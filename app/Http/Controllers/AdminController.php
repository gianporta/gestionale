<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Mostra la dashboard dell'admin.
     */
    public function index()
    {
        return view('admin.index');
    }
}