<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaah_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_number')->unique();
            $table->string('name');
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leader_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            $table->integer('quota')->default(40);
            $table->text('notes')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaah_groups');
    }
};
