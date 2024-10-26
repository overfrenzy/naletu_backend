<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['special', 'type']);
            $table->string('slug')->unique()->after('name');
            $table->text('description')->nullable()->after('slug');
            $table->string('image2')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('special')->nullable();
            $table->enum('type', ['home', 'banner']);
            $table->dropColumn(['slug', 'description', 'image2']);
        });
    }
};
