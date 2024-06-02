<?php

namespace App\Imports;

use App\Models\Akuntansi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AkuntansiImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        // dd($collection);
        foreach ($collection as $key => $row) {
            if ($key >= 8 && $key <= 30) {
                if (empty($row[0])) {
                    continue; // Skip the row if id_payment is empty
                }

                $tanggal = ($row[3] - 25569) * 86400;
                Akuntansi::create([
                    'id_payment' => $row[0],
                    'id_payment' => $row[1],
                    'id_infaq' => $row[2],
                    'tanggal' => gmdate('Y-m-d', $tanggal),
                    'keterangan_program' => $row[4],
                    'keterangan_infaq' => $row[5],
                    'debet' => $row[6],
                    'saldo' => $row[7],
                ]);
            }
        }
    }
}
