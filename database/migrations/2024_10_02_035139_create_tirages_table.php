<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tirages', function (Blueprint $table) {
            $table->id();  // Clé primaire auto-incrémentée
            $table->unsignedBigInteger('competition_id');  // Clé étrangère vers la table 'competitions'
            $table->enum('phase', ['Phase de groupes', 'Quarts de finale', 'Demi-finales', 'Finale']); // Liste explicite des phases            $table->json('poul')->enum;   // JSON représentant les poules de la compétition
            $table->foreign('competition_id')->references('id')->on('competitions')->onDelete('cascade');  // Si la compétition est supprimée, les tirages le sont aussi
            $table->json('poul')->nullable();
            $table->timestamps();  // Dates de création et de mise à jour
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tirages');
    }
}
