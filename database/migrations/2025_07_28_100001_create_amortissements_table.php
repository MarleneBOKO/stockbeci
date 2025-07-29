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
        Schema::create('amortissements', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
    $table->integer('nombremois');
    $table->decimal('valeur_min_apres_amortissement', 12, 2);
    $table->enum('type_valeur', ['Quantite', 'Pourcentage']);
            $table->timestamps();
        });

        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amortissements');
    }
};
