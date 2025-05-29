<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('correos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remitente_id');
            $table->unsignedBigInteger('destinatario_id')->nullable();
            $table->string('email_destinatario')->nullable();
            $table->string('asunto');
            $table->text('contenido');
            $table->date('fecha_entrega')->nullable();
            $table->string('ubicacion_entrega')->nullable();
            $table->json('productos_solicitados')->nullable();
            $table->boolean('leido')->default(false);
            $table->string('tipo')->default('interno'); // interno, externo
            $table->timestamps();

            $table->foreign('remitente_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('destinatario_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('correos');
    }
};