<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tusuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('name', 50);
            $table->string('email', 100)->unique();
            $table->string('contrasena', 100);
            $table->timestamps();
        });
    }

     /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tusuarios');
    }
};
