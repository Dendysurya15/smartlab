<?php


use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
use App\Jobs\Generatebulkpdfpr;

if (!function_exists('startExport')) {
    function startExport(Collection $records)
    {

        $recordIds = [];
        $totalItems = 0;

        $records->each(function ($record) use (&$recordIds, &$totalItems) {
            if ($record->status !== 'Draft' && $record->status !== 'Rejected') {
                $recordIds[] = $record->id;
                $totalItems++;
            }
        });

        $data = implode('$', $recordIds);
        $filename = 'kupa_export_' . now()->format('Y-m-d_H-i-s');

        // Show initial notification
        Notification::make()
            ->title('Export Started')
            ->body("Processing {$totalItems} items. Please wait...")
            ->success()
            ->send();

        $random_task_id = generateRandomString(10) . date('YmdHis');

        Generatebulkpdfpr::dispatch($data, $filename, auth()->id(), $totalItems, $random_task_id);
    }
}
