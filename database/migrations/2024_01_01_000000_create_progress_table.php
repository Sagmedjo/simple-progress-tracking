<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_id')->constrained()->cascadeOnDelete();
            $table->decimal('value', 10, 2);
            $table->timestamp('tracked_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
