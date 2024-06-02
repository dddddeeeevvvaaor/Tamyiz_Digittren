<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infaq_Perbulan extends Model
{
    use HasFactory;

    protected $table = "infaq";
    protected $primaryKey = 'id_infaq';
    protected $fillable = [
        'id_user',
        'nist',
        'id_bank',
        'nama_bank',
        'pemilik_rekening',
        'nominal',
        'img_bukti',
        'status',
    ];

    public function siswa(){
        return $this->belongsTo(Siswa::class,  'nist');
    }

    public function user(){
        return $this->belongsTo(User::class,  'id_user');
    }

    public function bank(){
        return $this->belongsTo(Bank::class,  'id_bank');
    }
}
