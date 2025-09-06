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
            $table->string('contact')->nullable(); // Personne ressource
            $table->string('telephone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable(); // Messagerie électronique
            $table->string('site_web')->nullable(); // URL
            $table->text('notes')->nullable(); // Note (au pluriel)
            $table->string('image')->nullable();
            $table->timestamps();
        });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};
