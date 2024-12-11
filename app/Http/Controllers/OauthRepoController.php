<?php

declare(strict_types=1);

namespace App\Http\Controllers;
use App\Models\OauthRepo;
class OauthRepoController extends Controller
{
    private const TITLE = 'OauthRepo';

    public function index()
    {
        $model = new OauthRepo();
        $columns = \Schema::getColumnListing($model->getTable());
        $data = $model->all();
        $title = self::TITLE;
        return view('table', compact('data', 'columns', 'title'));
    }
}