<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode_pendaftaran extends Model
{
    use HasFactory;
    protected $table = 'periode_pendaftaran';
    protected $primaryKey = 'id_periodedaftar';
    protected $fillable = [
        'periode',
        'diskon',
    ];
}
