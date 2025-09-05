<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kit_id')->constrained('kits')->onDelete('cascade');
            $table->string('item_type'); // Actif, Composant, Accessoire, Consommable
            $table->unsignedBigInteger('item_id'); // id de la table correspondante
            $table->integer('quantite')->default(1);
            $table->timestamps();
        });

        Schema::disableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('kit_items');
    }
};
