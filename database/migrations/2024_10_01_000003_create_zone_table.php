<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('localite');
            $table->unsignedBigInteger(column: 'user_id')->nullable();  // Clé étrangère vers la table 'users', gestionnaire de l'équipe, peut être nul
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');  // Clé étrangère liée à 'users', met à null en cas de suppression de l'utilisateur
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zones');
    }
}
