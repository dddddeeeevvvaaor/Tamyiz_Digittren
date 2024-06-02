<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppAPI extends Model
{
    use HasFactory;
    
    protected $table = 'whatsapp_api';
    protected $primaryKey = 'id_wa_api';
    protected $guarded = [];
    protected $fillable = [
        'token',
        'base_server',
    ];
}
