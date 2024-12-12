<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\OAuthUser;

class OAuthUserController extends Controller
{
    private const TITLE = 'OAuthUser';

    public function index()
    {
        $model = new OAuthUser();
        $columns = \Schema::getColumnListing($model->getTable());
        $data = $model->all();
        $title = self::TITLE;
        $tableName = $model->getTable();
        return view('table', compact('data', 'columns', 'title','tableName'));
    }
}