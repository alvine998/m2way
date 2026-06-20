<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->text('description')->nullable();
            $table->unsignedInteger('duration');
            $table->date('departure_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('departure_city')->default('Jakarta');
            $table->unsignedInteger('quota')->default(0);
            $table->unsignedInteger('quota_remaining')->default(0);
            $table->decimal('base_price', 15, 2);
            $table->decimal('hpp', 15, 2)->default(0);
            $table->json('cost_details')->nullable();
            $table->string('airline')->nullable();
            $table->string('hotel_name')->nullable();
            $table->unsignedTinyInteger('hotel_star')->nullable();
            $table->json('includes')->nullable();
            $table->json('excludes')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
