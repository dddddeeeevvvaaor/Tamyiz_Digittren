<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akuntansi extends Model
{
    use HasFactory;

    protected $table = 'akuntansi';
    protected $primaryKey = 'id_akuntan';

    protected $fillable = [
        'id_payment',
        'id_infaq',
        'tanggal',
        'keterangan_program',
        'keterangan_infaq',
        'debet',
        'saldo',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'id_payment');
    }
}