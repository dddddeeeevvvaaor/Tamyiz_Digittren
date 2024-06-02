<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting_Tamyiz extends Model
{
    use HasFactory;

    protected $table = 'setting_tamyiz';
    protected $primaryKey = 'id_settamyiz';

    protected $fillable = [
        'nama_pesantren',
        'kode_pos',
        'nomor_telpon',
        'alamat',
        'website',
        'email',
        'logo',
    ];
}
