<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salao extends Model
{
    protected $table = 'saloes';

    protected $fillable = [
        'nome',
        'cidade',
        'capacidade',
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function eventos(): HasMany
    {
        return $this->hasMany(Evento::class);
    }
}
