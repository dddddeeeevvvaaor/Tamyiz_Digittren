<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class AkunBaruUser extends Authenticatable
{
    use Notifiable;


    use HasFactory;

    protected $table = 'akun_baru_user';
    protected $primaryKey = 'id_newuser';

    protected $dates = [
        'mulai',
        'berakhir',
    ];


    protected $fillable = [
        'id_payment',
        'id_golongan',
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
        'city',
        'role',
        'status',
        'mulai',
        'berakhir',
        'expired_at',
    ];

    //membuat fitur mutator untuk mengubah nilai kolom status berdasarkan nilai kolom berakhir dan nilai kolom status sebelumnya jadi tidak perlu lagi menekan tombol status secara manual untuk mengubah statusnya jadi otomatis berubah statusnya ketika sudah melewati tanggal berakhirnya di database
    public function getStatusAttribute($value)
    {
        // Periksa apakah waktu berakhir sudah lewat dan status aktif
        if ($this->attributes['berakhir'] < now()) {
            $this->attributes['status'] = 'inactive';

            // Temukan dan perbarui catatan pembayaran yang terkait
            $payment = $this->payment;
            if ($payment) {
                $payment->update(['status' => 3]); // Ubah status pembayaran menjadi 3
            }

            // Simpan perubahan status ke database
            $this->save();

            $this->kirimnotifwhatsapp($payment->siswa->user->phone);

            return 'inactive';
        } else {
            if ($this->attributes['status'] === 'inactive') {
                $this->attributes['status'] = 'active';
                $this->save(); // Simpan perubahan status ke database
            }
            return 'active';
        }
    }

    public function kirimnotifwhatsapp($phone)
    {
        $message = "Assalamualaikum, " . $this->firstname . " " . $this->lastname . " \n";
        $message .= "Akun anda sudah tidak aktif, silahkan melakukan pembayaran kembali untuk mengaktifkan akun anda kembali. \n";
        $message .= "Terima kasih.";

        $api = WhatsAppAPI::first();

        $curl = curl_init();
        $token = $api->token;
        $data = [
            'phone' => $phone,
            'message' => $message,
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: $token",
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $api->base_server . '/api/send-message');
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    // jika button status sudah berubah sesuai dengan statusnya maka akan mengubah statusnya di database secara otomatis tanpa harus menekan tombol status secara manual
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value;
        $this->save(); // Simpan perubahan status ke database

        // Retrieve the associated Payment record
        $payment = $this->payment;

        if ($payment) {
            // Update the status in the associated Payment record
            if ($value === 'inactive') {
                $payment->update(['status' => 2]); // Assuming '2' represents 'inactive' status in the payments table
            } else {
                $payment->update(['status' => 1]); // Assuming '1' represents 'active' status in the payments table
            }
        }
    }

    //membuat fitur mutator untuk mengubah nilai kolom berakhir berdasarkan nilai kolom mulai
    public function setBerakhirAttribute($value)
    {
        $payment = Payment::find($this->attributes['id_payment']);

        // Pastikan 'mulai' adalah objek Carbon atau DateTime
        $mulai = Carbon::parse($this->attributes['mulai']);

        // Sekarang Anda bisa memanggil 'copy' dan 'addMonths'
        $this->attributes['berakhir'] = $mulai->copy()->addMonths($payment->jumlah_pembayaran);
    }


    public function payment()
    {
        return $this->belongsTo(Payment::class, 'id_payment');
    }

    public function golongan()
    {
        return $this->belongsTo(Golongan::class, 'id_golongan');
    }
}
