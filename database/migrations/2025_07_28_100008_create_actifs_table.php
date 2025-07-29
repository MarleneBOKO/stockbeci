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
        Schema::create('actifs', function (Blueprint $table) {
            $table->id();
            $table->string('inventaire')->nullable();
            $table->string('serial')->nullable();
            $table->foreignId('model_id')->constrained('models')->onDelete('cascade');
            $table->string('statut')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('emplacement_id')->constrained()->onDelete('cascade');
            $table->boolean('sur_demande')->default(false);
            $table->string('image')->nullable();
            $table->string('nom_actif')->nullable();
            $table->string('garantie')->nullable();
            $table->date('date_verification')->nullable();
            $table->date('date_achat')->nullable();
            $table->date('date_fin_vie')->nullable();
            $table->foreignId('fournisseur_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('cout_achat', 15, 2)->nullable();
            $table->foreignId('projet_id')->nullable()->constrained()->onDelete('set null');
            $table->unsignedBigInteger('utilisateur_id')->nullable();
            $table->foreign('utilisateur_id')->references('idUser')->on('users')->nullOnDelete();
            $table->decimal('valeur_actuelle', 15, 2)->nullable();
            $table->timestamps();
        });

        Schema::disableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actifs');
    }
};
