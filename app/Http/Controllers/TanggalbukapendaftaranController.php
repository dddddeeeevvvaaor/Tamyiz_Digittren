<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tanggal_buka_pendaftaran;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class TanggalbukapendaftaranController extends Controller
{
    public function index()
    {
        $tanggalbukapendaftaran = Tanggal_buka_pendaftaran::all();

        foreach ($tanggalbukapendaftaran as $pendaftaran) {
            $pendaftaran->tanggal_buka = Carbon::parse($pendaftaran->tanggal_buka)->format('d/m/Y');
            $pendaftaran->tanggal_tutup = Carbon::parse($pendaftaran->tanggal_tutup)->format('d/m/Y');
            $pendaftaran->tanggal_program = Carbon::parse($pendaftaran->tanggal_program)->format('d/m/Y');
        }

        return view('dashboard.tanggalbukapendaftaran.index', compact('tanggalbukapendaftaran'));
    }

    public function create()
    {
        return view('dashboard.tanggalbukapendaftaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_buka' => 'required',
            'tanggal_tutup' => 'required',
            'tanggal_program' => 'required',
        ]);

        try {
            Tanggal_buka_pendaftaran::create([
                'tanggal_buka' => $request->tanggal_buka,
                'tanggal_tutup' => $request->tanggal_tutup,
                'tanggal_program' => $request->tanggal_program,
            ]);

            Alert::success('Success', 'Data Tanggal Buka Pendaftaran Berhasil Ditambahkan!');
            return redirect()->route('tanggalbukapendaftaran.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data Tanggal Buka Pendaftaran.');
            return redirect()->back()->withInput();
        }
    }

    public function edit(Tanggal_buka_pendaftaran $tanggalbukapendaftaran)
    {
        $tanggalbukapendaftaran->tanggal_buka = Carbon::parse($tanggalbukapendaftaran->tanggal_buka)->format('Y-m-d');
        $tanggalbukapendaftaran->tanggal_tutup = Carbon::parse($tanggalbukapendaftaran->tanggal_tutup)->format('Y-m-d');
        $tanggalbukapendaftaran->tanggal_program = Carbon::parse($tanggalbukapendaftaran->tanggal_program)->format('Y-m-d');

        return view('dashboard.tanggalbukapendaftaran.edit', compact('tanggalbukapendaftaran'));
    }


    public function update(Request $request, Tanggal_buka_pendaftaran $tanggalbukapendaftaran)
    {
        $request->validate([
            'tanggal_buka' => 'required',
            'tanggal_tutup' => 'required',
            'tanggal_program' => 'required',
        ]);

        try {
            Tanggal_buka_pendaftaran::where('id_tglpendaftaran', $tanggalbukapendaftaran->id_tglpendaftaran)
                ->update([
                    'tanggal_buka' => $request->tanggal_buka,
                    'tanggal_tutup' => $request->tanggal_tutup,
                    'tanggal_program' => $request->tanggal_program,
                ]);

            Alert::success('Success', 'Data Tanggal Buka Pendaftaran Berhasil Diubah!');
            return redirect()->route('tanggalbukapendaftaran.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal mengubah data Tanggal Buka Pendaftaran.');
            return redirect()->back()->withInput();
        }
    }

    public function destroy(Tanggal_buka_pendaftaran $tanggalbukapendaftaran)
    {
        try {
            Tanggal_buka_pendaftaran::destroy($tanggalbukapendaftaran->id_tglpendaftaran);
            Alert::success('Success', 'Data Tanggal Buka Pendaftaran Berhasil Dihapus!');
            return redirect()->route('tanggalbukapendaftaran.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus data Tanggal Buka Pendaftaran.');
            return redirect()->back();
        }
    }
}
