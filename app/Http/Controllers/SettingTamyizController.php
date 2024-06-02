<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting_Tamyiz;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;


class SettingTamyizController extends Controller
{
    public function index()
    {
        $settingtamyiz = Setting_Tamyiz::first();
        return view('dashboard.settingtamyiz.index', compact('settingtamyiz'));
    }

    public function create()
    {
        return view('dashboard.settingtamyiz.create');
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'nama_pesantren' => 'min:3|min:3|max:30',
            'kode_pos' => 'numeric',
            'nomor_telpon' => 'numeric|digits_between:10,15',
            'alamat' => 'min:5|max:50',
            'website' => 'url',
            'email' => 'email',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_pesantren.min' => 'Nama Pesantren minimal 3 karakter',
            'nama_pesantren.max' => 'Nama Pesantren maksimal 30 karakter',
            'kode_pos.numeric' => 'Kode Pos harus berupa angka',
            'nomor_telpon.numeric' => 'Nomor Telepon harus berupa angka',
            'nomor_telpon.digits_between' => 'Nomor Telepon minimal 10 karakter dan maksimal 15 karakter',
            'alamat.min' => 'Alamat minimal 5 karakter',
            'alamat.max' => 'Alamat maksimal 50 karakter',
            'website.url' => 'Website harus berupa URL',
            'email.email' => 'Email harus berupa format email yang benar',
            'logo.image' => 'Logo harus berupa file gambar',
            'logo.mimes' => 'Logo harus berupa file gambar dengan format jpeg, png, jpg',
            'logo.max' => 'Ukuran file logo maksimal 2MB'
        ]);

        try {
            // Menangani proses upload file logo jika diperlukan
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
            }

            Setting_Tamyiz::updateOrCreate(
                ['nama_pesantren' => $request->nama_pesantren],
                [
                    'nama_pesantren' => $request->nama_pesantren,
                    'kode_pos' => $request->kode_pos,
                    'nomor_telpon' => $request->nomor_telpon,
                    'alamat' => $request->alamat,
                    'website' => $request->website,
                    'email' => $request->email,
                    'logo' => $logoPath,
                ]
            );

            Alert::success('Success', 'Data Setting Tamyiz berhasil disimpan!');
            return redirect()->route('settingtamyiz.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menyimpan data: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pesantren' => 'required|min:3|max:30',
            'kode_pos' => 'nullable|numeric',
            'nomor_telpon' => 'nullable|numeric|digits_between:10,15',
            'alamat' => 'required|min:5',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'nama_pesantren.min' => 'Nama Pesantren minimal 3 karakter',
            'nama_pesantren.max' => 'Nama Pesantren maksimal 30 karakter',
            'kode_pos.numeric' => 'Kode Pos harus berupa angka',
            'nomor_telpon.numeric' => 'Nomor Telepon harus berupa angka',
            'nomor_telpon.digits_between' => 'Nomor Telepon minimal 10 karakter dan maksimal 15 karakter',
            'alamat.min' => 'Alamat minimal 5 karakter',
            'website.url' => 'Website harus berupa URL',
            'email.email' => 'Email harus berupa format email yang benar',
            'logo.image' => 'Logo harus berupa file gambar',
            'logo.mimes' => 'Logo harus berupa file gambar dengan format jpeg, png, jpg',
            'logo.max' => 'Ukuran file logo maksimal 2MB'
        ]);

        try {
            // Proses pengunggahan logo
            $name_logo = null;
            if ($request->has('logo')) {
                $logo_file = $request->file('logo');
                $name_logo = time() . rand() . '.' . $logo_file->getClientOriginalExtension();
                $logo_file->move('assets/images/logo/', $name_logo);
            }

            Setting_Tamyiz::create([
                'nama_pesantren' => $request->nama_pesantren,
                'kode_pos' => $request->kode_pos,
                'nomor_telpon' => $request->nomor_telpon,
                'alamat' => $request->alamat,
                'website' => $request->website,
                'email' => $request->email,
                'logo' => $name_logo, // Simpan nama file logo
            ]);

            Alert::success('Success', 'Data Setting Tamyiz Berhasil Ditambahkan!');
            return redirect()->route('settingtamyiz.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data Setting Tamyiz.');
            return redirect()->back()->withInput();
        }
    }


    public function edit(Setting_Tamyiz $settingtamyiz)
    {
        return view('dashboard.settingtamyiz.edit', compact('settingtamyiz'));
    }

    public function update(Request $request, Setting_Tamyiz $settingtamyiz)
    {
        $validationRules = [
            'nama_pesantren' => 'required|min:3|max:30',
            'kode_pos' => 'nullable|numeric',
            'nomor_telpon' => 'nullable|numeric|digits_between:10,15',
            'alamat' => 'nullable|min:5',
            'website' => 'nullable|url',
            'email' => 'nullable|email',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $validationMessages = [
            'nama_pesantren.min' => 'Nama Pesantren minimal 3 karakter',
            'nama_pesantren.max' => 'Nama Pesantren maksimal 30 karakter',
            'kode_pos.numeric' => 'Kode Pos harus berupa angka',
            'nomor_telpon.numeric' => 'Nomor Telepon harus berupa angka',
            'nomor_telpon.digits_between' => 'Nomor Telepon minimal 10 karakter dan maksimal 15 karakter',
            'alamat.min' => 'Alamat minimal 5 karakter',
            'website.url' => 'Website harus berupa URL',
            'email.email' => 'Email harus berupa format email yang benar',
            'logo.image' => 'Logo harus berupa file gambar',
            'logo.mimes' => 'Logo harus berupa file gambar dengan format jpeg, png, jpg',
            'logo.max' => 'Ukuran file logo maksimal 2MB'
        ];

        $request->validate($validationRules, $validationMessages);

        try {
            $nameLogo = $settingtamyiz->logo;

            if ($request->hasFile('logo')) {
                $logoFile = $request->file('logo');
                $nameLogo = time() . rand() . '.' . $logoFile->getClientOriginalExtension();
                $logoFile->move('assets/images/logo/', $nameLogo);
            }

            $settingtamyiz->update([
                'nama_pesantren' => $request->nama_pesantren,
                'kode_pos' => $request->kode_pos,
                'nomor_telpon' => $request->nomor_telpon,
                'alamat' => $request->alamat,
                'website' => $request->website,
                'email' => $request->email,
                'logo' => $nameLogo,
            ]);

            Alert::success('Success', 'Data Setting Tamyiz Berhasil Diubah!');
            return redirect()->route('settingtamyiz.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal mengubah data Setting Tamyiz: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    public function destroy(Setting_Tamyiz $settingtamyiz)
    {
        $settingtamyiz->delete();

        Alert::success('Success', 'Data Setting Tamyiz Berhasil Dihapus!');
        return redirect()->route('settingtamyiz.index');
    }
}
