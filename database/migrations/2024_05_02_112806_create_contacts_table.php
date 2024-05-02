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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string("first_name", 25)->nullable(false);
            $table->string("last_name", 25)->nullable();
            $table->string("email", 25)->nullable();
            $table->string("phone", 20)->nullable(false)->unique();
            $table->unsignedBigInteger("user_id")->nullable(false);
            $table->timestamps();
            $table->foreign("user_id")->on("users")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};