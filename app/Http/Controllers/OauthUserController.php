<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\OauthUser;

class OauthUserController extends Controller
{
    private const TITLE = 'OauthUser';

    public function index()
    {
        $model = new OAuthUser();
        $columns = \Schema::getColumnListing($model->getTable());
        $data = $model->all();
        $title = self::TITLE;
        return view('table', compact('data', 'columns', 'title'));
    }
}