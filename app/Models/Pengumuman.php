<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';
    protected $primaryKey = 'id_pengumuman';
    protected $fillable = [
        'id_user',
        'judul',
        'isi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
