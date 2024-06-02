<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AkunBaruUser;
use App\Exports\AkunBaruUserExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AkunBaruUserImport;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Golongan;
use App\Models\Payment;

class AkunBaruUserController extends Controller

{
    public function index(Request $request)
    {
        $statusFilter = $request->input('status');
        $golonganFilter = $request->input('golongan');
        $perPage = $request->input('perPage', 10);

        $query = AkunBaruUser::query();

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($golonganFilter) {
            $query->where('id_golongan', $golonganFilter);
        }

        if ($perPage == 'all') {
            $akun_baru_user = AkunBaruUser::paginate(AkunBaruUser::count()); // Mengubah ini
        } else {
            $akun_baru_user = AkunBaruUser::paginate($perPage);
        }

        $golongan = Golongan::all();
        $payments = Payment::all();

        return view('dashboard.akun_baru_user.index', compact('akun_baru_user', 'perPage', 'statusFilter', 'golongan', 'payments', 'golonganFilter'));
    }


    public function create()
    {
        return view('dashboard.akun_baru_user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:akun_baru_user',
            'phone' => 'required|unique:akun_baru_user',
            'password' => 'required',
            'role' => 'required',
            'status' => 'required',
        ]);

        AkunBaruUser::create($request->all());

        return redirect()->route('akun_baru_user.index')
            ->with('success', 'AkunBaruUser created successfully.');
    }

    public function show(AkunBaruUser $akun_baru_user)
    {
        return view('dashboard.akun_baru_user.show', compact('akun_baru_user'));
    }

    public function editpassword(AkunBaruUser $akun_baru_user)
    {
        return view('dashboard.akun_baru_user.editpassword', compact('akun_baru_user'));
    }

    public function updatepassword(Request $request, AkunBaruUser $akun_baru_user)
    {
        $request->validate([
            'password' => 'required',
        ]);

        //edit password
        $akun_baru_user->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('akun_baru_user.index')
            ->with('success', 'AkunBaruUser updated successfully');
    }


    public function update(Request $request, AkunBaruUser $akun_baru_user)
    {
        $request->validate([
            'username' => 'required|unique:akun_baru_user',
            'phone' => 'required|unique:akun_baru_user',
            'password' => 'required',
            'role' => 'required',
            'status' => 'required',
        ]);

        $akun_baru_user->update($request->all());

        return redirect()->route('akun_baru_user.index')
            ->with('success', 'AkunBaruUser updated successfully');
    }

    public function destroy(AkunBaruUser $akun_baru_user)
    {
        $akun_baru_user->delete();

        return redirect()->route('akun_baru_user.index')
            ->with('success', 'AkunBaruUser deleted successfully');
    }

    public function updateStatus(Request $request, $id)
    {
        $akun = AkunBaruUser::findOrFail($id);
        $akun->status = $request->status;
        $akun->save();

        return response()->json([
            'success' => true,
            'new_status' => $akun->status,
        ]);
    }


    public function getStatus($id)
    {
        $user = AkunBaruUser::find($id);
        if ($user) {
            return response()->json(['status' => $user->status]);
        } else {
            return response()->json(['status' => 'not found']);
        }
    }

    public function reopenAndDelete(Request $request, $id)
    {
        $user = AkunBaruUser::findOrFail($id);

        // Periksa jika pengguna adalah non-aktif
        if ($user->status === 'inactive') {
            // Temukan dan perbarui catatan pembayaran yang terkait
            $payment = $user->payment;
            if ($payment) {
                $payment->update(['status' => 3]); // Ubah status pembayaran menjadi 3 (dibuka kembali)
            }

            $user->delete(); // Hapus pengguna

            return redirect()->route('akun_baru_user.index')->with('success', 'Pembayaran dibuka kembali dan pengguna dihapus.');
        }

        return redirect()->route('akun_baru_user.index')->with('error', 'Tidak dapat membuka pembayaran atau menghapus pengguna yang aktif.');
    }

    public function export($status = null, $golongan = null)
    {
        // Jika status adalah 'all' atau null, ekspor semua data
        if ($status === 'all' || $status === null) {
            $status = null; // Mengatur status menjadi null akan mengekspor semua data
        }

        //Jika status adalah 'all' atau null, eksport semua data
        if ($golongan === 'all' || $golongan === null) {
            $golongan = null; // Mengatur golongan menjadi null akan mengekspor semua data
        }

        $filename = 'Data Akun Baru User ' . ($status ? ucfirst($status) . ' ' : '') . ($golongan ? 'Golongan ' . $golongan . ' ' : '') . date('Y-m-d_H-i-s') . '.xlsx';
        return Excel::download(new AkunBaruUserExport($status, $golongan), $filename);
    }

    public function format_import()
    {
        $file = public_path() . '/format_import/format_import_akun_baru_user.xlsx';
        $headers = array(
            'Content-Type: application/xlsx',
        );
        return Response()->download($file, 'format_import_akun_baru_user ' . date('Y-m-d_H-i-s') . '.xlsx', $headers);
    }

    public function import(Request $request)
    {
        $file = $request->file('file_import');
        Excel::import(new AkunBaruUserImport, $file);

        Alert::success('Import Berhasil', 'Data berhasil diimport ke dalam database.');
        return back();
    }
}
