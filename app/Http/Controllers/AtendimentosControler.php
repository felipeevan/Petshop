<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class AtendimentosControler extends Controller
{
    public function list(Request $request)
    {
        return Inertia::render('Atendimento', [
        ]);
    }
}
