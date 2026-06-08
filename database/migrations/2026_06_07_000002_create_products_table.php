<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('artist')->nullable();
            $table->integer('year')->nullable();
            $table->string('format')->default('CD');
            $table->string('genre')->nullable();
            $table->integer('price')->default(0);
            $table->integer('stock')->default(0);
            $table->string('label')->nullable();
            $table->string('condition')->default('New');
            $table->text('description')->nullable();
            $table->string('cover')->default('cover-a');
            $table->json('covers')->nullable();
            $table->string('badge')->nullable();
            $table->json('tracklist')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
