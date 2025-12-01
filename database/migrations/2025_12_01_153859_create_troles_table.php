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
        Schema::create('troles', function (Blueprint $table) {
            $table->id('id_rol');
            $table->string('nombre_rol', 50);

            // Relación con tusuarios (id_usuario)
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->foreign('id_usuario')
                ->references('id_usuario')->on('tusuarios')
                ->onDelete('cascade');

            // Relación con admins (id)
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->foreign('admin_id')
                ->references('id')->on('admins')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

     /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('troles');
    }
};
