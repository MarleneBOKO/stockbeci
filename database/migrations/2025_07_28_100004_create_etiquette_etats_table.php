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
        Schema::create('etiquette_etats', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
    $table->enum('type', ['Déployable', 'En instance', 'Indéployable', 'Archivés']);
    $table->string('couleur');
    $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etiquette_etats');
    }
};
