<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('term_relationships', function (Blueprint $table) {
            $table->id();
            $table->morphs('termable');
            $table->foreignId('term_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['termable_id', 'termable_type', 'term_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_relationships');
    }
};
