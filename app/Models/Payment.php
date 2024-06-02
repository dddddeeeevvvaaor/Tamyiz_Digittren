<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = "payments";
    protected $primaryKey = 'id_payment';
    protected $fillable = [
        'nist',
        'id_bank',
        'id_periodedaftar',
        'nama_bank',
        'pemilik_rekening',
        'nominal',
        'img_bukti',
        'status',
        'infaq',
        'jumlah_pembayaran',
    ];
    public function siswa(){
        return $this->belongsTo(Siswa::class,  'nist');
    }

    public function bank(){
        return $this->belongsTo(Bank::class,  'id_bank');
    }

    public function periode_pendaftaran(){
        return $this->belongsTo(Periode_pendaftaran::class,  'id_periodedaftar');
    }
}
