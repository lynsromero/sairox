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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('post_author')->unsigned()->default(0);
            $table->longText('post_content');
            $table->text('post_title');
            $table->text('post_excerpt');
            $table->string('post_status')->default('publish');
            $table->string('comment_status')->default('open');
            $table->string('slug')->unique();
            $table->string('post_type')->default('post');
            $table->bigInteger('comment_count')->default('0');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
