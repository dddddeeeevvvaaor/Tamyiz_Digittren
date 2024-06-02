<?php

namespace App\Imports;

use App\Models\AkunBaruUser;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AkunBaruUserImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        // dd($collection);
        foreach ($collection as $key => $row) {
            if ($key >= 8 && $key <= 30) { //berf
                if (empty($row[0])) {
                    continue; // Skip the row if id_payment is empty
                }

                $mulai = ($row[10] - 25569) * 86400;
                AkunBaruUser::create([
                    'id_payment' => $row[0],
                    'id_golongan' => $row[1],
                    'firstname' => $row[2],
                    'lastname' => $row[3],
                    'username' => $row[4],
                    'email' => $row[5],
                    'password' => $row[6],
                    'city' => $row[7],
                    'role' => $row[8],
                    'status' => $row[9],
                    'mulai' => gmdate('Y-m-d', $mulai),
                    'berakhir' => $row[11],
                ]);
            }
        }
    }
}
