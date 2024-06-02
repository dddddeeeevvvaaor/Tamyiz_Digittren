<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use RealRashid\SweetAlert\Facades\Alert;

class ProgramController extends Controller
{
    public function index()
    {
        $program = program::all();
        return view('dashboard.program.index', compact('program'));
    }

    public function create()
    {
        return view('dashboard.program.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:5|max:10',
            //validasi harus mata uang ruupiah contohnya Rp 100.000 dan nominal tidak boleh negatif
            'nominal' => 'required|regex:/^Rp\s\d{1,3}(\.\d{3})*?$/',
        ], [
            'nama.required' => 'Nama tidak boleh kosong',
            'nama.min' => 'Nama minimal 5 karakter',
            'nama.max' => 'Nama maksimal 10 karakter',
            'nominal.required' => 'Nominal tidak boleh kosong',
            'nominal.regex' => 'Nominal harus diawali dengan Rp dan nominal tidak boleh negatif',
        ]);

        // Menghilangkan 'Rp ' dan titik dari nilai nominal sebelum menyimpan
        $nominal = str_replace(['Rp ', '.'], '', $request->nominal);

        try {
            program::create([
                'nama' => $request->nama,
                'nominal' => $nominal,
            ]);
            Alert::success('Success', 'Data Program Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data program. Silakan coba lagi.');
        }

        return redirect()->route('program.index');
    }



    public function edit(program $program)
    {
        return view('dashboard.program.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $request->validate([
            'nama' => 'required|min:5|max:10',
            //validasi harus mata uang ruupiah contohnya Rp 100.000 dan nominal tidak boleh negatif
            'nominal' => 'required|regex:/^Rp\s\d{1,3}(\.\d{3})*?$/',
        ], [
            'nama.required' => 'Nama tidak boleh kosong',
            'nama.min' => 'Nama minimal 5 karakter',
            'nama.max' => 'Nama maksimal 10 karakter',
            'nominal.required' => 'Nominal tidak boleh kosong',
            'nominal.regex' => 'Nominal harus diawali dengan Rp dan nominal tidak boleh negatif',
        ]);

        // Menghilangkan 'Rp ' dan titik dari nilai nominal sebelum menyimpan
        $nominal = str_replace(['Rp ', '.'], '', $request->nominal);

        program::where('id_program', $program->id_program)
            ->update([
                'nama' => $request->nama,
                'nominal' => $nominal,
            ]);
        Alert::success('Success', 'Data Program Berhasil Diubah!');
        return redirect()->route('program.index');
    }

    public function destroy(program $program)
    {
        try {
            program::destroy($program->id_program);
            Alert::success('Success', 'Data Program Berhasil Dihapus!');
            return redirect('/dashboard/program/index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus data Program!');
            return redirect('/dashboard/program/index');
        }
    }
}
