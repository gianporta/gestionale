<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RowTableCreateController extends Controller
{
    public function createRow(Request $request)
    {
        $validated = $request->validate([
            'table' => 'required|string',
            'data' => 'required|array',
        ]);

        $table = $validated['table'];
        $insertData = $validated['data'];

        $modelMap = config('model_map');

        if (!array_key_exists($table, $modelMap))
            return response()->json(['success' => false, 'message' => 'Tabella non riconosciuta'], 400);

        $modelClass = $modelMap[$table];
        if (!$modelClass)
            return response()->json(['success' => false, 'message' => 'Tabella non riconosciuta'], 400);
        $model = new $modelClass();
        $model->fill($insertData);
        $model->save();

        return response()->json(['success' => true, 'record' => $model]);
    }
}