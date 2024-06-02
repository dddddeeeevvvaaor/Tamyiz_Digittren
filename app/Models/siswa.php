<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'santri';
    protected $primaryKey = 'nist';
    protected $fillable = [
        'id_user',
        'id_program',
        'nist',
        'tanggal_lahir',
        'jenis_kelamin',
        'city',
    ];
    public function pembayaran()
    {
        return $this->hasMany(Payment::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'id_program');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Model Siswa
    public function payments()
    {
        return $this->hasMany(Payment::class, 'nist');
    }
}
