<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Repo;

class RepoController extends Controller
{
    private const TITLE = 'Repo';

    public function index()
    {
        $model = new Repo();
        $columns = \Schema::getColumnListing($model->getTable());
        $data = $model->all();
        $title = self::TITLE;
        $tableName = $model->getTable();
        return view('table', compact('data', 'columns', 'title','tableName'));
    }
}