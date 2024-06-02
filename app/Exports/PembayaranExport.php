<?php

namespace App\Exports;
use App\Models\Bank;
use App\Models\Program;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PembayaranExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $time_download = date('Y-m-d H:i:s');
        $banks = Bank::all();
        $programs = Program::all();
        $students = Payment::with('siswa')->paginate(5);
        return view('dashboard.pembayaran.export.export_pembayaran_verifikasi', compact('time_download', 'banks', 'programs', 'students'));
    }
}