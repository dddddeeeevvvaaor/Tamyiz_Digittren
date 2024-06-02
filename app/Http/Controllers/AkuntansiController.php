<?php

namespace App\Http\Controllers;

use App\Models\Infaq_Perbulan;
use App\Models\Siswa;
use App\Models\Payment;
use App\Models\Akuntansi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AkuntansiExport;
use App\Imports\AkuntansiImport;
use GuzzleHttp\Psr7\Response;
use RealRashid\SweetAlert\Facades\Alert;

class AkuntansiController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::all();
        $payment = Payment::all();
        $tgl_awal = Akuntansi::min('tanggal');
        $tgl_akhir = Akuntansi::max('tanggal');
        $data_akuntansi = Akuntansi::all();

        $infaq = Infaq_Perbulan::all();

        $perPageInfaq = $request->input('perPageInfaq', 10); // Default adalah 10 jika tidak ada input
        if ($perPageInfaq == 'all') {
            $infaq = Infaq_Perbulan::all();
        } else {
            $infaq = Infaq_Perbulan::paginate($perPageInfaq);
        }

        $perPage = $request->input('perPage', 10); // Default adalah 10 jika tidak ada input
        if ($perPage == 'all') {
            $akuntansi = Akuntansi::paginate(Akuntansi::count()); // Mengubah ini
        } else {
            $akuntansi = Akuntansi::paginate($perPage);
        }
        return view('dashboard.laporanakuntansi.akuntansi', compact('siswa', 'payment', 'akuntansi', 'tgl_awal', 'tgl_akhir', 'data_akuntansi', 'perPage', 'infaq', 'perPageInfaq'));
    }

    public function filter(Request $request)
    {
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');

        $akuntansi = Akuntansi::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get();

        return response()->json($akuntansi);
    }

    public function filter_infaq(Request $request)
    {
        $tahuninfaq = $request->input('tahuninfaq');
        $bulaninfaq = $request->input('bulaninfaq');

        $infaq = Infaq_Perbulan::whereYear('updated_at', $tahuninfaq)
            ->whereMonth('updated_at', $bulaninfaq)
            ->get();

        return response()->json($infaq);
    }

    public function export()
    {
        $filename = 'Data Akuntansi ' . date('Y-m-d_H-i-s') . '.xls';
        return Excel::download(new AkuntansiExport, $filename);
    }

    public function format_import()
    {
        $file = public_path() . '/format_import/format_import_akuntansi.xls';
        $headers = array(
            'Content-Type: application/xls',
        );
        return Response()->download($file, 'format_import_akuntansi ' . date('Y-m-d_H-i-s') . '.xls', $headers);
    }

    public function import(Request $request)
    {
        $file = $request->file('file_import');
        Excel::import(new AkuntansiImport, $file);

        Alert::success('Import Berhasil', 'Data berhasil diimport ke dalam database.');
        return back();
    }
}
