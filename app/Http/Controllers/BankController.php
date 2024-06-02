<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use App\Models\Bank;

class BankController extends Controller
{
    public function index()
    {
        $bank = bank::all();
        return view('dashboard.bank.index', compact('bank'));
    }

    public function create()
    {
        return view('dashboard.bank.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:3|max:15',
            'no_rekening' => 'required|numeric',
            'atas_nama' => 'required|min:5|max:50',
            'phone' => 'required|numeric|digits_between:10,15',
        ], [
            'nama.required' => 'Nama tidak boleh kosong',
            'nama.min' => 'Nama minimal 5 karakter',
            'nama.max' => 'Nama maksimal 15 karakter',
            'no_rekening.required' => 'Nomor rekening tidak boleh kosong',
            'no_rekening.numeric' => 'Nomor rekening harus berupa angka',
            'atas_nama.required' => 'Atas nama tidak boleh kosong',
            'atas_nama.min' => 'Atas nama minimal 5 karakter',
            'atas_nama.max' => 'Atas nama maksimal 50 karakter',
            'phone.required' => 'Nomor telepon tidak boleh kosong',
            'phone.numeric' => 'Nomor telepon harus berupa angka',
            'phone.digits_between' => 'Nomor telepon minimal 10 karakter dan maksimal 15 karakter',
        ]);

        try {
            bank::create([
                'nama' => $request->nama,
                'no_rekening' => $request->no_rekening,
                'atas_nama' => $request->atas_nama,
                'phone' => $request->phone,
            ]);

            Alert::success('Success', 'Data Bank Berhasil Ditambahkan!');
            return redirect()->route('bank.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data bank: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg', 'Gagal menambahkan data bank']);
        }
    }

    public function edit(bank $bank)
    {
        return view('dashboard.bank.edit', compact('bank'));
    }

    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'nama' => 'required|min:3|max:15',
            'no_rekening' => 'required|numeric',
            'atas_nama' => 'required|min:5|max:50',
            'phone' => 'required|numeric|digits_between:10,15',
        ], [
            'nama.required' => 'Nama tidak boleh kosong',
            'nama.min' => 'Nama minimal 5 karakter',
            'nama.max' => 'Nama maksimal 15 karakter',
            'no_rekening.required' => 'Nomor rekening tidak boleh kosong',
            'no_rekening.numeric' => 'Nomor rekening harus berupa angka',
            'atas_nama.required' => 'Atas nama tidak boleh kosong',
            'atas_nama.min' => 'Atas nama minimal 5 karakter',
            'atas_nama.max' => 'Atas nama maksimal 50 karakter',
            'phone.required' => 'Nomor telepon tidak boleh kosong',
            'phone.numeric' => 'Nomor telepon harus berupa angka',
            'phone.digits_between' => 'Nomor telepon minimal 10 karakter dan maksimal 15 karakter',
        ]);

        $updated = Bank::where('id_bank', $bank->id_bank)
            ->update([
                'nama' => $request->nama,
                'no_rekening' => $request->no_rekening,
                'atas_nama' => $request->atas_nama,
                'phone' => $request->phone,
            ]);

        if ($updated) {
            return redirect()->route('bank.index')->with('status', 'Data Bank Berhasil Diubah!');
        } else {
            return redirect()->route('bank.index')->with('error', 'Gagal mengubah data bank.');
        }
    }


    public function destroy(Bank $bank)
    {
        bank::destroy($bank->id_bank);
        return redirect()->route('bank.index')->with('status', 'Data Bank Berhasil Dihapus!');
    }
}
