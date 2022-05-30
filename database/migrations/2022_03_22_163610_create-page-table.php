<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            
            $table->uuid('id')->primary();
            $table->string('title');
            $table->enum('type',['home','page','post'])->default('page');
            $table->string('meta_keyword')->nullable();
            $table->string('meta_decryption')->nullable();
            $table->boolean('publish')->default(false);
            $table->dateTime('publish_time')->nullable();
            $table->boolean('show_comment')->default(false);
            $table->string('url')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }
};
