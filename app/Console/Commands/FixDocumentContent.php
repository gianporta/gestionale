<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDocumentContent extends Command
{
    protected $signature = 'fix:document-content';
    protected $description = 'Convert serialized content to JSON for documenti';

    public function handle()
    {
        $this->info('Start fixing content...');

        DB::table('documenti')
            ->whereNotNull('content')
            ->orderBy('id')
            ->chunk(100, function ($rows) {

                foreach ($rows as $row) {

                    $content = $row->content;

                    // già JSON → skip
                    if (is_string($content) && str_starts_with(trim($content), '['))
                        continue;

                    $data = @unserialize($content);

                    if (!is_array($data))
                        continue;

                    $clean = [];

                    foreach ($data as $item) {
                        $clean[] = [
                            'descrizione' => $item['descrizione'] ?? null,
                            'ore' => isset($item['ore']) && $item['ore'] !== '' ? (float)$item['ore'] : null,
                            'costo' => isset($item['costo']) && $item['costo'] !== '' ? (float)$item['costo'] : null,
                            'imponibile' => isset($item['imponibile']) ? (float)$item['imponibile'] : 0,
                        ];
                    }

                    DB::table('documenti')
                        ->where('id', $row->id)
                        ->update([
                            'content' => json_encode($clean)
                        ]);
                }
            });

        $this->info('Done.');
    }
}
