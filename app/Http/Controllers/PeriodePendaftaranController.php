<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periode_pendaftaran;
use RealRashid\SweetAlert\Facades\Alert;

class PeriodePendaftaranController extends Controller
{
    public function index()
    {
        $periode = Periode_pendaftaran::all();
        return view('dashboard.periode_pendaftaran.index', compact('periode'));
    }

    public function create()
    {
        return view('dashboard.periode_pendaftaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            //validasi harus angka
            'periode' => 'required|numeric|unique:periode_pendaftaran',
            'diskon' => 'required|numeric|between:0,100',
        ], [
            'periode.required' => 'Periode pendaftaran harus diisi',
            'periode.numeric' => 'Periode pendaftaran harus berupa angka',
            'periode.unique' => 'Periode pendaftaran sudah ada',
            'diskon.required' => 'Diskon harus diisi',
            'diskon.numeric' => 'Diskon harus berupa angka',
            'diskon.between' => 'Diskon harus diantara 0 sampai 100',
        ]);

        try {
            Periode_pendaftaran::create([
                'periode' => $request->periode,
                'diskon' => $request->diskon,
            ]);

            Alert::success('Success', 'Data Periode Pendaftaran Berhasil Ditambahkan!');
            return redirect()->route('periode_pendaftaran.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data periode pendaftaran: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg', 'Gagal menambahkan data periode pendaftaran']);
        }
    }

    public function edit(Periode_pendaftaran $periode)
    {
        return view('dashboard.periode_pendaftaran.edit', compact('periode'));
    }

    public function update(Request $request, Periode_pendaftaran $periode)
    {
        $request->validate([
            'periode' => 'required|numeric',
            'diskon' => 'required|numeric',
        ], [
            'periode.required' => 'Periode pendaftaran harus diisi',
            'periode.numeric' => 'Periode pendaftaran harus berupa angka',
            'diskon.required' => 'Diskon harus diisi',
            'diskon.numeric' => 'Diskon harus berupa angka',
        ]);

        try {
            $periode->update([
                'periode' => $request->periode,
                'diskon' => $request->diskon,
            ]);

            Alert::success('Success', 'Data Periode Pendaftaran Berhasil Diubah!');
            return redirect()->route('periode_pendaftaran.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal mengubah data periode pendaftaran: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg', 'Gagal mengubah data periode pendaftaran']);
        }
    }

    public function destroy(Periode_pendaftaran $periode)
    {
        try {
            $periode->delete();
            Alert::success('Success', 'Data Periode Pendaftaran Berhasil Dihapus!');
            return redirect()->route('periode_pendaftaran.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus data periode pendaftaran: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg', 'Gagal menghapus data periode pendaftaran']);
        }
    }
}
