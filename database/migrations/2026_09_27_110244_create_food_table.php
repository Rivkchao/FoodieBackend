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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // e.g. 'Burgers', 'Pizza'
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->decimal('rating', 3, 1)->default(5.0);
            $table->unsignedInteger('reviews')->default(0);
            $table->text('image');
            $table->json('ingredients')->nullable(); // array of string
            $table->string('prepTime')->default('15-20 min');
            $table->string('calories')->default('500 kkal');
            $table->boolean('popular')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
