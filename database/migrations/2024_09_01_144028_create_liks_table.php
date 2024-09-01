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
        Schema::create('liks', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->foreignId('comment_id')->nullable()->constrained();
            $table->foreignId('post_id')->nullable()->constrained();
            $table->timestamps();

            $table->primary(['user_id', 'comment_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liks');
    }
};
