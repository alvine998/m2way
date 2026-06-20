<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActivityLogger
{
    private static bool $logging = false;

    public static function log(
        string $event,
        string $description,
        ?Model $subject = null,
        array $properties = [],
        ?int $userId = null,
        ?string $userName = null,
        ?string $userEmail = null,
    ): void {
        if (self::$logging) {
            return;
        }

        self::$logging = true;

        try {
            $user = Auth::user();

            ActivityLog::create([
                'user_id' => $userId ?? $user?->id,
                'user_name' => $userName ?? $user?->name,
                'user_email' => $userEmail ?? $user?->email,
                'event' => $event,
                'subject_type' => $subject ? $subject::class : null,
                'subject_id' => $subject?->getKey(),
                'subject_label' => $subject ? self::subjectLabel($subject) : null,
                'description' => $description,
                'properties' => self::sanitizeArray($properties),
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
            ]);
        } finally {
            self::$logging = false;
        }
    }

    public static function logModelEvent(Model $model, string $event): void
    {
        $modelName = Str::headline(class_basename($model));
        $label = self::subjectLabel($model);

        $properties = match ($event) {
            'created' => ['attributes' => self::sanitizeArray($model->getAttributes())],
            'updated' => self::changedProperties($model),
            'deleted' => ['old' => self::sanitizeArray($model->getOriginal())],
            default => [],
        };

        if ($event === 'updated' && empty($properties['changes'])) {
            return;
        }

        self::log(
            $event,
            "{$modelName} {$label} {$event}.",
            $model,
            $properties,
        );
    }

    public static function subjectLabel(Model $model): string
    {
        foreach (['name', 'transaction_number', 'entry_number', 'payment_number', 'group_number', 'document_number', 'email', 'file_name'] as $key) {
            $value = $model->getAttribute($key);
            if ($value) {
                return (string) $value;
            }
        }

        return '#' . $model->getKey();
    }

    private static function changedProperties(Model $model): array
    {
        $changes = collect($model->getChanges())
            ->except(['updated_at'])
            ->mapWithKeys(fn ($value, $key) => [
                $key => [
                    'old' => self::sanitizeValue($model->getOriginal($key)),
                    'new' => self::sanitizeValue($value),
                ],
            ])
            ->all();

        return ['changes' => $changes];
    }

    private static function sanitizeArray(array $values): array
    {
        return collect($values)
            ->mapWithKeys(fn ($value, $key) => [$key => self::isSensitive((string) $key) ? '[redacted]' : self::sanitizeValue($value)])
            ->all();
    }

    private static function sanitizeValue(mixed $value): mixed
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        return $value;
    }

    private static function isSensitive(string $key): bool
    {
        return in_array($key, ['password', 'remember_token'], true)
            || str_contains($key, 'token');
    }
}
