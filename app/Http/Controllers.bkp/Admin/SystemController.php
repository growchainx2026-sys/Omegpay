<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SystemController extends Controller
{
    public function update(Request $request)
    {
        if(auth()->user()->permission != 'admin'){
            return redirect()->to('/dashboard');
        }

       $response = Http::get('https://pagapix.shop/api/update/version.json');
       $new = $response->json(); 
        return view('pages.admin.system-update', compact('new'));
    }
}
