<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Golongan;
use RealRashid\SweetAlert\Facades\Alert;

class GolonganController extends Controller
{
    public function index()
    {
        $golongan = Golongan::all();
        return view('dashboard.golongan.index', compact('golongan'));
    }

    public function create()
    {
        return view('dashboard.golongan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_golongan' => 'required|min:3|max:10', //membuat validasi nama_golongan harus diisi dan minimal 3 karakter
            //membuat validasi max_santri harus angka dan tidak boleh negatif
            'max_santri' => 'required|numeric|digits_between:1,11',
        ], [
            'nama_golongan.required' => 'Nama golongan tidak boleh kosong',
            'nama_golongan.min' => 'Nama golongan minimal 3 karakter',
            'nama_golongan.max' => 'Nama golongan maksimal 10 karakter',
            'max_santri.required' => 'Max santri tidak boleh kosong',
            'max_santri.numeric' => 'Max santri harus berupa angka',
            'max_santri.digits_between' => 'Max santri minimal 1 dan maksimal 11',
        ]);

        try {
            Golongan::create([
                'nama_golongan' => $request->nama_golongan,
                'max_santri' => $request->max_santri,
            ]);

            Alert::success('Success', 'Data Golongan Berhasil Ditambahkan!');
            return redirect()->route('golongan.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data golongan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg', 'Gagal menambahkan data golongan']);
        }
    }

    public function edit(Golongan $golongan)
    {
        return view('dashboard.golongan.edit', compact('golongan'));
    }

    public function update(Request $request, Golongan $golongan)
    {
        $request->validate([
            'nama_golongan' => 'required|min:3|max:10', //membuat validasi nama_golongan harus diisi dan minimal 3 karakter
            //membuat validasi max_santri harus angka dan tidak boleh negatif
            'max_santri' => 'required|numeric|digits_between:1,11',
        ], [
            'nama_golongan.required' => 'Nama golongan tidak boleh kosong',
            'nama_golongan.min' => 'Nama golongan minimal 3 karakter',
            'nama_golongan.max' => 'Nama golongan maksimal 10 karakter',
            'max_santri.required' => 'Max santri tidak boleh kosong',
            'max_santri.numeric' => 'Max santri harus berupa angka',
            'max_santri.digits_between' => 'Max santri minimal 1 dan maksimal 11',
        ]);

        try {
            $golongan->update([
                'nama_golongan' => $request->nama_golongan,
                'max_santri' => $request->max_santri,
            ]);

            Alert::success('Success', 'Data Golongan Berhasil Diubah!');
            return redirect()->route('golongan.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal mengubah data golongan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg', 'Gagal mengubah data golongan']);
        }
    }

    public function destroy(Golongan $golongan)
    {
        try {
            $golongan->delete();
            Alert::success('Success', 'Data Golongan Berhasil Dihapus!');
            return redirect()->route('golongan.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus data golongan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg', 'Gagal menghapus data golongan']);
        }
    }
}
