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
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
    $table->string('adresse')->nullable();
    $table->string('ville')->nullable();
    $table->string('etat')->nullable();
    $table->string('pays')->nullable();
    $table->string('fermeture_eclair')->nullable();
    $table->string('nom_personne_ressource')->nullable();
    $table->string('telephone')->nullable();
    $table->string('fax')->nullable();
    $table->string('messagerie_electronique')->nullable();
    $table->string('url')->nullable();
    $table->text('note')->nullable();
    $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
