<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class ServicosControler extends Controller
{
    public function list(Request $request)
    {
        return Inertia::render('Servico', [
        ]);
    }
}
