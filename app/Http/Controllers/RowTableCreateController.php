<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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
        if (!Schema::hasTable($table))
            return response()->json(['success' => false, 'message' => 'Tabella inesistente nel database'], 404);

        $modelClass->fill($insertData);
        $modelClass->save();

        return response()->json(['success' => true, 'record' => $modelClass]);
    }

    public function duplicateRow(Request $request)
    {
        $validated = $request->validate([
            'table' => 'required|string',
            'id' => 'required|integer'
        ]);

        $table = $validated['table'];
        $recordId = $validated['id'];
        $modelMap = config('model_map');

        if (!array_key_exists($table, $modelMap))
            return response()->json(['success' => false, 'message' => 'Tabella non riconosciuta'], 400);
        $modelClass = $modelMap[$table];

        if (!Schema::hasTable($table))
            return response()->json(['success' => false, 'message' => 'Tabella inesistente nel database'], 404);

        $model = $modelClass::find($recordId);
        if (!$model)
            return response()->json(['success' => false, 'message' => 'Record non trovato'], 404);
        $newRecord = $model->replicate();
        $newRecord->push();

        return response()->json(['success' => true, 'record' => $newRecord]);
    }
}