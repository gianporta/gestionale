<?php

namespace App\Http\Controllers;

use App\Models\Quote;

class QuoteController extends Controller
{
    public function print(Quote $quote)
    {
        return view('quote.print', compact('quote'));
    }
}
