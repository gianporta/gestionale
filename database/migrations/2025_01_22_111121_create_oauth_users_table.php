<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oauth_user', function (Blueprint $table) {
            $table->id();

            $table->string('user', 255)->default('');
            $table->string('email', 255)->default('');
            $table->string('psw', 255)->default('');

            $table->integer('is_active')->default(1);

            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->dateTime('expiration')->nullable();

            $table->unique('user', 'unique_user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_user');
    }
};
