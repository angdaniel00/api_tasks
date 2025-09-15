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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('removed')->default(false);
            $table->timestamps();
        });

        Schema::table('users', function(Blueprint $table){
            $table->unsignedBigInteger('team_id')->nullable();
            $table->boolean('is_superuser')->default(false);
            $table->boolean('removed')->default(false);
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
