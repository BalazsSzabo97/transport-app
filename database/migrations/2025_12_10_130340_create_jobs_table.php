<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('from_address');
            $table->string('to_address');
            $table->string('recipient_name');
            $table->string('recipient_phone');

            $table->enum('status', [
                'assigned',
                'in_progress',
                'completed',
                'failed'
            ])->default('assigned');

            // Nullable because jobs can be created before assignment
            $table->foreignId('driver_id')->nullable()
                  ->constrained('drivers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
