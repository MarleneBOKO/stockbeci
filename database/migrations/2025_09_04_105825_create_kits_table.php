<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kits', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // nom du kit
            $table->text('description')->nullable();
            $table->string('images')->nullable();
            $table->timestamps();
        });

        Schema::disableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('kits');
    }
};
