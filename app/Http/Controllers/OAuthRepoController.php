<?php

declare(strict_types=1);

namespace App\Http\Controllers;
use App\Models\OAuthRepo;
class OAuthRepoController extends Controller
{
    private const TITLE = 'OAuthRepo';

    public function index()
    {
        $model = new OAuthRepo();
        $columns = \Schema::getColumnListing($model->getTable());
        $data = $model->all();
        $title = self::TITLE;
        $tableName = $model->getTable();
        return view('table', compact('data', 'columns', 'title','tableName'));
    }
}