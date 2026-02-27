<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.documentation.index');
    }


    public function send(Request $request)
    {
        return view('pages.documentation.send');
    }

    public function receive(Request $request)
    {
        return view('pages.documentation.receive');
    }

    public function webhooks(Request $request)
    {
        return view('pages.documentation.webhooks');
    }
}
