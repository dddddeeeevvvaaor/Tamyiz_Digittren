<?php

use App\Http\Controllers\PpdbController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\WhatsAppAPIController;
use App\Http\Controllers\TanggalbukapendaftaranController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\AkunBaruUserController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\AkuntansiController;
use App\Http\Controllers\PeriodePendaftaranController;
use App\Http\Controllers\SettingTamyizController;
use App\Http\Controllers\InfaqPerbulanController;
use App\Http\Controllers\GolonganController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PpdbController::class, 'login']);

Route::middleware('isGuest')->group(function () {
    Route::get('/login', [PpdbController::class, 'login'])->name('login');
    Route::post('/login/auth', [PpdbController::class, 'auth'])->name('login.auth');
    Route::post('/postLogin', [PpdbController::class, 'postLogin'])->name('postLogin');
    Route::get('/daftar', [PpdbController::class, 'daftar'])->name('daftar');
    Route::get('/formPendaftaran', [PpdbController::class, 'formPendaftaran'])->name('formPendaftaran');
    Route::post('/store', [PpdbController::class, 'store'])->name('store');
    Route::get('/print', [PpdbController::class, 'print'])->name('print');
});

Route::middleware(['isLogin', 'cekRole:admin,student'])->group(function () {
    Route::get('/dashboard', [PpdbController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/pembayaran/pembayaran', [PpdbController::class, 'pembayaran'])->name('pembayaran');
    Route::get('/dashboard/pembayaran/pembayaran_infaq', [PpdbController::class, 'pembayaran_infaq'])->name('pembayaran_infaq');
    Route::get('/edit_profile_admin/{user}', [PpdbController::class, 'editProfileAdmin'])->name('edit_profile_admin.editProfileAdmin');
    Route::patch('/update_profile_admin/{user}', [PpdbController::class, 'updateProfileAdmin'])->name('update_profile_admin.updateProfileAdmin');
    Route::patch('/update_akun_admin/{user}', [PpdbController::class, 'updateAkunAdmin'])->name('update_akun_admin.updateAkunAdmin');
    Route::get('/edit_profile_siswa/{user}', [PpdbController::class, 'editProfileSiswa'])->name('edit_profile_siswa.editProfileSiswa');
    Route::patch('/update_profile_siswa/{user}', [PpdbController::class, 'updateProfileSiswa'])->name('update_profile_siswa.updateProfileSiswa');
    Route::patch('/update_akun_siswa/{user}', [PpdbController::class, 'updateAkunSiswa'])->name('update_akun_siswa.updateAkunSiswa');
});

Route::middleware(['isLogin', 'cekRole:student'])->group(function () {
    Route::post('/postPayment', [PpdbController::class, 'postPayment'])->name('postPayment');
    Route::post('/postInfaq', [PpdbController::class, 'postInfaq'])->name('postInfaq');
    Route::patch('/pembayaran/update', [PpdbController::class, 'pembayaran_update'])->name('pembayaran.update');
    Route::patch('/pembayaran/infaq_update', [PpdbController::class, 'infaq_update'])->name('infaq.update');    
});

Route::middleware(['isLogin', 'cekRole:admin'])->group(function () {
    Route::get('/dashboard/bukti_pembayaran/{id_user}', [PpdbController::class, 'bukti_pembayaran'])->name('bukti');
    Route::get('/dashboard/bukti_pembayaran_infaq/{nist}', [PpdbController::class, 'bukti_pembayaran_infaq'])->name('bukti_infaq');
    Route::patch('/dashboard/bukti_pembayaran/update_nominal/{nist}', [PpdbController::class, 'updateNominal'])->name('update_nominal');
    Route::patch('/dashboard/bukti_pembayaran/update_nominal_infaq/{id}', [PpdbController::class, 'updateNominalInfaq'])->name('update_nominal_infaq');
    Route::get('/dashboard/detail_pendaftaran/{id_user}', [PpdbController::class, 'detail_pendaftaran'])->name('detail.pendaftaran');
    Route::patch('/dashboard/pembayaran/validasi/{id_user}', [PpdbController::class, 'validasi'])->name('validasi');
    Route::patch('/dashboard/pembayaran/validasiinfaq/{id}', [PpdbController::class, 'validasiinfaq'])->name('validasiinfaq');
    Route::patch('/dashboard/pembayaran/tolak/{id_user}', [PpdbController::class, 'tolak'])->name('tolak');
    Route::patch('/dashboard/pembayaran/tolakinfaq/{id}', [PpdbController::class, 'tolakinfaq'])->name('tolakinfaq');
    Route::get('/dashboard/pembayaran/pembayaran_ditolak', [PpdbController::class, 'pembayaran_di_tolak'])->name('pembayaran_di_tolak');
    Route::get('/dashboard/pembayaran/pembayaran_diterima', [PpdbController::class, 'pembayaran_diterima'])->name('pembayaran_diterima');
    Route::get('/dashboard/pembayaran/export/export_pembayaran_verifikasi', [PpdbController::class, 'exportPembayaran'])->name('pembayaran_export');

    Route::get('/dashboard/program/index', [ProgramController::class, 'index'])->name('program.index');
    Route::get('/dashboard/program/create', [ProgramController::class, 'create'])->name('program.create');
    Route::post('/dashboard/program/store', [ProgramController::class, 'store'])->name('program.store');
    Route::get('/dashboard/program/edit/{program}', [ProgramController::class, 'edit'])->name('program.edit');
    Route::patch('/dashboard/program/update/{program}', [ProgramController::class, 'update'])->name('program.update');
    Route::delete('/dashboard/program/destroy/{program}', [ProgramController::class, 'destroy'])->name('program.destroy');

    Route::get('/dashboard/whatsapp_api/index', [WhatsAppAPIController::class, 'index'])->name('whatsapp_api.index');
    Route::get('/dashboard/whatsapp_api/create', [WhatsAppAPIController::class, 'create'])->name('whatsapp_api.create');
    Route::post('/dashboard/whatsapp_api/store', [WhatsAppAPIController::class, 'store'])->name('whatsapp_api.store');
    Route::get('/dashboard/whatsapp_api/edit/{whatsapp_api}', [WhatsAppAPIController::class, 'edit'])->name('whatsapp_api.edit');
    Route::patch('/dashboard/whatsapp_api/update/{whatsapp_api}', [WhatsAppAPIController::class, 'update'])->name('whatsapp_api.update');
    Route::delete('/dashboard/whatsapp_api/destroy/{whatsapp_api}', [WhatsAppAPIController::class, 'destroy'])->name('whatsapp_api.destroy');

    Route::get('/dashboard/tanggalbukapendaftaran/index', [TanggalbukapendaftaranController::class, 'index'])->name('tanggalbukapendaftaran.index');
    Route::get('/dashboard/tanggalbukapendaftaran/create', [TanggalbukapendaftaranController::class, 'create'])->name('tanggalbukapendaftaran.create');
    Route::post('/dashboard/tanggalbukapendaftaran/store', [TanggalbukapendaftaranController::class, 'store'])->name('tanggalbukapendaftaran.store');
    Route::get('/dashboard/tanggalbukapendaftaran/edit/{tanggalbukapendaftaran}', [TanggalbukapendaftaranController::class, 'edit'])->name('tanggalbukapendaftaran.edit');
    Route::patch('/dashboard/tanggalbukapendaftaran/update/{tanggalbukapendaftaran}', [TanggalbukapendaftaranController::class, 'update'])->name('tanggalbukapendaftaran.update');
    Route::delete('/dashboard/tanggalbukapendaftaran/destroy/{tanggalbukapendaftaran}', [TanggalbukapendaftaranController::class, 'destroy'])->name('tanggalbukapendaftaran.destroy');

    Route::get('/dashboard/bank/index', [BankController::class, 'index'])->name('bank.index');
    Route::get('/dashboard/bank/create', [BankController::class, 'create'])->name('bank.create');
    Route::post('/dashboard/bank/store', [BankController::class, 'store'])->name('bank.store');
    Route::get('/dashboard/bank/edit/{bank}', [BankController::class, 'edit'])->name('bank.edit');
    Route::patch('/dashboard/bank/update/{bank}', [BankController::class, 'update'])->name('bank.update');
    Route::delete('/dashboard/bank/destroy/{bank}', [BankController::class, 'destroy'])->name('bank.destroy');

    Route::get('/dashboard/data_siswa/calonsiswa', [PpdbController::class, 'dataSiswa'])->name('calonsiswa');
    Route::get('/dashboard/data_siswa/updatepassworddatasiswa/{user}', [PpdbController::class, 'updatepassworddatasiswa'])->name('updatepassworddatasiswa');
    Route::patch('/dashboard/data_siswa/updatepassworddatasiswa/editpassworddatasiswa/{user}', [PpdbController::class, 'editpassworddatasiswa'])->name('editpassworddatasiswa');
    Route::get('/dashboard/data_siswa/export', [PpdbController::class, 'calonSiswaExport'])->name('calonsiswa_export');

    Route::get('/dashboard/akun_baru_user/index', [AkunBaruUserController::class, 'index'])->name('akun_baru_user.index');
    Route::get('/dashboard/akun_baru_user/create', [AkunBaruUserController::class, 'create'])->name('akun_baru_user.create');
    Route::post('/dashboard/akun_baru_user/store', [AkunBaruUserController::class, 'store'])->name('akun_baru_user.store');
    Route::get('/dashboard/akun_baru_user/edit/{akun_baru_user}', [AkunBaruUserController::class, 'edit'])->name('akun_baru_user.edit');
    Route::patch('/dashboard/akun_baru_user/update/{akun_baru_user}', [AkunBaruUserController::class, 'update'])->name('akun_baru_user.update');
    Route::delete('/dashboard/akun_baru_user/destroy/{akun_baru_user}', [AkunBaruUserController::class, 'destroy'])->name('akun_baru_user.destroy');
    Route::patch('/dashboard/akun_baru_user/update_status/{akun_baru_user}', [AkunBaruUserController::class, 'updateStatus'])->name('update_status');
    Route::post('/dashboard/akun_baru_user/reopen_and_delete/{akun_baru_user}', [AkunBaruUserController::class, 'reopenAndDelete'])->name('reopen_and_delete');
    Route::delete('/dashboard/akun_baru_user/filter_status', [AkunBaruUserController::class, 'filterStatus'])->name('filter_status');
    Route::get('/dashboard/akun_baru_user/filter/{status}', [AkunBaruUserController::class, 'getByStatus'])->name('akun_baru_user.filter');
    Route::get('/dashboard/akun_baru_user/format_import', [AkunBaruUserController::class, 'format_import'])->name('akun_baru_user.format_import');
    Route::post('/dashboard/akun_baru_user/import', [AkunBaruUserController::class, 'import'])->name('akun_baru_user.import');


    Route::get('/dashboard/akun_baru_user/editpassword/{akun_baru_user}', [AkunBaruUserController::class, 'editpassword'])->name('akun_baru_user.editpassword');
    Route::patch('/dashboard/akun_baru_user/updatepassword/{akun_baru_user}', [AkunBaruUserController::class, 'updatepassword'])->name('akun_baru_user.updatepassword');

    Route::get('/dashboard/pengumuman/index', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::get('/dashboard/pengumuman/create', [PengumumanController::class, 'create'])->name('pengumuman.create');
    Route::post('/dashboard/pengumuman/store', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::get('/dashboard/pengumuman/edit/{pengumuman}', [PengumumanController::class, 'edit'])->name('pengumuman.edit');
    Route::patch('/dashboard/pengumuman/update/{pengumuman}', [PengumumanController::class, 'update'])->name('pengumuman.update');
    Route::delete('/dashboard/pengumuman/destroy/{pengumuman}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    Route::get('/dashboard/laporanakuntansi/akuntansi', [AkuntansiController::class, 'index'])->name('akuntansi.index');
    Route::get('/dashboard/laporanakuntansi/akuntansi/filter', [AkuntansiController::class,'filter'])->name('akuntansi.filter');
    Route::get('/dashboard/laporanakuntansi/akuntansi/filter_infaq', [AkuntansiController::class,'filter_infaq'])->name('akuntansi.filter_infaq');
    Route::get('/dashboard/laporanakuntansi/export', [AkuntansiController::class, 'export'])->name('akuntansi_export');
    Route::get('/dashboard/laporanakuntansi/format_import', [AkuntansiController::class, 'format_import'])->name('akuntansi_format_import');
    Route::post('/dashboard/laporanakuntansi/import', [AkuntansiController::class, 'import'])->name('akuntansi_import');

    Route::get('/dashboard/pembayaran/cari', [PpdbController::class, 'cari'])->name('cari');

    Route::get('/dashboard/akun_baru_user/export/{status?}/{golongan?}', [AkunBaruUserController::class, 'export'])->name('akun_baru_user.export');

    Route::get('/dashboard/data_siswa/create_calonsiswa', [PpdbController::class, 'create_calonsiswa'])->name('create_calonsiswa');

    Route::post('/dashboard/data_siswa/pendaftaran_yang_dilakukan_admin', [PpdbController::class, 'pendaftaran_yang_dilakukan_admin'])->name('pendaftaran_yang_dilakukan_admin');

    Route::get('/dashboard/pembayaran/create_pembayaran_admin', [PpdbController::class, 'create_pembayaran_admin'])->name('create_pembayaran_admin');

    Route::post('/dashboard/pembayaran/update_pembayaran_admin', [PpdbController::class, 'update_pembayaran_admin'])->name('update_pembayaran_admin');


    Route::get('/dashboard/settingtamyiz/index', [SettingTamyizController::class, 'index'])->name('settingtamyiz.index');
    Route::get('/dashboard/settingtamyiz/create', [SettingTamyizController::class, 'create'])->name('settingtamyiz.create');
    Route::post('/dashboard/settingtamyiz/store', [SettingTamyizController::class, 'store'])->name('settingtamyiz.store');
    Route::get('/dashboard/settingtamyiz/edit/{settingtamyiz}', [SettingTamyizController::class, 'edit'])->name('settingtamyiz.edit');
    Route::patch('/dashboard/settingtamyiz/update/{settingtamyiz}', [SettingTamyizController::class, 'update'])->name('settingtamyiz.update');
    Route::delete('/dashboard/settingtamyiz/destroy/{settingtamyiz}', [SettingTamyizController::class, 'destroy'])->name('settingtamyiz.destroy');
    Route::post('/dashboard/settingtamyiz/storeorupdate', [SettingTamyizController::class, 'storeOrUpdate'])->name('settingtamyiz.storeorupdate');    

    Route::get('/dashboard/periode_pendaftaran/index', [PeriodePendaftaranController::class, 'index'])->name('periode_pendaftaran.index');
    Route::get('/dashboard/periode_pendaftaran/create', [PeriodePendaftaranController::class, 'create'])->name('periode_pendaftaran.create');
    Route::post('/dashboard/periode_pendaftaran/store', [PeriodePendaftaranController::class, 'store'])->name('periode_pendaftaran.store');
    Route::get('/dashboard/periode_pendaftaran/edit/{periode}', [PeriodePendaftaranController::class, 'edit'])->name('periode_pendaftaran.edit');
    Route::patch('/dashboard/periode_pendaftaran/update/{periode}', [PeriodePendaftaranController::class, 'update'])->name('periode_pendaftaran.update');
    Route::delete('/dashboard/periode_pendaftaran/destroy/{periode}', [PeriodePendaftaranController::class, 'destroy'])->name('periode_pendaftaran.destroy');

    Route::get('/dashboard/infaq_perbulan/index', [InfaqPerbulanController::class, 'index'])->name('infaq_perbulan.index');
    Route::get('/dashboard/infaq_perbulan/create', [InfaqPerbulanController::class, 'create'])->name('infaq_perbulan.create');
    Route::post('/dashboard/infaq_perbulan/store', [InfaqPerbulanController::class, 'store'])->name('infaq_perbulan.store');
    Route::get('/dashboard/infaq_perbulan/edit/{infaq_perbulan}', [InfaqPerbulanController::class, 'edit'])->name('infaq_perbulan.edit');
    Route::patch('/dashboard/infaq_perbulan/update/{infaq_perbulan}', [InfaqPerbulanController::class, 'update'])->name('infaq_perbulan.update');
    Route::delete('/dashboard/infaq_perbulan/destroy/{infaq_perbulan}', [InfaqPerbulanController::class, 'destroy'])->name('infaq_perbulan.destroy');

    Route::get('/dashboard/golongan/index', [GolonganController::class, 'index'])->name('golongan.index');
    Route::get('/dashboard/golongan/create', [GolonganController::class, 'create'])->name('golongan.create');
    Route::post('/dashboard/golongan/store', [GolonganController::class, 'store'])->name('golongan.store');
    Route::get('/dashboard/golongan/edit/{golongan}', [GolonganController::class, 'edit'])->name('golongan.edit');
    Route::patch('/dashboard/golongan/update/{golongan}', [GolonganController::class, 'update'])->name('golongan.update');
    Route::delete('/dashboard/golongan/destroy/{golongan}', [GolonganController::class, 'destroy'])->name('golongan.destroy');
});


Route::get('/logout', [PpdbController::class, 'logout'])->name('logout');
Route::get('/error', [PpdbController::class, 'error'])->name('error');
