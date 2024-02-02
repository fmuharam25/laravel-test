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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username',20);
            $table->string('phone',30)->nullable();
            $table->enum('role', ['guest', 'admin'])->default('guest');
            $table->enum('is_subscribe', ['subscribed', 'unsubscribe'])->default('subscribed');


        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('phone');
            $table->dropColumn('role');
            $table->dropColumn('is_subscribe');
        });
    
    }
};
