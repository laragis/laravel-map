<?php

namespace TungTT\LaravelMap\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\DefaultValueBinder;
use TungTT\LaravelMap\Models\MapBookmark;

class MapBookmarkExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithCustomValueBinder
{
    use Exportable;

    public function collection()
    {
        return MapBookmark::where('user_id', auth()->user()?->id)->get()->map(fn($model) => [
            'id' => $model->id,
            'title' => $model->title,
            'description' => $model->description,
            'lat' => data_get($model->geometry, 'coordinates.1'),
            'lng' => data_get($model->geometry, 'coordinates.0'),
        ]);
    }

    public function headings(): array
    {
        return [
            'id',
            'title',
            'description',
            'lat',
            'lng',
        ];
    }
}
