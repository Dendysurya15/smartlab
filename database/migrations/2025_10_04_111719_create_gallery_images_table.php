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
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('gallery_categories')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path', 500);
            $table->string('file_name');
            $table->integer('file_size')->unsigned()->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->integer('width')->unsigned()->nullable();
            $table->integer('height')->unsigned()->nullable();
            $table->string('alt_text')->nullable();
            $table->string('caption', 500)->nullable();
            $table->enum('position', ['hero', 'top-right', 'middle-right', 'bottom-left', 'bottom-center', 'bottom-right'])->default('hero');
            $table->string('grid_area', 50)->nullable();
            $table->boolean('is_carousel')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('overlay_gradient', 100)->nullable();
            $table->string('badge_text', 50)->nullable();
            $table->string('badge_color', 50)->nullable();
            $table->timestamps();

            $table->index(['is_active', 'position', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_images');
    }
};
