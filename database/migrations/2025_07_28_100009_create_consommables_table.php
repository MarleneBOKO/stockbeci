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
        Schema::create('consommables', function (Blueprint $table) {
            $table->id();
    $table->string('nom');
    $table->foreignId('categorie_id')->constrained()->onDelete('cascade');
    $table->string('numero_model')->nullable();
    $table->string('numero_article')->nullable();
    $table->integer('qte_min')->nullable();
    $table->foreignId('emplacement_id')->constrained()->onDelete('cascade');
    $table->string('num_commande')->nullable();
    $table->date('date_achat')->nullable();
    $table->decimal('cout_achat', 15, 2)->nullable();
    $table->foreignId('fournisseur_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('fabricant_id')->nullable()->constrained()->onDelete('set null');
    $table->integer('quantite')->default(0);
    $table->text('notes')->nullable();
    $table->string('images')->nullable();
            $table->timestamps();
        });

        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consommables');
    }
};
