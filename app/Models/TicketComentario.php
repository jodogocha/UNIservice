<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComentario extends Model
{
    use HasFactory;

    protected $table = 'ticket_comentarios';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'comentario',
    ];

    /**
     * Relación con Ticket
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Relación con Usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}