<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\WhatsAppAPI;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PengumumanController extends Controller

{
    public function index()
    {
        $pengumuman = Pengumuman::all();
        return view('dashboard.pengumuman.index', compact('pengumuman'));
    }

    public function create()
    {
        $pengumuman = Pengumuman::where('id_pengumuman', Pengumuman::max('id_pengumuman'))->get();
        return view('dashboard.pengumuman.create', compact('pengumuman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|min:5|max:15',
            'isi' => 'required|min:5|max:255',
        ], [
            'judul.required' => 'Judul tidak boleh kosong',
            'judul.min' => 'Judul minimal 5 karakter',
            'judul.max' => 'Judul maksimal 15 karakter',
            'isi.required' => 'Isi tidak boleh kosong',
            'isi.min' => 'Isi minimal 5 karakter',
            'isi.max' => 'Isi maksimal 255 karakter',
        ]);
        $userId = Auth::user()->id_user;
        $payment = Payment::whereHas('siswa', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        })->first();

        // Mengambil pengguna dengan status pembayaran 1
        $users = User::join('santri', 'users.id_user', '=', 'santri.id_user')
            ->join('payments', 'santri.nist', '=', 'payments.nist')
            ->where('payments.status', 1)
            ->get();


        foreach ($users as $user) {
            $this->notifikasiwhatsapp($user->phone, $request->judul, $request->isi);
        }

        try {
            Pengumuman::create([
                'id_user' => Auth::user()->id_user,
                'judul' => $request->judul,
                'isi' => $request->isi,
            ]);
            Alert::success('Success', 'Data Pengumuman Berhasil Ditambahkan!');
            return redirect()->route('pengumuman.create');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data pengumuman: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg', 'Gagal menambahkan data pengumuman']);
        }
    }

    public function notifikasiwhatsapp($phone, $judul, $isi)
    {
        $message = "Assalamualaikum,\n\n" . $judul . "\n" . $isi . "\n\n" . "Wassalamualaikum Wr. Wb.";

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

    public function show($id)
    {
        $pengumuman = Pengumuman::find($id);
        return view('dashboard.pengumuman.show', compact('pengumuman'));
    }

    public function edit(Pengumuman $pengumuman)
    {
        return view('dashboard.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required|min:5|max:15',
            'isi' => 'required|min:5|max:255',
        ], [
            'judul.required' => 'Judul tidak boleh kosong',
            'judul.min' => 'Judul minimal 5 karakter',
            'judul.max' => 'Judul maksimal 15 karakter',
            'isi.required' => 'Isi tidak boleh kosong',
            'isi.min' => 'Isi minimal 5 karakter',
            'isi.max' => 'Isi maksimal 255 karakter',
        ]);

        $updated = Pengumuman::where('id_pengumuman', $pengumuman->id_pengumuman)
            ->update([
                'judul' => $request->judul,
                'isi' => $request->isi,
            ]);

        if ($updated) {
            Alert::success('Success', 'Data Pengumuman Berhasil Diubah!');
            return redirect()->route('pengumuman.index');
        } else {
            Alert::error('Error', 'Gagal mengubah data pengumuman');
            return redirect()->back();
        }
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();
        Alert::success('Success', 'Data Pengumuman Berhasil Dihapus!');
        return redirect()->route('pengumuman.index');
    }
}
