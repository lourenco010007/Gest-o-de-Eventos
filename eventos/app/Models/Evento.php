<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evento extends Model
{
    public const STATUS_PENDENTE = 'pendente';
    public const STATUS_CONFIRMADO = 'confirmado';
    public const STATUS_CANCELAMENTO_SOLICITADO = 'cancelamento_solicitado';
    public const STATUS_ADIAMENTO_SOLICITADO = 'adiamento_solicitado';
    public const STATUS_CANCELADO = 'cancelado';
    public const STATUS_ADIADO = 'adiado';

    protected $casts = [
        'items' => 'array',
        'private' => 'boolean',
        'date' => 'date',
        'requested_date' => 'date',
        'requested_at' => 'datetime',
    ];

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salao(): BelongsTo
    {
        return $this->belongsTo(Salao::class);
    }

    public function isCancelado(): bool
    {
        return $this->status === self::STATUS_CANCELADO;
    }

    public function isAlteracaoSolicitada(): bool
    {
        return in_array($this->status, [
            self::STATUS_CANCELAMENTO_SOLICITADO,
            self::STATUS_ADIAMENTO_SOLICITADO,
        ], true);
    }
}
