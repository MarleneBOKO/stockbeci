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
        Schema::create('accessoires', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
    $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null')->nullable();
    $table->integer('quantite');
    $table->integer('qte_min');
    $table->foreignId('fabricant_id')->nullable()->constrained('fabricants')->onDelete('set null')->nullable();
    $table->string('numero_model')->nullable();
    $table->foreignId('emplacement_id')->nullable()->constrained('emplacements')->onDelete('set null')->nullable();
    $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs')->onDelete('set null')->nullable();
    $table->string('num_commande')->nullable();
    $table->date('date_achat')->nullable();
    $table->decimal('cout_achat', 12, 2)->nullable();
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
        Schema::dropIfExists('accessoires');
    }
};
