<?php

namespace App\Exports;

use App\Models\AkunBaruUser;
use App\Models\Golongan;
use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AkunBaruUserExport implements FromView, ShouldAutoSize
{
    protected $status;
    protected $golongan;

    public function __construct($status = null, $golongan = null)
    {
        $this->status = $status;
        $this->golongan = $golongan;
    }

    public function view(): View
    {
        $time_download = date('Y-m-d H:i:s');
        $query = AkunBaruUser::query();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->golongan) {
            $query->where('id_golongan', $this->golongan);
        }

        $data_akunbaruuser = $query->get();
        $golongan = Golongan::all();
        $payments = Payment::all();

        return view('dashboard.akun_baru_user.export', compact('time_download', 'data_akunbaruuser', 'golongan', 'payments'));
    }
}
