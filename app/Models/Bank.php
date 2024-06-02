<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $table = 'bank';
    protected $primaryKey = 'id_bank';
    protected $fillable = [
        'nama',
        'no_rekening',
        'atas_nama',
        'phone',
    ];
}
