<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('escolas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cnpj');
            $table->string('uf', 2);
            $table->string('cidade');
            $table->boolean('endereco_em_breve')->default(false);
            $table->string('cep')->nullable();
            $table->string('endereco')->nullable();
            $table->string('numero_endereco')->nullable();
            $table->string('complemento')->nullable();
            $table->string('instagram')->nullable();
            $table->string('celular');
            $table->string('logo_path');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escolas');
    }
};
