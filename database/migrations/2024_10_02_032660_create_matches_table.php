<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    public function up()
    { 
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competitions')->onDelete('cascade');
            $table->foreignId('equipe1_id')->constrained('equipes')->onDelete('cascade');
            $table->foreignId('equipe2_id')->constrained('equipes')->onDelete('cascade');
            $table->string('lieux')->nullable(); 
            $table->enum('statut', ['en_attente', 'termine', 'annule'])->default('en_attente');
            $table->integer('score_equipe1')->nullable();
            $table->integer('score_equipe2')->nullable();
            $table->date('date_matche');
            $table->json('buteurs')->nullable();
            $table->json('passeurs')->nullable();
            $table->foreignId('homme_du_matche')->nullable()->constrained('joueurs')->onDelete('set null');
            $table->json('cartons')->nullable();
            $table->enum('resultat', ['gagne', 'nul', 'perdu']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
