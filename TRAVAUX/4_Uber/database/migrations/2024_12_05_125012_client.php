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
        Schema::create('client', function (Blueprint $table) {
            $table->bigIncrements('idclient'); // Clé primaire auto-incrémentée
            $table->unsignedBigInteger('idpanier')->nullable(); // Ajout de idpanier
            $table->unsignedBigInteger('idplanning')->nullable(); // Ajout de idpanier
            $table->unsignedBigInteger('idadresse')->nullable(); // Clé étrangère
            $table->string('nomuser');
            $table->string('prenomuser')->nullable();
            $table->string('genreuser')->nullable();
            $table->date('datenaissance')->nullable();
            $table->string('telephone', 10)->nullable();
            $table->string('emailuser')->unique();
            $table->string('motdepasseuser');
            $table->timestamps();

            // Clé étrangère
            $table->foreign('idadresse')->references('idadresse')->on('adresse')->onDelete('cascade');
            $table->foreign('idpanier')->references('idpanier')->on('panier')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client');
    }
};
