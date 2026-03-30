<?php

namespace App\Http\Controllers;

use App\Models\Proforma;

class ProformaController extends Controller
{
    public function print(Proforma $proforma)
    {
        return view('proforma.print', compact('proforma'));
    }
}
