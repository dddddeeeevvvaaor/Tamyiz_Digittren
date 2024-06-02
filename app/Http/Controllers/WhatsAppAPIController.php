<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhatsAppAPI;
use RealRashid\SweetAlert\Facades\Alert;

class WhatsAppAPIController extends Controller
{
    public function index()
    {
        $whatsapp_api = WhatsAppAPI::all();
        return view('dashboard.whatsappapi.index', compact('whatsapp_api'));
    }

    public function create()
    {
        return view('dashboard.whatsappapi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'base_server' => 'required',
        ]);

        try {
            WhatsAppAPI::create([
                'token' => $request->token,
                'base_server' => $request->base_server,
            ]);

            Alert::success('Success', 'Data WhatsApp API Berhasil Ditambahkan!')->showConfirmButton('OK');
            return redirect()->route('whatsapp_api.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan data WhatsApp API. Silakan coba lagi.')->showConfirmButton('OK');
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(WhatsAppAPI $whatsapp_api)
    {
        return view('dashboard.whatsappapi.edit', compact('whatsapp_api'));
    }

    public function update(Request $request, WhatsAppAPI $whatsapp_api)
    {
        $request->validate([
            'token' => 'required',
            'base_server' => 'required',
        ]);

        try {
            WhatsAppAPI::where('id_wa_api', $whatsapp_api->id_wa_api)
                ->update([
                    'token' => $request->token,
                    'base_server' => $request->base_server,
                ]);

            Alert::success('Success', 'Data WhatsApp API Berhasil Diubah!')->showConfirmButton('OK');
            return redirect()->route('whatsapp_api.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan saat mengubah data.')->showConfirmButton('OK');
            return redirect()->back();
        }
    }

    public function destroy(WhatsAppAPI $whatsapp_api)
    {
        try {
            WhatsAppAPI::destroy($whatsapp_api->id_wa_api);
            Alert::success('Success', 'Data WhatsApp API Berhasil Dihapus!')->showConfirmButton('OK');
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus data WhatsApp API. Silakan coba lagi.')->showConfirmButton('OK');
        }
        return redirect()->route('whatsapp_api.index');
    }
}
