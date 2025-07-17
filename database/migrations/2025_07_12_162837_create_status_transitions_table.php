<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('status_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_status_id')
                ->constrained('task_statuses')
                ->cascadeOnDelete();

            $table->foreignId('to_status_id')
                ->constrained('task_statuses')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['from_status_id', 'to_status_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_transitions');
    }
};
