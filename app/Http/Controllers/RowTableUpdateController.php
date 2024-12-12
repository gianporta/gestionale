<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RowTableUpdateController extends Controller
{
    public function updateRow(Request $request)
    {
        $validated = $request->validate([
            'table' => 'required|string',
            'id' => 'required|integer',
            'data' => 'required|array',
        ]);

        $table = $validated['table'];
        $recordId = $validated['id'];
        $updateData = $validated['data'];
        $modelMap = config('model_map');

        if (!array_key_exists($table, $modelMap))
            return response()->json(['success' => false, 'message' => 'Tabella non riconosciuta'], 400);

        $modelClass = $modelMap[$table];

        if (!Schema::hasTable($table))
            return response()->json(['success' => false, 'message' => 'Tabella inesistente nel database'], 404);

        $model = $modelClass::find($recordId);
        if (!$model)
            return response()->json(['success' => false, 'message' => 'Record non trovato'], 404);
        foreach ($updateData as $column => $value) {
            if ($column === 'id')
                continue;
            if (Schema::hasColumn($table, $column))
                $model->$column = $value;
        }

        $model->save();

        return response()->json(['success' => true]);
    }
}