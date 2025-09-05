<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alertes_seuil', function (Blueprint $table) {
            $table->id();
            $table->string('item_type'); // Actif, Composant, Accessoire, Consommable
            $table->unsignedBigInteger('item_id'); // id de l'élément concerné
            $table->integer('seuil_min'); // seuil minimum pour alerte
            $table->boolean('alerte_envoyee')->default(false);
            $table->timestamps();
        });

        Schema::disableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('alertes_seuil');
    }
};
