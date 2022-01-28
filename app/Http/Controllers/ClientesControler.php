<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class ClientesControler extends Controller
{
    public function list(Request $request)
    {
        return Inertia::render('Cliente', [
            
        ]);
    }
}
