<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use App\Models\WhatsAppAPI;
use App\Models\Tanggal_buka_pendaftaran;
use App\Models\Payment;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Program;
use App\Models\Admin;
use App\Models\AkunBaruUser;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Pengumuman;
use App\Models\Akuntansi;
use App\Models\Login;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CalonSiswaExport;
use App\Exports\PembayaranExport;
use App\Models\Setting_Tamyiz;
use GuzzleHttp\Client;
use App\Models\Periode_pendaftaran;
use App\Models\Infaq_Perbulan;
use App\Models\Golongan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class PpdbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function beranda()
    {
        $settingtamyiz = Setting_Tamyiz::all();
        return view('beranda', compact('settingtamyiz'));
    }

    public function error()
    {
        $settingtamyiz = Setting_Tamyiz::all();
        return view('error', compact('settingtamyiz'));
    }

    public function login()
    {
        $settingtamyiz = Setting_Tamyiz::all();
        return view('login', compact('settingtamyiz'));
    }

    public function pendaftaran_tutup()
    {
        $settingtamyiz = Setting_Tamyiz::all();
        return view('daftar', compact('settingtamyiz'));
    }

    public function store(Request $request)
    {
        // Cek apakah ada data di tabel WhatsAppAPI
        $whatsappApi = WhatsAppAPI::first();

        if (!$whatsappApi || empty($whatsappApi->token) || empty($whatsappApi->base_server)) {
            Alert::error('Gagal', 'Maaf, terjadi kendala sistem');
            return redirect('/error');
        }

        // $randomPassword = Str::random(8);

        $phone = $request->phone;

        $request->validate([
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'nama' => 'required|min:2|max:50', // Menambahkan validasi maksimal karakter
            'phone' => 'required|digits_between:10,15|unique:users,phone|numeric', // Menambahkan validasi unique

            'nist' => 'min:5|max:20|unique:santri,nist',
            'city' => 'required|min:3|max:15',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'nama.regex' => 'Nama harus terdiri dari first name dan last name yang dipisahkan oleh spasi.',
            'nama.min' => 'Nama terlalu pendek.',
            'nama.max' => 'Nama terlalu panjang.',
            'phone.unique' => 'No Hp sudah terdaftar',
            'phone.digits_between' => 'No Hp harus berupa angka dan minimal 10 karakter serta maksimal 15 karakter.',
            'phone.required' => 'No Hp harus diisi.',
            'phone.numeric' => 'No Hp harus berupa angka.',
            'city.required' => 'Kota harus diisi.',
            'city.string' => 'Kota harus berupa string.',
            'city.max' => 'Kota maksimal 255 karakter.',

            'nist.min' => 'NIST terlalu pendek.',
            'nist.max' => 'NIST terlalu panjang.',
            'nist.unique' => 'NIST sudah terdaftar.',
            'city.required' => 'Kota harus diisi.',
            'city.max' => 'Kota maksimal 15 karakter.',
            'city.min' => 'Kota minimal 3 karakter.',
        ]);

        // Generate unique register_id
        $lastUser = User::latest()->first(); // Get the latest user
        $lastId = $lastUser ? substr($lastUser->register_id, -5) : '00000'; // Extract last 5 characters of register_id
        $newId = intval($lastId) + 1; // Increment the numeric part of the register_id
        $formattedId = str_pad($newId, 5, '0', STR_PAD_LEFT); // Format the new ID to have leading zeros if needed
        $registerId = 'TamyizREG' . $formattedId; // Combine with the prefix

        $tanggal_lahir = $request->input('tahun_lahir') . '-' . $request->input('bulan_lahir') . '-' . $request->input('tanggal_lahir');

        // Pastikan tabel 'programs' ada dalam database dan nama tabelnya benar
        $program = Program::find($request->id_program);
        if (!$program) {
            Alert::error('Gagal', 'Program tidak ditemukan.');
            return back()->withErrors(['error' => 'Program tidak ditemukan.']);
        }


        $lastThreeDigitsphone = substr($phone, -3);

        // Daftar karakter unik yang ingin Anda gunakan
        $specialChars = "!@#$%^&*()_-=+;:,.?";

        // Generate 3 karakter unik secara acak dari daftar karakter di atas
        $randomSpecialChars = substr(str_shuffle($specialChars), 0, 3);

        // Gabungkan karakter unik ke dalam password
        $lastThreeDigitsphone = substr($phone, -3);

        // Daftar huruf yang ingin Anda gunakan
        $specialChars = "abcdefghijklmnopqrstuvwxyz";

        // Generate 3 karakter unik secara acak dari daftar karakter di atas
        $randomSpecialChars = substr(str_shuffle($specialChars), 0, 3);

        $plainPassword = "Tamyiz" . "-" . $lastThreeDigitsphone . $randomSpecialChars;

        // Gabungkan karakter unik ke dalam password
        $password = Hash::make($plainPassword);

        // Mengambil input tanggal lahir dari request dan memvalidasinya
        $tanggal_lahirInput = $request->input('tanggal_lahir');
        $bulan_lahirInput = $request->input('bulan_lahir');
        $tahun_lahirInput = $request->input('tahun_lahir');

        // Membuat tanggal lahir dalam format yang benar (YYYY-MM-DD)
        $tanggal_lahirnist = $tahun_lahirInput . $bulan_lahirInput . $tanggal_lahirInput;

        // Memeriksa apakah tanggal yang dibuat adalah tanggal yang valid
        if (!strtotime($tanggal_lahir)) {
            Alert::error('Gagal', 'Tanggal lahir tidak valid.');
            return back()->withErrors(['error' => 'Tanggal lahir tidak valid.']);
        }

        // Mengambil tahun sekarang
        $tahunSekarang = date('Y');

        try {
            // Proses penyimpanan data...
            $user = User::create([
                'nama' => $request->nama,
                'password' => $password,
                'role' => 'student',
                'phone' => $request->phone,
            ]);

            // Mengambil input tanggal lahir dari request dan memvalidasinya
            $tanggal_lahirInput = str_pad($request->input('tanggal_lahir'), 2, '0', STR_PAD_LEFT);
            $bulan_lahirInput = str_pad($request->input('bulan_lahir'), 2, '0', STR_PAD_LEFT);
            $tahun_lahirInput = $request->input('tahun_lahir');

            // Membuat tanggal lahir dalam format yang benar (YYYY-MM-DD)
            $tanggal_lahir = $tahun_lahirInput . '-' . $bulan_lahirInput . '-' . $tanggal_lahirInput;

            // Menggabungkan tahun sekarang dengan tanggal lahir untuk NIST
            $nistPrefix = $tahunSekarang . $tahun_lahirInput . $bulan_lahirInput . $tanggal_lahirInput;

            // Mendapatkan ID pengguna yang baru saja didaftarkan dengan padding nol
            // Pastikan panjang total NIST sesuai kebutuhan (misal 20 karakter)
            $lastUserId = User::orderBy('id_user', 'desc')->first()->id_user ?? 0;
            $nistId = $lastUserId;
            $formattedNistId = str_pad($nistId, 7, '0', STR_PAD_LEFT); // Menggunakan 7 digit untuk ID

            // Menggabungkan semua komponen untuk membuat NIST
            $fullNIST = $nistPrefix . $formattedNistId;

            Siswa::create([
                'id_user' => $user->id_user,
                'tanggal_lahir' => $tanggal_lahir,
                'nist' => $fullNIST,
                'jenis_kelamin' => $request->jenis_kelamin,
                'id_program' => $program->id_program,
                'city' => $request->city,
            ]);

            $plainPassword = "Tamyiz" . "-" . $lastThreeDigitsphone . $randomSpecialChars;

            // Panggil fungsi registerWA hanya untuk pengguna yang baru dibuat
            $this->registerWA($user->phone, $registerId, $request->nama_santri, $plainPassword, $user->created_at, $user->id_user, $request->id_program, date('d-m-Y H:i:s'));

            $users = User::where('role', 'admin')->get();

            foreach ($users as $adminUser) {
                $this->notifikasi_khususadmin($adminUser->phone);
            }

            // Melakukan login otomatis setelah pengguna berhasil didaftarkan
            Auth::login($user);

            DB::commit(); // Commit transaksi jika semua operasi berhasil

            // Pemberitahuan berhasil
            Alert::success('Berhasil', 'Pendaftaran Berhasil Dilakukan');

            // Pengalihan ke halaman pembayaran
            return redirect('/dashboard/pembayaran/pembayaran');
        } catch (\Exception $e) {
            DB::rollback(); // Batalkan transaksi jika terjadi kesalahan
            // Jika terjadi kesalahan, kirimkan pesan gagal
            Alert::error('Gagal', 'Maaf, terjadi kendala saat proses pendaftaran.');
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function auth(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:users,phone',
            'password' => 'required',
        ], [
            'phone.exist' => 'Nomor telepon ini belum terdaftar',
            'phone.required' => 'Nomor telepon harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        $credentials = $request->only('phone', 'password');
        $user = User::where('phone', $request->input('phone'))->first();

        if ($user->device_id !== $request->device_id) {
            // Invalidate previous session or token
            $user->device_id = $request->device_id;
            $user->save();
            Auth::logout(); // Logout from the previous device
        }

        if (Auth::attempt($credentials)) {
            $user = User::where('phone', $request->input('phone'))->first();
            $siswa = Siswa::where('id_user', $user->id_user)->first();

            // Periksa jika pengguna memiliki peran admin
            if ($user->role === 'admin') {
                // Tindakan khusus untuk admin, seperti redirect ke halaman admin
                return redirect('/dashboard');
            }

            // Update atau Create record di tabel login
            Login::updateOrCreate(
                ['nama' => $siswa->user->nama],
                ['nist' => $siswa->nist, 'is_online' => true, 'last_activity' => now()]
            );

            session(['lastActivityTime' => now()]);

            // Redirect ke dashboard
            return redirect('/dashboard');
        } else {
            return redirect()->back()->with('error', 'Gagal login, silahkan cek dan coba lagi!');
        }
    }

    public function logout()
    {
        // Dapatkan nama pengguna yang sedang login
        $user = Auth::user();

        // Update status login menjadi offline
        Login::where('nama', $user->nama)->update(['is_online' => false]);

        Auth::logout();
        return redirect('/');
    }



    // Controller
    // public function auth(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|exists:akun_baru_user,email',
    //         'password' => 'required',
    //     ], [
    //         'email.exists' => 'Email belum terdaftar',
    //         'email.required' => 'Email harus diisi',
    //         'password.required' => 'Password harus diisi',
    //     ]);

    //     $credentials = $request->only('email', 'password');

    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user(); // Mendapatkan objek user saat ini

    //         if ($user->status === 'active') {
    //             return redirect('/dashboard');
    //         } else {
    //             Auth::logout(); // Logout user jika statusnya tidak aktif
    //             return redirect()->back()->with('error', 'Maaf, akun Anda tidak aktif. Silakan hubungi admin untuk bantuan lebih lanjut.');
    //         }
    //     } else {
    //         return redirect()->back()->with('error', 'Gagal login, silahkan cek dan coba lagi!');
    //     }
    // }

    public function daftar()
    {
        $tanggal = Tanggal_buka_pendaftaran::first();
        $programs = Program::all();
        $settingtamyiz = Setting_Tamyiz::all();

        if ($tanggal && date('Y-m-d') >= $tanggal->tanggal_buka && date('Y-m-d') <= $tanggal->tanggal_tutup) {
            return view('daftar', compact('programs', 'settingtamyiz'));
        } else {
            return view('pendaftaran-ditutup', compact('settingtamyiz'));
        }
    }

    // Controller
    public function dashboard()
    {
        $userId = Auth::user()->id_user;
        $siswa = Siswa::where('id_user', Auth::user()->id_user)->get();
        $payment = Payment::whereHas('siswa', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        })->get();
        $user = User::where('id_user', Auth::user()->id_user)->get();
        $data_jumlah_siswa_atau_pendaftar = Siswa::all()->count();
        $data_jumlah_siswa_diterima = Payment::where('status', 1)->count();
        $data_jumlah_siswa_ditolak = Payment::where('status', 2)->count();
        $data_akun_active = AkunBaruUser::where('status', 'active')->count();
        $data_akun_unactive = AkunBaruUser::where('status', 'inactive')->count();
        $data_pengumuman = Pengumuman::OrderByRaw('created_at DESC')->limit(5)->get();
        $data_debet = Akuntansi::all()->sum('debet');
        $data_jumlah_saldo = Akuntansi::all()->sum('debet');

        $team_teknis = User::all();


        // Data untuk ditampilkan ke dashboard
        $data_login = Login::orderByRaw('created_at DESC')->limit(10)->get();
        return view('dashboard.welcome', compact('siswa', 'data_pengumuman', 'data_jumlah_siswa_atau_pendaftar', 'data_jumlah_siswa_diterima', 'data_jumlah_siswa_ditolak', 'data_akun_active', 'data_akun_unactive', 'data_debet', 'data_jumlah_saldo', 'data_login', 'user', 'payment', 'team_teknis', 'userId'));
    }

    public function pembayaran(Request $request)
    {
        $userId = Auth::user()->id_user;
        // Menggunakan method latest('created_at') untuk mengambil pembayaran terakhir
        $item = Payment::whereHas('siswa', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        })->latest('created_at')->first(); // Mengambil entry terakhir berdasarkan tanggal

        $banks = Bank::all();
        $programs = Program::all();
        // Tidak perlu mengubah query untuk students, user, dan periode_pendaftaran
        $latestPayments = Payment::select('nist', DB::raw('MAX(created_at) as latest_payment'))
            ->groupBy('nist')
            ->pluck('latest_payment', 'nist');

        $students = Payment::with('siswa')
            ->whereIn('created_at', $latestPayments)
            ->paginate(5);
        $user = User::all();
        $periode_pendaftaran = Periode_pendaftaran::all();
        $infaq_perbulan = Infaq_Perbulan::with('siswa')->get();

        $iteminfaq = Infaq_Perbulan::where('nist', Auth::user()->id_user)->first();

        $perPageInfaq = $request->input('perPageInfaq', 100); // Default adalah 100 jika tidak ada input
        if ($perPageInfaq == 'all') {
            $infaq_perbulan = Infaq_Perbulan::paginate(Infaq_Perbulan::count());
        } else {
            $infaq_perbulan = Infaq_Perbulan::paginate($perPageInfaq);
        }

        $perPage = $request->input('perPage', 100); // Default adalah 100 jika tidak ada input
        if ($perPage == 'all') {
            $students = Payment::paginate(Payment::count());
        } else {
            $students = Payment::paginate($perPage);
        }

        return view('dashboard.pembayaran.pembayaran', compact('students', 'item', 'banks', 'programs', 'user', 'perPage', 'periode_pendaftaran', 'infaq_perbulan', 'iteminfaq', 'perPageInfaq', 'userId'));
    }


    public function pembayaran_infaq(Request $request)
    {
        $userId = Auth::user()->id_user;
        $item = Payment::whereHas('siswa', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        })->first();
        $banks = Bank::all();
        $programs = Program::all();
        $students = Payment::with('siswa')->paginate(5);
        $user = User::all();
        $periode_pendaftaran = Periode_pendaftaran::all();
        $infaq_perbulan = Infaq_Perbulan::with('siswa')->get();

        $iteminfaq = Infaq_Perbulan::whereHas('siswa', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        })->first();

        $perPageInfaq = $request->input('perPageInfaq', 10); // Default adalah 10 jika tidak ada input
        if ($perPageInfaq == 'all') {
            $infaq_perbulan = Infaq_Perbulan::with('siswa')->get();
        } else {
            $infaq_perbulan = Infaq_Perbulan::with('siswa')->paginate($perPageInfaq);
        }

        $perPage = $request->input('perPage', 10); // Default adalah 10 jika tidak ada input
        if ($perPage == 'all') {
            $students = Payment::with('siswa')->get();
        } else {
            $students = Payment::with('siswa')->paginate($perPage);
        }

        return view('dashboard.pembayaran.pembayaran_infaq', compact('students', 'item', 'banks', 'programs', 'user', 'perPage', 'periode_pendaftaran', 'infaq_perbulan', 'iteminfaq', 'perPageInfaq'));
    }

    public function kirimWAinfaq($phone, $status = 1)
    {

        $tanggalpendaftaran = Siswa::all();

        $message = "Assalamualaikum..\n\n";
        $message .= "Terima kasih telah melakukan pembayaran infaq, pembayaran anda telah kami terima.\n\n";
        $message .= "Berikut Bukti Pembayaran anda:\n\n";
        $message .= "Tanggal: " . date('d-m-Y') . "\n";
        $message .= "Jam pembayaran: " . date('H:i:s') . "\n";
        $message .= "Status: " . ($status == 1 ? 'Diterima' : 'Ditolak') . "\n\n";

        $api = WhatsAppAPI::first();

        $curl = curl_init();
        $token = $api->token;
        $data = [
            'phone' => $phone,
            'message' => $message,
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: $token",
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $api->base_server . '/api/send-message');
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function tolakinfaq($id)
    {
        $infaq = Infaq_Perbulan::where('id_infaq', $id)->first();
        try {
            //mengedit berdasarkan id tabel infaq perbulan
            Infaq_Perbulan::where('id_infaq', $infaq->id_infaq)->update([
                'status' => 1,
            ]);

            $this->kirimwaditolakinfaq($infaq->siswa->user->phone, 2);

            Alert::success('Success', 'Pembayaran Berhasil Diverifikasi! Akun baru telah dibuat.');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data infaq perbulan: ' . $e->getMessage());
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function kirimwaditolakinfaq($phone, $status = 2)
    {

        $tanggalpendaftaran = Siswa::all();

        $infaqs = Infaq_Perbulan::all();

        $message = "Assalamualaikum..\n\n";

        $message .= "Terima kasih infaq anda sudah kami terima sejumlah Rp " . number_format($infaqs->first()->nominal, 0, ',', '.') . ".\n\n";
        $message .= "Insya Allah, infaq anda akan kami salurkan untuk kegiatan pembelajaran santri.\n\n";

        $message .= "Terima kasih.\n\n";
        $api = WhatsAppAPI::first();

        $curl = curl_init();
        $token = $api->token;
        $data = [
            'phone' => $phone,
            'message' => $message,
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: $token",
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $api->base_server . '/api/send-message');
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }


    public function validasi($id_user, Request $request)
    {
        // Check available Golongan
        $golongan = Golongan::where('max_santri', '>', function ($query) {
            $query->selectRaw('COUNT(*)')
                ->from('akun_baru_user')
                ->whereColumn('id_golongan', 'golongan.id_golongan');
        })->orderBy('id_golongan')->first(); // Get the first Golongan with available space

        if (!$golongan) {
            Alert::error('Error', 'Tidak ada golongan dengan ruang tersedia.');
            return redirect()->back();
        }

        // Cari pembayaran terbaru berdasarkan 'nist' dan 'created_at'
        $payment = Payment::where('nist', '=', $id_user)
            ->latest('created_at')
            ->first();

        if (!$payment) {
            Alert::error('Error', 'Pembayaran tidak ditemukan.');
            return redirect()->back();
        }
        //mengambil data program
        $program = Program::where('id_program', $payment->siswa->id_program)->first();

        if (!$payment) {
            Alert::error('Error', 'Pembayaran tidak ditemukan.');
            return redirect()->back();
        }

        $program = Program::where('id_program', $payment->siswa->id_program)->first();

        // Update payment status to validated
        $payment->update([
            'status' => 1,
        ]);

        // Calculate start and end dates based on 'jumlah_pembayaran'
        $startDate = now();
        $endDate = $startDate->copy()->addMonths($payment->jumlah_pembayaran)->format('Y-m-d H:i:s');

        $user = User::where('id_user', $id_user)->first();

        // memanggil data user melalui nist
        $phone = $payment->siswa->user->phone;

        $lastThreeDigitsphone = substr($phone, -3);

        // Extract name from Siswa and prepare email prefix
        // Extract name from Siswa and prepare email prefix
        $nameParts = explode(' ', $payment->siswa->user->nama);

        // Menentukan firstname dan lastname
        $firstname = $nameParts[0];
        $lastname = count($nameParts) > 1 ? $nameParts[count($nameParts) - 1] : "Tamyiz";

        $emailPrefix = strtolower(implode('', $nameParts));

        // Gabungkan karakter unik ke dalam password
        $lastThreeDigitsphone = substr($phone, -3);

        // Daftar huruf yang ingin Anda gunakan
        $specialChars = "abcdefghijklmnopqrstuvwxyz";

        // Generate 3 karakter unik secara acak dari daftar karakter di atas
        $randomSpecialChars = substr(str_shuffle($specialChars), 0, 3);

        $plainPassword = "Tamyiz" . "-" . $lastThreeDigitsphone . $randomSpecialChars;

        // Gabungkan karakter unik ke dalam password
        $password = Hash::make($plainPassword);


        $user = User::where('id_user', $id_user)->first();

        // membuat validasi akun baru user dan validasi akuntansi
        $request->validate([
            //     'firstname' => 'required|min:2|max:25',
            //     'lastname' => 'required|min:2|max:25',
            //     'username' => 'required|min:9|max:13|unique:users,phone|numeric',
            //     'email' => 'required',
            //     'password' => 'required_if:password,null|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/',
            //     'city' => 'required|min:3|max:15',

            //     'keterangan_program' => 'required|min:5|max:20',
            //     'keterangan_infaq' => 'required|min:5|max:50',
            //     'debet' => 'required|numeric',
            //     'saldo' => 'required|numeric',
            // ], [
            //     'firstname.required' => 'Nama depan harus diisi.',
            //     'firstname.min' => 'Nama depan minimal 2 karakter.',
            //     'firstname.max' => 'Nama depan maksimal 25 karakter.',
            //     'lastname.required' => 'Nama belakang harus diisi.',
            //     'lastname.min' => 'Nama belakang minimal 2 karakter.',
            //     'lastname.max' => 'Nama belakang maksimal 25 karakter.',
            //     'username.required' => 'Nomor telepon harus diisi.',
            //     'username.min' => 'Nomor telepon minimal 9 karakter.',
            //     'username.max' => 'Nomor telepon maksimal 13 karakter.',
            //     'username.unique' => 'Nomor telepon sudah terdaftar.',
            //     'username.numeric' => 'Nomor telepon harus berupa angka.',
            //     'email.required' => 'Email harus diisi.',
            //     'password.required' => 'Password harus diisi.',
            //     'password.min' => 'Password minimal 8 karakter.',
            //     'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus.',
            //     'city.required' => 'Kota harus diisi.',
            //     'city.min' => 'Kota minimal 3 karakter.',
            //     'city.max' => 'Kota maksimal 15 karakter.',

            //     'keterangan_program.required' => 'Keterangan program harus diisi.',
            //     'keterangan_program.min' => 'Keterangan program minimal 5 karakter.',
            //     'keterangan_program.max' => 'Keterangan program maksimal 20 karakter.',
            //     'keterangan_infaq.required' => 'Keterangan infaq harus diisi.',
            //     'keterangan_infaq.min' => 'Keterangan infaq minimal 5 karakter.',
            //     'keterangan_infaq.max' => 'Keterangan infaq maksimal 50 karakter.',
            //     'debet.required' => 'Debet harus diisi.',
            //     'debet.numeric' => 'Debet harus berupa angka.',
            //     'saldo.required' => 'Saldo harus diisi.',
            //     'saldo.numeric' => 'Saldo harus berupa angka.',
        ]);

        // Check if AkunBaruUser already exists for the user
        $akunbaruuser = AkunBaruUser::where('id_payment', $payment->id_payment)->first();

        if ($akunbaruuser) {
            // If AkunBaruUser already exists, update the existing record
            $akunbaruuser->update([
                'id_golongan' => $golongan->id_golongan,
                'mulai' => $startDate,
                'berakhir' => $endDate,
            ]);

            Alert::info('Info', 'Data AkunBaruUser telah diperbarui.');
        } else {
            // If AkunBaruUser does not exist, create a new record
            $akunbaruuser = AkunBaruUser::updateOrCreate(
                ['email' => $emailPrefix . '@gmail.com'], // Cek berdasarkan email
                [
                    'id_payment' => $payment->id_payment,
                    'id_golongan' => $golongan->id_golongan,
                    'firstname' => $nameParts[0], // Use the first name
                    'lastname' => $lastname, // Use the last name or "Tamyiz" if not available
                    'username' => $payment->siswa->user->phone, // Use the phone number as the username
                    'password' => $password,
                    'city' => $payment->siswa->city,
                    'role' => 'student', // Set the role as a student or based on your requirements
                    'status' => 'active', // Set the status as active or based on your requirements
                    'mulai' => $startDate,
                    'berakhir' => $endDate,
                ]
            );
        }

        // Setelah memvalidasi pembayaran, buat akun baru
        $emailPrefix = strtolower(implode('', $nameParts));
        $generatedPassword = 'Generated_password'; // Buat password yang aman

        // Siapkan data pengguna untuk pembuatan akun baru
        $userData = [
            'username' => $payment->siswa->user->phone, //mengambil username yang ada di AkunBaruUser
            //mengambil password yang ada di AkunBaruUser
            'password' => $plainPassword,
            'firstname' => $nameParts[0],
            'lastname' => $lastname, // Use the last name or "Tamyiz" if not available
            'email' => $emailPrefix . '@gmail.com',
            'city' => $payment->siswa->city,
        ];

        //update password di tabel user agar sama dengan password di userData
        User::where('id_user', $id_user)->update([
            'password' => $password,
        ]);

        // Kirim WA ke admin
        $adminName = User::where('id_user', Auth::user()->id_user)->first()->nama;
        $admin = User::where('role', 'admin')->get();
        foreach ($admin as $admins) {
            $this->kirimWAadmin($admins->phone, $lastname, $firstname, $emailPrefix, $adminName, $userData);
        }

        $this->kirimWA($phone, 1, $plainPassword);

        // Membuat akun baru
        $this->createNewUser($userData);

        // Atur zona waktu menjadi Waktu Indonesia Barat (WIB)
        date_default_timezone_set('Asia/Jakarta');

        $paymentSum = Payment::where('status', 1)->sum('nominal');
        $infaqSum = Infaq_Perbulan::where('status', 1)->sum('nominal');

        // Membuat validasi akuntansi

        Akuntansi::create([
            'id_payment' => $payment->id_payment,
            'tanggal' => Carbon::now()->format('Y-m-d H:i:s'), // Mengambil tanggal dan waktu saat ini
            'keterangan_program' => 'Pembayaran ' . $program->nama,
            'keterangan_infaq' => $payment->infaq ? 'Memberikan infaq sebesar Rp ' . number_format($payment->infaq, 0, ',', '.') : 'Tidak memberikan infaq',
            'debet' => $payment->nominal, // Tetapkan nilai debet atau modifikasi sesuai kebutuhan
            'saldo' => $paymentSum + $infaqSum,
        ]);


        Alert::success('Success', 'Pembayaran Berhasil Diverifikasi! Akun baru telah dibuat.');

        return redirect()->back();
    }

    public function kirimWA($phone, $status = 1, $password_plain)
    {
        $tanggal_buka_pendaftaran = Tanggal_buka_pendaftaran::all();

        // Memanggil phone admin
        $user = User::where('role', 'admin')->first();

        $tanggalpendaftaran = Siswa::all();

        // Set zona waktu ke "Asia/Jakarta"
        date_default_timezone_set("Asia/Jakarta");

        $message = "Assalamualaikum..\n\n";
        $message .= "Terima kasih, pembayaran anda telah kami terima.\n\n";
        $message .= "Berikut Bukti Pembayaran anda:\n\n";
        $message .= "Tanggal        : " . date('d-m-Y') . "\n";
        $message .= "Jam pembayaran : " . date('H:i:s') . "\n";
        $message .= "Status         : " . ($status == 1 ? 'Diterima' : 'Ditolak') . "\n\n";
        $message .= "Silakan download aplikasi 'Moodle' di Playstore atau App Store dengan mengklilk link berikut:" . "\n\n";
        $message .= "Untuk pengguna Android https://play.google.com/store/apps/details?id=com.moodle.moodlemobile." . "\n\n";
        $message .= "Untuk pengguna iOS https://apps.apple.com/id/app/moodle/id633359593?l=id." . "\n\n";
        $message .= "Kemudian ketik link : https://ilmuku.id/tamyiz di aplikasi Moodle" . "\n\n";

        $message .= "Berikut detail akun anda untuk login ke aplikasi Tamyiz:\n\n";
        $message .= "Username       : " . $phone . "\n";
        $message .= "Password       : " . $password_plain . "\n\n";
        // Menggunakan format d-m-Y
        $message .= "Tanggal mulai: " . date('d-m-Y', strtotime($tanggal_buka_pendaftaran->first()->tanggal_program)) . "\n\n";
        $message .= "Apabila ada kendala, silakan hubungi admin di nomor: " . $user->phone . "\n\n";

        $api = WhatsAppAPI::first();

        $curl = curl_init();
        $token = $api->token;
        $data = [
            'phone' => $phone,
            'message' => $message,
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: $token",
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $api->base_server . '/api/send-message');
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    private function createNewUser($userData)
    {
        $admintoken = '9ea1808800e2a4d60166b4ad59f9d4b3';
        $apiUrl = 'https://ilmuku.id/tamyiz/webservice/rest/server.php';

        $requestData = [
            'wstoken' => $admintoken,
            'wsfunction' => 'core_user_create_users',
            'moodlewsrestformat' => 'json',
            'users' => [$userData]
        ];

        $client = new Client();
        try {
            $response = $client->post($apiUrl, ['form_params' => $requestData]);
            $result = json_decode($response->getBody()->getContents(), true);
            // Logika setelah berhasil membuat akun baru
        } catch (\Exception $e) {
            // Logika jika terjadi kesalahan saat mengirim permintaan
            error_log($e->getMessage());
        }
    }

    public function validasiinfaq($id)
    {
        $infaq = Infaq_Perbulan::where('id_infaq', '=', $id)->first();

        //mengambil data program
        $program = Program::where('id_program', $infaq->siswa->id_program)->first();

        if (!$infaq) {
            Alert::error('Error', 'Pembayaran tidak ditemukan.');
            return redirect()->back();
        }

        $program = Program::where('id_program', $infaq->siswa->id_program)->first();


        // Update infaq status to validated
        $infaq->update([
            'status' => 1,
        ]);

        $this->kirimWAinfaq($infaq->siswa->user->phone, 1);

        // Atur zona waktu menjadi Waktu Indonesia Barat (WIB)
        date_default_timezone_set('Asia/Jakarta');

        Akuntansi::create([
            'id_infaq' => $infaq->id_infaq,
            'tanggal' => Carbon::now()->format('Y-m-d H:i:s'), // Mengambil tanggal dan waktu saat ini
            'keterangan_program' => '',
            'keterangan_infaq' => 'Memberikan infaq sebesar Rp ' . number_format($infaq->nominal, 0, ',', '.'),
            'debet' => $infaq->nominal, // Tetapkan nilai debet atau modifikasi sesuai kebutuhan
            'saldo' => Payment::where('status', 1)->sum('nominal') + Infaq_Perbulan::where('status', 1)->sum('nominal'),
        ]);

        Alert::success('Success', 'Pembayaran Berhasil Diverifikasi! Akun baru telah dibuat.');

        return redirect()->back();
    }

    public function kirimWAadmin($phone, $lastnamenya, $firstnamenya, $emailPrefix, $adminName, $userData)
    {
        $tanggal_buka_pendaftaran = Tanggal_buka_pendaftaran::all();

        // Memanggil phone admin
        $user = User::where('role', 'admin')->first();

        $tanggalpendaftaran = Siswa::all();

        // Set zona waktu ke "Asia/Jakarta"
        date_default_timezone_set("Asia/Jakarta");

        $message = "Assalamualaikum..\n\n";
        $message .= "Santri baru telah diverifikasi.\n\n";
        $message .= "berikut detail admin yang melakukan verifikasi:\n\n";
        $message .= "Nama Admin      : " . $adminName . "\n";
        $message .= "Berikut detail akun santri:\n\n";
        // nama gabungan dari nama depan dan nama belakang
        $message .= "Nama           : " . $firstnamenya . " " . $lastnamenya . "\n";
        $message .= "Username       : " . $userData['username'] . "\n";
        $message .= "Email          : " . $emailPrefix . "@gmail.com" . "\n\n";

        $message .= "Terima kasih.\n\n";
        $api = WhatsAppAPI::first();

        $curl = curl_init();
        $token = $api->token;
        $data = [
            'phone' => $phone,
            'message' => $message,
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: $token",
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $api->base_server . '/api/send-message');
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function postInfaq(Request $request)
    {
        try {
            $select = $request->nama_bank;
            if ($select == "bank_lainnya") {
                $ambil = $request->nama_bank_lainnya;
            } else {
                $ambil = $request->nama_bank;
            }

            $validatedData = $request->validate([
                'nama_bank' => 'required|min:3|max:15',
                'pemilik_rekening' => 'required|min:3|max:50',
                'nominal' => 'required',
                'img_bukti' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ], [
                'nama_bank.required' => 'Nama Bank tidak boleh kosong',
                'nama_bank.min' => 'Nama Bank minimal 3 karakter',
                'nama_bank.max' => 'Nama Bank maksimal 15 karakter',
                'pemilik_rekening.required' => 'Pemilik Rekening tidak boleh kosong',
                'pemilik_rekening.min' => 'Pemilik Rekening minimal 3 karakter',
                'pemilik_rekening.max' => 'Pemilik Rekening maksimal 50 karakter',
                'nominal.required' => 'Nominal tidak boleh kosong',
                'img_bukti.required' => 'Bukti Pembayaran tidak boleh kosong',
                'img_bukti.image' => 'Bukti Pembayaran harus berupa gambar',
                'img_bukti.mimes' => 'Bukti Pembayaran harus berformat jpg, png, jpeg',
                'img_bukti.max' => 'Bukti Pembayaran maksimal 2MB',
            ]);

            // Process image upload
            $image = $request->file('img_bukti');
            $imgName = time() . rand() . '.' . $image->extension();
            $destinationPath = public_path('/buktipembayaraninfaq');
            $image->move($destinationPath, $imgName);
            $uploaded = $imgName;

            // Retrieve user's 'siswa' data
            $siswa = Siswa::where('id_user', Auth::user()->id_user)->first();

            $id_bank = Bank::all();

            $nominal = str_replace(['Rp ', '.'], '', $request->nominal);

            $infaqperbulan = Infaq_Perbulan::create([
                'nist' => $siswa->nist,
                'id_bank' => $id_bank->first()->id_bank,
                'nama_bank' => $ambil,
                'pemilik_rekening' => $request->pemilik_rekening,
                'nominal' => $nominal,
                'img_bukti' => $uploaded,
                'status' => 0,
            ]);

            Alert::success('Success', 'Data Infaq Perbulan Berhasil Ditambahkan!');
            return redirect('/dashboard/pembayaran/pembayaran')->with('success', 'Data Infaq Perbulan Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data infaq perbulan: ' . $e->getMessage());
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function exportPembayaran()
    {
        $filename = 'Data Pembayaran ' . date('Y-m-d_H-i-s') . '.xls';
        return Excel::download(new PembayaranExport, $filename);
    }

    public function detail_pendaftaran($nist)
    {
        $detailSiswa = Siswa::findOrFail($nist);
        $user = User::all();
        return view('dashboard.detail_pendaftaran', compact('detailSiswa', 'user'));
    }

    public function bukti_pembayaran($nist)
    {
        $detailSiswa = Siswa::findOrFail($nist);
        $pay = Payment::where('nist', $nist)->first();
        $bank = Bank::all();
        return view('dashboard.bukti_pembayaran', compact('detailSiswa', 'pay', 'bank'));
    }

    public function bukti_pembayaran_infaq($id)
    {
        $detailSiswa = Siswa::findOrFail($id);
        $pay = Infaq_Perbulan::where('nist', $id)->first();
        $bank = Bank::all();
        return view('dashboard.bukti_pembayaran_infaq', compact('detailSiswa', 'pay', 'bank'));
    }

    // Di dalam Controller Anda

    public function updateNominal(Request $request, $nist)
    {
        $request->validate([
            'nominal' => 'required',
        ]);

        $formattedNominal = preg_replace('/[Rp. ,]/', '', $request->nominal);

        if (!is_numeric($formattedNominal)) {
            return redirect()->back()->with('error', 'Format nominal tidak valid.');
        }

        $payment = Payment::where('nist', $nist)->firstOrFail();
        $payment->nominal = $formattedNominal;
        $payment->save();

        // Gunakan first() bukan firstOrFail()
        $akuntansi = Akuntansi::where('id_payment', $payment->id_payment)->first();

        // Cek jika objek akuntansi tidak null
        if ($akuntansi) {
            $akuntansi->debet = $formattedNominal;
            $akuntansi->saldo = Payment::where('status', 1)->sum('nominal');
            $akuntansi->save();
        }

        return redirect()->back()->with('success', 'Nominal pembayaran berhasil diperbarui.');
    }


    public function updateNominalInfaq(Request $request, $nist)
    {
        $request->validate([
            'nominal' => 'required',
        ]);

        $formattedNominal = preg_replace('/[Rp. ,]/', '', $request->nominal);

        if (!is_numeric($formattedNominal)) {
            return redirect()->back()->with('error', 'Format nominal tidak valid.');
        }

        $payment = Infaq_Perbulan::where('nist', $nist)->firstOrFail();
        $payment->nominal = $formattedNominal;
        $payment->save();

        // Gunakan first() bukan firstOrFail()
        $akuntansi = Akuntansi::where('id_payment', $payment->id_payment)->first();

        // Cek jika objek akuntansi tidak null
        if ($akuntansi) {
            $akuntansi->debet = $formattedNominal;
            $akuntansi->saldo = Infaq_Perbulan::where('status', 1)->sum('nominal');
            $akuntansi->save();
        }

        return redirect()->back()->with('success', 'Nominal pembayaran berhasil diperbarui.');
    }

    public function kirimWADitolak($phone)
    {
        $message = "Assalamualaikum..\n\n";
        $message .= "Mohon maaf, pembayaran anda tidak kami terima.\n\n";
        $message .= "Silahkan masukkan bukti pembayaran lagi yang benar di https://akademitamyiz.online/dashboard/pembayaran/pembayaran\n\n";
        $message .= "Terima kasih.\n\n";
        $api = WhatsAppAPI::first();

        $curl = curl_init();
        $token = $api->token;
        $data = [
            'phone' => $phone,
            'message' => $message,
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: $token",
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $api->base_server . '/api/send-message');
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function pembayaran_di_tolak(Request $request)
    {
        $userId = Auth::user()->id_user;
        $item = Payment::whereHas('siswa', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        })->first();
        $banks = Bank::all();
        $programs = Program::all();
        $students = Payment::with('siswa')->paginate(5);
        $user = User::all();

        $infaq = Infaq_Perbulan::with('siswa')->paginate(5);

        $perPageInfaq = $request->input('perPageInfaq', 10); // Default adalah 10 jika tidak ada input
        if ($perPageInfaq == 'all') {
            $infaq = Infaq_Perbulan::paginate(Infaq_Perbulan::count());
        } else {
            $infaq = Infaq_Perbulan::paginate($perPageInfaq);
        }

        $perPage = $request->input('perPage', 10); // Default adalah 10 jika tidak ada input
        if ($perPage == 'all') {
            $students = Payment::paginate(Payment::count());
        } else {
            $students = Payment::paginate($perPage);
        }
        return view('dashboard.pembayaran.pembayaran_ditolak', compact('students', 'item', 'banks', 'programs', 'user', 'perPage', 'infaq', 'perPageInfaq'));
    }

    public function pembayaran_diterima(Request $request)
    {
        $userId = Auth::user()->id_user;
        $item = Payment::whereHas('siswa', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        })->first();
        $banks = Bank::all();
        $programs = Program::all();
        $students = Payment::with('siswa')->paginate(5);
        $user = User::all();
        $periode_pendaftaran = Periode_pendaftaran::all();
        $infaq_perbulan = Infaq_Perbulan::with('siswa')->get();

        $iteminfaq = Infaq_Perbulan::whereHas('siswa', function ($query) use ($userId) {
            $query->where('id_user', $userId);
        })->first();

        $perPageInfaq = $request->input('perPageInfaq', 10); // Default adalah 10 jika tidak ada input
        if ($perPageInfaq == 'all') {
            $infaq_perbulan = Infaq_Perbulan::paginate(Infaq_Perbulan::count());
        } else {
            $infaq_perbulan = Infaq_Perbulan::paginate($perPageInfaq);
        }

        $perPage = $request->input('perPage', 10); // Default adalah 10 jika tidak ada input
        if ($perPage == 'all') {
            $students = Payment::paginate(Payment::count());
        } else {
            $students = Payment::paginate($perPage);
        }

        return view('dashboard.pembayaran.pembayaran_diterima', compact('students', 'item', 'banks', 'programs', 'user', 'perPage', 'periode_pendaftaran', 'infaq_perbulan', 'iteminfaq', 'perPageInfaq'));
    }

    public function generateNIST($birthdate)
    {
        // Ambil tahun sekarang
        $currentYear = date('Y');

        // Format tanggal lahir menjadi hari, bulan, dan tahun tahunnya lengkap misal 1999
        $formattedBirthdate = date('dmy', strtotime($birthdate));

        // Lakukan logika untuk menghasilkan urutan unik, misalnya 0000001
        $uniqueOrder = str_pad(Siswa::count() + 1, 7, '0', STR_PAD_LEFT);

        // Gabungkan semua bagian untuk membuat NIST
        $nist = $currentYear . $formattedBirthdate . $uniqueOrder;

        return $nist;
    }


    public function registerWA($phone, $registerId, $nama, $plainPassword, $created_at, $id, $id_program)
    {
        $program = Program::find($id_program);

        $bank = Bank::all();

        $siswa = Siswa::where('id_user', $id)->first();

        //memanggil phone admin di tabel user
        $user = User::where('role', 'admin')->first();

        $settingtamyiz = Setting_Tamyiz::all();

        $adminPhone = User::where('id_user', 1)->value('phone');

        $message = "Assalamualaikum..\n\n";

        $message .= "Terima kasih telah mendaftar di " . $settingtamyiz->first()->nama_pesantren . ".\n\n";

        $message .= "Berikut rincian INFO PENDAFTARAN Anda:\n\n";
        $message .= "I. BIODATA CALON PESERTA DIDIK\n";
        $message .= "Tanggal Daftar: " . date('j F, Y', strtotime($created_at)) . "\n";
        $message .= "Nama Lengkap: " . $nama . "\n";
        $message .= "NIST: " . $siswa->nist . "\n";
        $message .= "No. HP: " . $phone . "\n";
        $message .= "Program: " . $program->nama . "\n\n";

        $message .= "II. INFORMASI DAN PERSYARATAN\n";
        $message .= "A. Akun Anda\n";
        // membuat link untuk login bisa diakses melalui WhatsApp
        $message .= "Akses https://programkita.my.id/login, login dengan menggunakan:\n";
        $message .= "No. HP: " . $phone . "\n";
        $message .= "Password: " . $plainPassword . "\n\n";

        $message .= "Akun ini digunakan untuk melakukan pembayaran, dan mengecek status pembayaran pada" . $settingtamyiz->first()->nama_pesantren . ".\n\n";

        $message .= "B. Pembayaran\n";
        $message .= "Untuk melakukan pembayaran, silakan login ke akun Anda, lalu klik tombol 'Buat Pembayaran' pada halaman utama.\n\n";

        $message .= "Setelah melakukan pembayaran, silakan kembali ke link diatas untuk upload bukti pembayaran.\n\n";

        $message .= "Apabila anda sudah melakukan pembayaran, akun anda akan segera di verifikasi, silakan tunggu paling lambat 1x 24 jam. Apabila ada kendala silakan hubungi Customer Service kami di nomor: " . $adminPhone . "\n\n";


        $api = WhatsAppAPI::first();

        $curl = curl_init();
        $token = $api->token;
        $data = [
            'phone' => $phone,
            'message' => $message,
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: $token",
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $api->base_server . '/api/send-message');
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function create_calonsiswa()
    {
        $tanggal = Tanggal_buka_pendaftaran::first();
        $programs = Program::all(); // Get all programs
        return view('dashboard.data_siswa.create_calonsiswa', compact('programs'));
    }

    public function pendaftaran_yang_dilakukan_admin(Request $request)
    {
        // Cek apakah ada data di tabel WhatsAppAPI
        $whatsappApi = WhatsAppAPI::first();

        if (!$whatsappApi || empty($whatsappApi->token) || empty($whatsappApi->base_server)) {
            Alert::error('Gagal', 'Maaf, terjadi kendala sistem');
            return redirect('/error');
        }

        // $randomPassword = Str::random(8);

        $phone = $request->phone;

        $request->validate([
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'nama' => 'required|min:2|max:50', // Menambahkan validasi maksimal karakter
            'phone' => 'required|digits_between:10,15|unique:users,phone|numeric', // Menambahkan validasi unique

            'nist' => 'min:5|max:20|unique:santri,nist',
            'city' => 'required|min:3|max:15',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'nama.regex' => 'Nama harus terdiri dari first name dan last name yang dipisahkan oleh spasi.',
            'nama.min' => 'Nama terlalu pendek.',
            'nama.max' => 'Nama terlalu panjang.',
            'phone.unique' => 'No Hp sudah terdaftar',
            'phone.digits_between' => 'No Hp harus berupa angka dan minimal 10 karakter serta maksimal 15 karakter.',
            'phone.required' => 'No Hp harus diisi.',
            'phone.numeric' => 'No Hp harus berupa angka.',
            'city.required' => 'Kota harus diisi.',
            'city.string' => 'Kota harus berupa string.',
            'city.max' => 'Kota maksimal 255 karakter.',

            'nist.min' => 'NIST terlalu pendek.',
            'nist.max' => 'NIST terlalu panjang.',
            'nist.unique' => 'NIST sudah terdaftar.',
            'city.required' => 'Kota harus diisi.',
            'city.max' => 'Kota maksimal 15 karakter.',
            'city.min' => 'Kota minimal 3 karakter.',
        ]);

        // Generate unique register_id
        $lastUser = User::latest()->first(); // Get the latest user
        $lastId = $lastUser ? substr($lastUser->register_id, -5) : '00000'; // Extract last 5 characters of register_id
        $newId = intval($lastId) + 1; // Increment the numeric part of the register_id
        $formattedId = str_pad($newId, 5, '0', STR_PAD_LEFT); // Format the new ID to have leading zeros if needed
        $registerId = 'TamyizREG' . $formattedId; // Combine with the prefix

        $tanggal_lahir = $request->input('tahun_lahir') . '-' . $request->input('bulan_lahir') . '-' . $request->input('tanggal_lahir');

        // Pastikan tabel 'programs' ada dalam database dan nama tabelnya benar
        $program = Program::find($request->id_program);
        if (!$program) {
            Alert::error('Gagal', 'Program tidak ditemukan.');
            return back()->withErrors(['error' => 'Program tidak ditemukan.']);
        }


        $lastThreeDigitsphone = substr($phone, -3);

        // Daftar karakter unik yang ingin Anda gunakan
        $specialChars = "!@#$%^&*()_-=+;:,.?";

        // Generate 3 karakter unik secara acak dari daftar karakter di atas
        $randomSpecialChars = substr(str_shuffle($specialChars), 0, 3);

        // Gabungkan karakter unik ke dalam password
        $lastThreeDigitsphone = substr($phone, -3);

        // Daftar huruf yang ingin Anda gunakan
        $specialChars = "abcdefghijklmnopqrstuvwxyz";

        // Generate 3 karakter unik secara acak dari daftar karakter di atas
        $randomSpecialChars = substr(str_shuffle($specialChars), 0, 3);

        $plainPassword = "Tamyiz" . "-" . $lastThreeDigitsphone . $randomSpecialChars;

        // Gabungkan karakter unik ke dalam password
        $password = Hash::make($plainPassword);

        // Mengambil input tanggal lahir dari request dan memvalidasinya
        $tanggal_lahirInput = $request->input('tanggal_lahir');
        $bulan_lahirInput = $request->input('bulan_lahir');
        $tahun_lahirInput = $request->input('tahun_lahir');

        // Membuat tanggal lahir dalam format yang benar (YYYY-MM-DD)
        $tanggal_lahirnist = $tahun_lahirInput . $bulan_lahirInput . $tanggal_lahirInput;

        // Memeriksa apakah tanggal yang dibuat adalah tanggal yang valid
        if (!strtotime($tanggal_lahir)) {
            Alert::error('Gagal', 'Tanggal lahir tidak valid.');
            return back()->withErrors(['error' => 'Tanggal lahir tidak valid.']);
        }

        // Mengambil tahun sekarang
        $tahunSekarang = date('Y');

        try {
            // Proses penyimpanan data...
            $user = User::create([
                'nama' => $request->nama,
                'password' => $password,
                'role' => 'student',
                'phone' => $request->phone,
            ]);

            // Mengambil input tanggal lahir dari request dan memvalidasinya
            $tanggal_lahirInput = str_pad($request->input('tanggal_lahir'), 2, '0', STR_PAD_LEFT);
            $bulan_lahirInput = str_pad($request->input('bulan_lahir'), 2, '0', STR_PAD_LEFT);
            $tahun_lahirInput = $request->input('tahun_lahir');

            // Membuat tanggal lahir dalam format yang benar (YYYY-MM-DD)
            $tanggal_lahir = $tahun_lahirInput . '-' . $bulan_lahirInput . '-' . $tanggal_lahirInput;

            // Menggabungkan tahun sekarang dengan tanggal lahir untuk NIST
            $nistPrefix = $tahunSekarang . $tahun_lahirInput . $bulan_lahirInput . $tanggal_lahirInput;

            // Mendapatkan ID pengguna yang baru saja didaftarkan dengan padding nol
            // Pastikan panjang total NIST sesuai kebutuhan (misal 20 karakter)
            $lastUserId = User::orderBy('id_user', 'desc')->first()->id_user ?? 0;
            $nistId = $lastUserId;
            $formattedNistId = str_pad($nistId, 7, '0', STR_PAD_LEFT); // Menggunakan 7 digit untuk ID

            // Menggabungkan semua komponen untuk membuat NIST
            $fullNIST = $nistPrefix . $formattedNistId;

            Siswa::create([
                'id_user' => $user->id_user,
                'tanggal_lahir' => $tanggal_lahir,
                'nist' => $fullNIST,
                'jenis_kelamin' => $request->jenis_kelamin,
                'id_program' => $program->id_program,
                'city' => $request->city,
            ]);

            $plainPassword = "Tamyiz" . "-" . $lastThreeDigitsphone . $randomSpecialChars;

            // Panggil fungsi registerWA hanya untuk pengguna yang baru dibuat
            $this->registerWA($user->phone, $registerId, $request->nama, $plainPassword, $user->created_at, $user->id_user, $request->id_program, date('d-m-Y H:i:s'));

            $users = User::where('role', 'admin')->get();

            foreach ($users as $adminUser) {
                $this->notifikasi_khususadmin($adminUser->phone);
            }

            // Pemberitahuan berhasil
            Alert::success('Berhasil', 'Pendaftaran Berhasil Dilakukan');
            return redirect('/dashboard/data_siswa/calonsiswa');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kirimkan pesan gagal
            Alert::error('Gagal', 'Maaf, terjadi kendala saat proses pendaftaran.');
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function print()
    {
        $dataSiswa   = Siswa::all();
        $programs = Program::all();
        $bank = Bank::all();
        $user = User::all();
        $settingtamyiz = Setting_Tamyiz::all();
        return view('print', compact('dataSiswa', 'programs', 'bank', 'user', 'settingtamyiz'));
    }

    public function notifikasi_khususadmin($phone)
    {
        $settingtamyiz = Setting_Tamyiz::all();

        $message = "Assalamualaikum..\n\n";
        $message .= "Ada pendaftaran baru di " . $settingtamyiz->first()->nama_pesantren . ".\n\n";
        $message .= "Silahkan cek di https://akademitamyiz.online/pendaftaran\n\n";

        $api = WhatsAppAPI::first();

        $curl = curl_init();
        $token = $api->token;
        $data = [
            'phone' => $phone,
            'message' => $message,
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: $token",
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $api->base_server . '/api/send-message');
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function postPayment(Request $request)
    {
        try {
            $select = $request->nama_bank;
            if ($select == "bank_lainnya") {
                $ambil = $request->nama_bank_lainnya;
            } else {
                $ambil = $request->nama_bank;
            }

            // Validate request data
            $validatedData = $request->validate([
                'nama_bank' => 'required|min:3|max:15',
                'pemilik_rekening' => 'required|min:3|max:50',
                'nominal' => 'max:100000000000',
                'img_bukti' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                'infaq' => 'max:100000000000',
                'id_periodedaftar' => 'required',
                'id_bank' => 'required',
            ], [
                'nama_bank.required' => 'Nama Bank harus diisi.',
                'nama_bank.min' => 'Nama Bank minimal 3 karakter.',
                'nama_bank.max' => 'Nama Bank maksimal 15 karakter.',
                'pemilik_rekening.required' => 'Pemilik Rekening harus diisi.',
                'pemilik_rekening.min' => 'Pemilik Rekening minimal 3 karakter.',
                'pemilik_rekening.max' => 'Pemilik Rekening maksimal 50 karakter.',
                'nominal.max' => 'Nominal maksimal',
                'img_bukti.required' => 'Bukti Pembayaran harus diisi.',
                'img_bukti.image' => 'Bukti Pembayaran harus berupa gambar.',
                'img_bukti.mimes' => 'Bukti Pembayaran harus berupa file JPG, PNG, JPEG.',
                'img_bukti.max' => 'Ukuran bukti pembayaran maksimal 2 MB.',
                'infaq.max' => 'Infaq maksimal',
                'id_periodedaftar.required' => 'Periode Pendaftaran harus diisi.',
                'id_bank.required' => 'Bank harus diisi.',
            ]);

            // Process image upload
            $image = $request->file('img_bukti');
            $imgName = time() . rand() . '.' . $image->extension();
            $destinationPath = public_path('/buktipembayaran');
            $image->move($destinationPath, $imgName);
            $uploaded = $imgName;

            // Retrieve user's 'siswa' data
            $siswa = Siswa::where('id_user', Auth::user()->id_user)->first();

            $id_bank = Bank::all();

            // Create Payment record
            $payment = Payment::create([
                'nist' => $siswa->nist,
                'id_bank' => $id_bank->first()->id_bank,
                'id_periodedaftar' => $request->id_periodedaftar,
                'nama_bank' => $ambil,
                'pemilik_rekening' => $request->pemilik_rekening,
                'nominal' => (int)str_replace(['Rp. ', '.', ','], '', $request->nominal),
                'img_bukti' => $uploaded,
                'status' => '0',
                'infaq' => (int)str_replace([' ', '.', ','], '', $request->infaq),
                'jumlah_pembayaran' => $request->jumlah_pembayaran,
            ]);

            $payments = Payment::all();

            // After creating the new payment record
            $bank = Bank::find($payment->id_bank);
            $this->notifikasi_khususpemilikbank($bank->phone, $payment);


            // Jika berhasil, tampilkan alert sukses
            Alert::success('Berhasil', 'Pembayaran Berhasil Dilakukan');
            return redirect('/dashboard/pembayaran/pembayaran')->with('done', 'Payment has been completed!');
        } catch (\Exception $e) {
            // Jika terjadi error, tampilkan alert gagal
            Alert::error('Gagal', 'Terjadi Kesalahan: ' . $e->getMessage());
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function create_pembayaran_admin(Request $request)
    {
        $item = Payment::where('nist', '=', Auth::user()->id_user)->first();
        $banks = Bank::all();
        $programs = Program::all();
        $students = Payment::with('siswa')->paginate(5);
        $user = User::all();
        $periode_pendaftaran = Periode_pendaftaran::all();
        $infaq_perbulan = Infaq_Perbulan::with('siswa')->get();

        // Mengambil data siswa yang belum melakukan pembayaran dan memiliki status pembayaran 3
        $siswaBelumBayar = Siswa::whereDoesntHave('payments', function ($query) {
            $query->where('status', 3);
        })->pluck('nist');

        // Mengambil data siswa yang belum melakukan pembayaran dan yang memiliki status payment 3
        $siswa = Siswa::whereDoesntHave('payments', function ($query) {
            $query->where('status', '!=', 3); // Siswa yang belum melakukan pembayaran atau statusnya bukan 3 akan diabaikan
        })->orWhereHas('payments', function ($query) {
            $query->where('status', '=', 3); // Siswa yang memiliki pembayaran dengan status 3 akan disertakan
        })->get();
        
        $iteminfaq = Infaq_Perbulan::where('nist', '=', Auth::user()->id_user)->first();

        $perPageInfaq = $request->input('perPageInfaq', 10); // Default adalah 10 jika tidak ada input
        if ($perPageInfaq == 'all') {
            $infaq_perbulan = Infaq_Perbulan::with('siswa')->get();
        } else {
            $infaq_perbulan = Infaq_Perbulan::with('siswa')->paginate($perPageInfaq);
        }

        $perPage = $request->input('perPage', 10); // Default adalah 10 jika tidak ada input
        if ($perPage == 'all') {
            $students = Payment::with('siswa')->get();
        } else {
            $students = Payment::with('siswa')->paginate($perPage);
        }
        return view('dashboard.pembayaran.create_pembayaran_admin', compact('students', 'item', 'banks', 'programs', 'user', 'perPage', 'infaq_perbulan', 'perPageInfaq', 'periode_pendaftaran', 'iteminfaq', 'siswa', 'students'));
    }

    public function update_pembayaran_admin(Request $request)
    {
        try {
            $select = $request->nama_bank;
            if ($select == "bank_lainnya") {
                $ambil = $request->nama_bank_lainnya;
            } else {
                $ambil = $request->nama_bank;
            }

            // Validate request data
            $validatedData = $request->validate([
                'nama_bank' => 'required|min:3|max:15',
                'pemilik_rekening' => 'required|min:3|max:50',
                'nominal' => 'max:100000000000',
                'img_bukti' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                'infaq' => 'max:100000000000',
                'id_periodedaftar' => 'required',
                'id_bank' => 'required',
            ], [
                'nama_bank.required' => 'Nama Bank harus diisi.',
                'nama_bank.min' => 'Nama Bank minimal 3 karakter.',
                'nama_bank.max' => 'Nama Bank maksimal 15 karakter.',
                'pemilik_rekening.required' => 'Pemilik Rekening harus diisi.',
                'pemilik_rekening.min' => 'Pemilik Rekening minimal 3 karakter.',
                'pemilik_rekening.max' => 'Pemilik Rekening maksimal 50 karakter.',
                'nominal.max' => 'Nominal maksimal',
                'img_bukti.required' => 'Bukti Pembayaran harus diisi.',
                'img_bukti.image' => 'Bukti Pembayaran harus berupa gambar.',
                'img_bukti.mimes' => 'Bukti Pembayaran harus berupa file JPG, PNG, JPEG.',
                'img_bukti.max' => 'Ukuran bukti pembayaran maksimal 2 MB.',
                'infaq.max' => 'Infaq maksimal',
                'id_periodedaftar.required' => 'Periode Pendaftaran harus diisi.',
                'id_bank.required' => 'Bank harus diisi.',
            ]);

            // Process image upload
            $image = $request->file('img_bukti');
            $imgName = time() . rand() . '.' . $image->extension();
            $destinationPath = public_path('/buktipembayaran');
            $image->move($destinationPath, $imgName);
            $uploaded = $imgName;

            // Retrieve user's 'siswa' data
            $siswa = Siswa::where('id_user', Auth::user()->id_user)->first();

            $id_bank = Bank::all();

            // Create Payment record
            $payment = Payment::create([
                // Menggunakan nist yang dipilih oleh admin untuk mengambil id_user
                'nist' => $request->nist,
                'id_bank' => $id_bank->first()->id_bank,
                'id_periodedaftar' => $request->id_periodedaftar,
                'nama_bank' => $ambil,
                'pemilik_rekening' => $request->pemilik_rekening,
                'nominal' => (int)str_replace(['Rp. ', '.', ','], '', $request->nominal),
                'img_bukti' => $uploaded,
                'status' => '0',
                'infaq' => (int)str_replace([' ', '.', ','], '', $request->infaq),
                'jumlah_pembayaran' => $request->jumlah_pembayaran,
            ]);

            $payments = Payment::all();

            // After creating the new payment record
            $bank = Bank::find($payment->id_bank);
            $this->notifikasi_khususpemilikbank($bank->phone, $payment);


            // Jika berhasil, tampilkan alert sukses
            Alert::success('Berhasil', 'Pembayaran Berhasil Dilakukan');
            return redirect('/dashboard/pembayaran/pembayaran')->with('done', 'Payment has been completed!');
        } catch (\Exception $e) {
            // Jika terjadi error, tampilkan alert gagal
            Alert::error('Gagal', 'Terjadi Kesalahan: ' . $e->getMessage());
            return back()->withErrors($e->getMessage())->withInput();
        }
        // Redirect with success message
    }

    public function notifikasi_khususpemilikbank($phone, $payment)
    {

        $settingtamyiz = Setting_Tamyiz::all();

        $message = "Assalamualaikum..\n\n";
        $message .= "Ada pembayaran baru di " . $settingtamyiz->first()->nama_pesantren . ".\n\n";
        $message .= "Atas nama: " . $payment->first()->pemilik_rekening . "\n";
        $message .= "Nominal: " . 'Rp. ' . number_format($payment->first()->nominal, 0, ',', '.') . "\n";

        $api = WhatsAppAPI::first();

        $curl = curl_init();
        $token = $api->token;
        $data = [
            'phone' => $phone,
            'message' => $message,
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: $token",
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $api->base_server . '/api/send-message');
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function tolak($id_user)
    {
        $payment = Payment::where('nist', '=', $id_user)
            ->latest('created_at')
            ->first();
        try {
            Payment::where('nist', '=', $id_user)->update([
                'status' => 2,
            ]);

            Alert::success('Success', 'Pembayaran Berhasil Ditolak!');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat menolak pembayaran: ' . $e->getMessage());
        }

        $phone = $payment->siswa->user->phone;

        $this->kirimWADitolak($phone);

        return redirect()->back();
    }

    public function infaq_update(Request $request)
    {
        try {
            $select = $request->nama_bank;
            if ($select == "bank_lainnya") {
                $ambil = $request->nama_bank_lainnya;
            } else {
                $ambil = $request->nama_bank;
            }

            $validatedData = $request->validate([
                'id_user' => 'required',
                'nist' => 'required',
                'id_bank' => 'required',
                'nama_bank' => 'required|min:3|max:15',
                'pemilik_rekening' => 'required|min:3|max:50',
                'nominal' => 'required',
                'img_bukti' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                'status' => 'required',
            ], [
                'id_user.required' => 'User ID tidak boleh kosong',
                'nist.required' => 'Siswa ID tidak boleh kosong',
                'id_bank.required' => 'Bank ID tidak boleh kosong',
                'nama_bank.required' => 'Nama Bank tidak boleh kosong',
                'nama_bank.min' => 'Nama Bank minimal 3 karakter',
                'nama_bank.max' => 'Nama Bank maksimal 15 karakter',
                'pemilik_rekening.required' => 'Pemilik Rekening tidak boleh kosong',
                'pemilik_rekening.min' => 'Pemilik Rekening minimal 3 karakter',
                'pemilik_rekening.max' => 'Pemilik Rekening maksimal 50 karakter',
                'nominal.required' => 'Nominal tidak boleh kosong',
                'img_bukti.required' => 'Bukti Pembayaran tidak boleh kosong',
                'img_bukti.image' => 'Bukti Pembayaran harus berupa gambar',
                'img_bukti.mimes' => 'Bukti Pembayaran harus berformat jpg, png, jpeg',
                'img_bukti.max' => 'Bukti Pembayaran maksimal 2MB',
                'status.required' => 'Status tidak boleh kosong',
            ]);

            $image = $request->file('img_bukti');
            $imgName = time() . rand() . '.' . $image->extension();
            if (!file_exists(public_path('/buktipembayaran' . $image->getClientOriginalName()))) {
                $destinationPath = public_path('/buktipembayaran');
                $image->move($destinationPath, $imgName);
                $uploaded = $imgName;
            } else {
                $uploaded = $image->getClientOriginalName();
            }

            $siswa = Siswa::where('nama', Auth::user()->nama)->first();

            $id_bank = Bank::all();

            $nominal = str_replace(['Rp ', '.'], '', $request->nominal);

            Infaq_Perbulan::where('id_user', '=', Auth::user()->id_user)->update([
                'id_user' => Auth::user()->id_user,
                'nist' => $siswa->nist,
                'id_bank' => $id_bank->first()->id_bank,
                'nama_bank' => $ambil,
                'pemilik_rekening' => $request->pemilik_rekening,
                'nominal' => $nominal,
                'img_bukti' => $uploaded,
                'status' => 0,
            ]);

            Alert::success('Success', 'Pembayaran Berhasil Dilakukan');
            return redirect('/dashboard/pembayaran/pembayaran');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat melakukan pembayaran: ' . $e->getMessage());
            return back()->withErrors($e->getMessage())->withInput();
        }
    }



    public function pembayaran_update(Request $request)
    {
        $select = $request->nama_bank;
        if ($select == "bank_lainnya") {
            $ambil = $request->nama_bank_lainnya;
        } else {
            $ambil = $request->nama_bank;
        }
        $request->validate([
            'nama_bank' => 'required',
            'pemilik_rekening' => 'required',
            'nominal' => 'required',
            'img_bukti' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        $image = $request->file('img_bukti');
        $imgName = time() . rand() . '.' . $image->extension();
        if (!file_exists(public_path('/buktipembayaran' . $image->getClientOriginalName()))) {
            $destinationPath = public_path('/buktipembayaran');
            $image->move($destinationPath, $imgName);
            $uploaded = $imgName;
        } else {
            $uploaded = $image->getClientOriginalName();
        }


        // Retrieve user's 'siswa' data
        $siswa = Siswa::where('nama', Auth::user()->nama)->first();

        Payment::where('id_user', Auth::user()->id_user)->update([
            'nama_bank' => $ambil,
            'pemilik_rekening' => $request->pemilik_rekening,
            'nominal' => (int)str_replace(['Rp. ', '.', ','], '', $request->nominal),
            'img_bukti' => $uploaded,
            'status' => '0',
            'infaq' => (int)str_replace([' ', '.', ','], '', $request->infaq),
            'jumlah_pembayaran' => $request->jumlah_pembayaran,
        ]);
        return redirect('/dashboard/pembayaran/pembayaran');
    }

    public function formPendaftaran()
    {
        $tanggal = Tanggal_buka_pendaftaran::first();

        $sekarang = date('Y-m-d');
        $besok = date('Y-m-d', strtotime('+1 day'));

        if ($sekarang >= $tanggal->tanggal_buka && $besok <= $tanggal->tanggal_tutup) {
            return view('pendaftaran');
        } else {
            return view('pendaftaran-ditutup');
        }
    }

    //menampilkan data calon siswa
    public function dataSiswa(Request $request)
    {
        $dataSiswa = Siswa::where('nist', Auth::user()->id_user)->get();
        $users = User::where('role', 'student')->get();
        $pembayaran = Payment::all();

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        $query = Siswa::query();

        if ($perPage == 'all') {
            $datasiswapage = $query->get();
        } else {
            $datasiswapage = $query->paginate($perPage);
        }
        return view('dashboard.data_siswa.calonsiswa', compact('dataSiswa', 'pembayaran', 'perPage', 'users'));
    }

    public function updatepassworddatasiswa(User $user)
    {
        return view('dashboard.data_siswa.updatepassworddatasiswa', compact('user'));
    }

    public function editpassworddatasiswa(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/',
        ], [
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial.',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        $this->kirimWAUpdatePassword($user->phone, $user->nama, $request->password);

        if ($user) {
            Alert::success('Success', 'Password Berhasil Diubah!');
        } else {
            Alert::error('Error', 'Gagal Mengubah Password');
        }
        return redirect()->route('calonsiswa');
    }

    public function kirimWAUpdatePassword($phone, $nama, $password)
    {
        $message = "Assalamualaikum..\n\n";
        $message .= "Password anda telah diubah.\n\n";
        $message .= "Berikut adalah password anda:\n";
        $message .= "Password: " . $password . "\n\n";
        $message .= "Silahkan login di https://akademitamyiz.online/login\n\n";

        $api = WhatsAppAPI::first();

        $curl = curl_init();
        $token = $api->token;
        $data = [
            'phone' => $phone,
            'message' => $message,
        ];

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: $token",
        ));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $api->base_server . '/api/send-message');
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public function calonSiswaExport()
    {
        $filename = 'Data Calon Siswa ' . date('Y-m-d_H-i-s') . '.xls';
        return Excel::download(new CalonSiswaExport, $filename);
    }

    public function editProfileAdmin(User $user)
    {
        $dataAdmin = User::where('id_user', $user->id_user)->get();
        return view('edit_profile.edit_profile_admin', compact('user', 'dataAdmin'));
    }

    public function updateProfileAdmin(Request $request, User $user)
    {
        //update nama di tabel user dan admin
        $request->validate([
            'nama' => 'required|min:2|max:50', // Menambahkan validasi maksimal karakter
        ], [
            'nama.required' => 'Nama harus diisi.',
            'nama.min' => 'Nama terlalu pendek.',
            'nama.max' => 'Nama terlalu panjang.',
        ]);

        $adminData = [
            'nama' => $request->nama,
        ];

        $admin = User::where('id_user', $user->id_user)
            ->update($adminData);

        if ($admin) {
            Alert::success('Success', 'Data Profile Berhasil Diubah!');
        } else {
            Alert::error('Error', 'Gagal Mengubah Data Profile');
        }

        return redirect()->back();
    }

    public function updateAkunAdmin(Request $request, User $user)
    {
        $request->validate(
            [
                //phone harus angka
                'phone' => 'required|min:9|max:13|regex:/^[0-9]+$/',
            ],
            [
                'phone.required' => 'No Hp harus diisi.',
                'phone.min' => 'Jumlah digit No Hp tidak sesuai',
                'phone.max' => 'Jumlah digit No Hp tidak sesuai',
                'phone.regex' => 'No Hp harus berupa angka.',
            ]
        );

        $userData = [
            'phone' => $request->phone,
        ];

        if (!empty($request->password)) {
            $userData['password'] = Hash::make($request->password);
        }

        $updated = User::where('id_user', $user->id_user)->update($userData);

        if ($updated) {
            Alert::success('Success', 'Data Profile Berhasil Diubah!');
        } else {
            Alert::error('Error', 'Gagal Mengubah Data Profile');
        }

        return redirect()->back();
    }

    public function editProfileSiswa(User $user)
    {
        $siswa = Siswa::where('id_user', $user->id_user)->get();
        return view('edit_profile.edit_profile_siswa', compact('user', 'siswa'));
    }

    public function updateProfileSiswa(Request $request, User $user, Siswa $siswa)
    {
        $request->validate([]);

        $siswaData = [
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'city' => $request->city,
        ];

        $siswa = Siswa::where('id_user', $user->id_user)
            ->update($siswaData);
        Alert::success('Success', 'Data Profile Berhasil Diubah!');
        return redirect()->back();
    }

    public function updateAkunSiswa(Request $request, User $user)
    {
        $request->validate([
            'phone' => 'required|min:9|max:13',
            'password' => 'min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/',
        ], [
            'phone.required' => 'No Hp harus diisi.',
            'phone.min' => 'No Hp minimal 9 digit.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf kecil, huruf besar, angka, dan simbol.',
        ]);

        $userData = [
            'phone' => $request->phone,
        ];

        if (!empty($request->password)) {
            $userData['password'] = Hash::make($request->password);
        }

        $updated = User::where('id_user', $user->id_user)->update($userData);

        if ($updated) {
            Alert::success('Success', 'Data Profile Berhasil Diubah!');
        } else {
            Alert::error('Error', 'Gagal Mengubah Data Profile');
        }

        return redirect()->back();
    }


    public function cari(Request $request)
    {
        $cari = $request->cari;

        $siswa = Siswa::where('nama', 'like', "%" . $cari . "%")
            ->orWhere('nis', 'like', "%" . $cari . "%")
            ->paginate();

        return view('dashboard.pembayaran.pembayaran', compact('siswa'));
    }
}
