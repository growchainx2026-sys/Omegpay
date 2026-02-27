<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $banners = Banner::get();
        return view('pages.admin.banners', compact('banners'));
    }
    
    public function create(Request $request)
    {
        $data = $request->only(['title', 'description', 'url']);

        $imageFields = ['image'];
       // dd($request->file('image'));
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Faz o upload da nova imagem com um nome único
                $image = $request->file($field);
                $imageName = 'banner_'.uniqid(). '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('banners', $imageName, 'public');

                $data[$field] = '/'.$imagePath;

            }
        }

        Banner::create($data);

        return back()->with('success', 'Banner cadastrado com sucesso.');
    }

    public function edit(Request $request, $id)
    {
        
        $data = $request->only(['title', 'description', 'url']);

        $imageFields = ['image'];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Faz o upload da nova imagem com um nome único
                $image = $request->file($field);
                $imageName = $field. '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('banners', $imageName, 'public');

                $data[$field] = '/'.$imagePath;

            }
        }

        Banner::findOrFail($id)->update($data);

        return back()->with('success', 'Banner alterado com sucesso.');
    }

    public function destroy(Request $request, $id)
    {
        Banner::findOrFail($id)->delete();

        return back()->with('success', 'Banner excluído com sucesso.');
    }

}
