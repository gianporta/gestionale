<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RowTableDeleteController extends Controller
{
    public function deleteRow(Request $request)
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
        $model = $modelClass::find($recordId);
        if (!$model)
            return response()->json(['success' => false, 'message' => 'Record non trovato'], 404);

        $model->delete();

        return response()->json(['success' => true]);
    }
}