<?php

namespace TungTT\LaravelMap\Imports;

use App\Models\MapBookmark;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MapBookmarkImport implements ToModel, WithValidation, WithHeadingRow
{
    public function model(array $row)
    {
        return new \TungTT\LaravelMap\Models\MapBookmark([
            'title' => $row['title'],
            'description' => $row['description'],
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [$row['lng'], $row['lat']]
            ],
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
            ],
            'lat' => [
                'required',
                'numeric',
            ],
            'lng' => [
                'required',
                'numeric',
            ],
        ];
    }
}
