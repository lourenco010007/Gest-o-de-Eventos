<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saloes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cidade');
            $table->unsignedInteger('capacidade')->default(100);
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        DB::table('saloes')->insert([
            [
                'nome' => 'WestPoint',
                'cidade' => 'Maputo',
                'capacidade' => 240,
                'descricao' => 'Salao versatil para eventos corporativos, sociais e academicos.',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'El Shadai',
                'cidade' => 'Matola',
                'capacidade' => 180,
                'descricao' => 'Espaco acolhedor para casamentos, aniversarios e celebracoes familiares.',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Goodness',
                'cidade' => 'Sofala',
                'capacidade' => 150,
                'descricao' => 'Salao preparado para eventos culturais, reunioes e workshops.',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nome' => 'Lovely',
                'cidade' => 'Tete',
                'capacidade' => 120,
                'descricao' => 'Ambiente compacto para eventos privados e encontros executivos.',
                'ativo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('saloes');
    }
};
