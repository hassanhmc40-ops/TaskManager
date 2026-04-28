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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Primary key: id (BIGINT, AUTO_INCREMENT)
            
            $table->string('title'); // Task title (VARCHAR 255, NOT NULL)
            $table->text('description')->nullable(); // Task description (TEXT, NULLABLE)
            
            // Status with enum values
            $table->enum('status', ['to_do', 'in_progress', 'completed'])
                  ->default('to_do'); // Default status is 'to_do'
            
            // Due date (bonus feature)
            $table->date('due_date')->nullable(); // Due date (DATE, NULLABLE)
            
            // Foreign key to users table
            $table->foreignId('user_id')
                  ->constrained('users') // References 'id' on 'users' table
                  ->onDelete('cascade'); // Delete tasks when user is deleted
            
            // Foreign key to categories table
            $table->foreignId('category_id')
                  ->nullable() // Category is optional
                  ->constrained('categories') // References 'id' on 'categories' table
                  ->onDelete('set null'); // Set to NULL when category is deleted
            
            $table->timestamps(); // created_at, updated_at (TIMESTAMP)
            
            // Indexes for better query performance
            $table->index('status'); // Index on status column
            $table->index('due_date'); // Index on due_date column
            $table->index('user_id'); // Index on user_id (already created by foreignId)
            $table->index('category_id'); // Index on category_id (already created by foreignId)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};