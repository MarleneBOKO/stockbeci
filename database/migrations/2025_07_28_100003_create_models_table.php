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
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
    $table->foreignId('categorie_id')->nullable()->constrained('categories')->onDelete('set null')->nullable();
    $table->foreignId('fabricant_id')->nullable()->constrained('fabricants')->onDelete('set null')->nullable();
    $table->string('model_num')->nullable();
    $table->foreignId('amortissement_id')->nullable()->constrained('amortissements')->onDelete('set null')->nullable();
    $table->integer('qte_min')->nullable();
    $table->integer('findevie')->nullable();
    $table->text('notes')->nullable();
    $table->string('ensemble_champs')->nullable();
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
        Schema::dropIfExists('models');
    }
};
