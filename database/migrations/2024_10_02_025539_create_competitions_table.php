<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();  // Clé primaire auto-incrémentée
            $table->string('nom');  // Nom de la compétition
            $table->date('date_debut');  // Date de début de la compétition
            $table->date('date_fin');  // Date de fin de la compétition
             $table->string('lieux')->default('Dakar'); // Assurez-vous de définir une valeur par défaut
            $table->timestamps();  // Dates de création et de mise à jour
        });

        // Table pivot pour la relation entre competitions et equipes
        Schema::create('competition_equipe', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competitions')->onDelete('cascade');
            $table->foreignId('equipe_id')->constrained('equipes')->onDelete('cascade');
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
        Schema::dropIfExists('competition_equipe');
        Schema::dropIfExists('competitions');
    }
}
