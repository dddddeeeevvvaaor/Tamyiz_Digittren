<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Infaq_Perbulan;
use RealRashid\SweetAlert\Facades\Alert;

class InfaqPerbulanController extends Controller
{
    public function index()
    {
        $infaq_perbulan = Infaq_Perbulan::all();
        return view('dashboard.infaq_perbulan.index', compact('infaq_perbulan'));
    }

    public function create()
    {
        return view('dashboard.infaq_perbulan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'nist' => 'required',
            'id_bank' => 'required',
            'nama_bank' => 'required|min:3|max:15',
            'pemilik_rekening' => 'required|min:3|max:50',
            'nominal' => 'required',
            'img_bukti' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'status' => 'required',
        ], [
            'id_user.required' => 'User ID tidak boleh kosong',
            'nist.required' => 'Siswa ID tidak boleh kosong',
            'id_bank.required' => 'Bank ID tidak boleh kosong',
            'nama_bank.required' => 'Nama Bank tidak boleh kosong',
            'nama_bank.min' => 'Nama Bank minimal 3 karakter',
            'nama_bank.max' => 'Nama Bank maksimal 15 karakter',
            'pemilik_rekening.required' => 'Pemilik Rekening tidak boleh kosong',
            'pemilik_rekening.min' => 'Pemilik Rekening minimal 3 karakter',
            'pemilik_rekening.max' => 'Pemilik Rekening maksimal 50 karakter',
            'nominal.required' => 'Nominal tidak boleh kosong',
            'img_bukti.required' => 'Bukti Pembayaran tidak boleh kosong',
            'img_bukti.image' => 'Bukti Pembayaran harus berupa gambar',
            'img_bukti.mimes' => 'Bukti Pembayaran harus berformat jpg, png, jpeg',
            'img_bukti.max' => 'Bukti Pembayaran maksimal 2MB',
            'status.required' => 'Status tidak boleh kosong',
        ]);

        try {
            Infaq_Perbulan::create([
                'id_user' => $request->id_user,
                'nist' => $request->nist,
                'id_bank' => $request->id_bank,
                'nama_bank' => $request->nama_bank,
                'pemilik_rekening' => $request->pemilik_rekening,
                'nominal' => $request->nominal,
                'img_bukti' => $request->img_bukti,
                'status' => $request->status,
            ]);

            Alert::success('Success', 'Data Infaq Perbulan Berhasil Ditambahkan!');
            return redirect()->route('infaq_perbulan.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data infaq perbulan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg', 'Gagal menambahkan data infaq perbulan']);
        }
    }

    public function edit(Infaq_Perbulan $infaq_perbulan)
    {
        return view('dashboard.infaq_perbulan.edit', compact('infaq_perbulan'));
    }

    public function update(Request $request, Infaq_Perbulan $infaq_perbulan)
    {
        $request->validate([
            'id_user' => 'required',
            'nist' => 'required',
            'id_bank' => 'required',
            'nama_bank' => 'required|min:3|max:15',
            'pemilik_rekening' => 'required|min:3|max:50',
            'nominal' => 'required',
            'img_bukti' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'status' => 'required',
        ], [
            'id_user.required' => 'User ID tidak boleh kosong',
            'nist.required' => 'Siswa ID tidak boleh kosong',
            'id_bank.required' => 'Bank ID tidak boleh kosong',
            'nama_bank.required' => 'Nama Bank tidak boleh kosong',
            'nama_bank.min' => 'Nama Bank minimal 3 karakter',
            'nama_bank.max' => 'Nama Bank maksimal 15 karakter',
            'pemilik_rekening.required' => 'Pemilik Rekening tidak boleh kosong',
            'pemilik_rekening.min' => 'Pemilik Rekening minimal 3 karakter',
            'pemilik_rekening.max' => 'Pemilik Rekening maksimal 50 karakter',
            'nominal.required' => 'Nominal tidak boleh kosong',
            'img_bukti.required' => 'Bukti Pembayaran tidak boleh kosong',
            'img_bukti.image' => 'Bukti Pembayaran harus berupa gambar',
            'img_bukti.mimes' => 'Bukti Pembayaran harus berformat jpg, png, jpeg',
            'img_bukti.max' => 'Bukti Pembayaran maksimal 2MB',
            'status.required' => 'Status tidak boleh kosong',
        ]);

        try {
            $infaq_perbulan->update([
                'id_user' => $request->id_user,
                'nist' => $request->nist,
                'id_bank' => $request->id_bank,
                'nama_bank' => $request->nama_bank,
                'pemilik_rekening' => $request->pemilik_rekening,
                'nominal' => $request->nominal,
                'img_bukti' => $request->img_bukti,
                'status' => $request->status,
            ]);

            Alert::success('Success', 'Data Infaq Perbulan Berhasil Diubah!');
            return redirect()->route('infaq_perbulan.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal mengubah data infaq perbulan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg', 'Gagal mengubah data infaq perbulan']);
        }
    }

    public function destroy(Infaq_Perbulan $infaq_perbulan)
    {
        try {
            $infaq_perbulan->delete();
            Alert::success('Success', 'Data Infaq Perbulan Berhasil Dihapus!');
            return redirect()->route('infaq_perbulan.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus data infaq perbulan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg', 'Gagal menghapus data infaq perbulan']);
        }
    }
}
