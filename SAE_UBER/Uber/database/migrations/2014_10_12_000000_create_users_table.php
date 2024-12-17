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
            $table->increments('idclient');
            $table->unsignedInteger('idadresse');
            $table->string('nomuser');
            $table->string('prenomuser');
            $table->string('genreuser');
            $table->date('datenaissance');
            $table->string('telephone');
            $table->string('emailuser')->unique();
            $table->string('motdepasseuser');
            $table->timestamps();

            $table->foreign('idadresse')->references('id')->on('adresse')->onDelete('cascade');
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
