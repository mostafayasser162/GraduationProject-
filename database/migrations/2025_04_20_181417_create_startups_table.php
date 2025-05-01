<?php

use App\Enums\StartUps\Status;
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
        Schema::create('startups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();

            $table->json('social_media_links')->nullable(); // ممكن تبقى array (facebook, instagram, linkedin...)
            $table->string('phone')->nullable();

            $table->string('status')->default(Status::PENDING());
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('categories_id')->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('startups');
    }
};
