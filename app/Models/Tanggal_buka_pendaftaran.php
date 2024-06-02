<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanggal_buka_pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'tanggal_buka_pendaftaran';
    protected $primaryKey = 'id_tglpendaftaran';
    protected $fillable = ['tanggal_buka', 'tanggal_tutup', 'tanggal_program'];
}
