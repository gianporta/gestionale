<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oauth_repo', function (Blueprint $table) {
            $table->id();
            $table->string('link_site', 255)->default('');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->unsignedBigInteger('id_repo')->nullable();

            $table->integer('is_active')->default(1);

            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->dateTime('expiration')->nullable();

            // indici
            $table->index('id_repo', 'repo_id_oauth_repo_id');
            $table->index('id_user', 'oauth_user_id_oauth_repo_user_id');

            // foreign key
            $table->foreign('id_user', 'oauth_user_id_oauth_repo_user_id')
                ->references('id')
                ->on('oauth_user')
                ->onDelete('cascade');

            $table->foreign('id_repo', 'repo_id_oauth_repo_id')
                ->references('id')
                ->on('repo')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_repo');
    }
};
