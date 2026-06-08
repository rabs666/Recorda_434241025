<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image')->nullable()->after('cover');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->string('image')->nullable()->after('cover');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
