<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nama' => 'admin1',
            'password' => bcrypt('admin1'),
            'role' => 'admin',
            'phone' => '085799811481'
        ]);


        //membuat admin 2
        User::create([
            'nama' => 'admin2', //tambahkan nama 'siswa2
            'password' => bcrypt('admin2'),
            'role' => 'admin',
            'phone' => '085799811482'
        ]);


        //membuat admin 3
        User::create([
            'nama' => 'admin3', //tambahkan nama 'siswa3
            'password' => bcrypt('admin3'),
            'role' => 'admin',
            'phone' => '085799811483'
        ]); 

        //membuat admin 4
        User::create([
            'nama' => 'admin4', //tambahkan nama 'siswa4
            'password' => bcrypt('admin4'),
            'role' => 'admin',
            'phone' => '085799811484'
        ]);

        //membuat admin 5
        User::create([
            'nama' => 'admin5', //tambahkan nama 'siswa5
            'password' => bcrypt('admin5'),
            'role' => 'admin',
            'phone' => '085799811485'
        ]);

        //membuat admin 6
        User::create([
            'nama' => 'admin6', //tambahkan nama 'siswa6
            'password' => bcrypt('admin6'),
            'role' => 'admin',
            'phone' => '085799811486'
        ]);

        //membuat admin 7
        User::create([
            'nama' => 'admin7', //tambahkan nama 'siswa7
            'password' => bcrypt('admin7'),
            'role' => 'admin',
            'phone' => '085799811487'
        ]);

        //membuat admin 8
        User::create([
            'nama' => 'admin8', //tambahkan nama 'siswa8
            'password' => bcrypt('admin8'),
            'role' => 'admin',
            'phone' => '085799811488'
        ]);

        //membuat admin 9
        User::create([
            'nama' => 'admin9', //tambahkan nama 'siswa9
            'password' => bcrypt('admin9'),
            'role' => 'admin',
            'phone' => '085799811489'
        ]);

        //membuat admin 10
        User::create([
            'nama' => 'admin10', //tambahkan nama 'siswa10
            'password' => bcrypt('admin10'),
            'role' => 'admin',
            'phone' => '085799811480'
        ]);
    }
}
