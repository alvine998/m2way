<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaah_group_customer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('room_type')->default('double');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['jamaah_group_id', 'customer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaah_group_customer');
    }
};
