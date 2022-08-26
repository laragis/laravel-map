<?php

namespace TungTT\LaravelMap\MenuItemTypes;

use TungTT\LaravelMap\Models\MapLayer;
use Laravel\Nova\Fields\Boolean;
use Outl1ne\MenuBuilder\MenuItemTypes\MenuItemSelectType;

class LayerMenuItemType extends MenuItemSelectType
{
    public static function getIdentifier(): string
    {
        return 'layer';
    }

    public static function getName(): string
    {
        return 'Layer';
    }

    public static function getOptions($locale): array {
        return MapLayer::where('group', 'layer')->pluck('title', 'id')->all();
    }

    public static function getDisplayValue($value, ?array $data, $locale) {
        $name = data_get(MapLayer::find($value), 'title');
        $selected = $data['selected'];
        return $value.") {$name} ".($selected ? "(selected)" : "");
    }

    public static function getFields(): array
    {
        return [
            Boolean::make('Selected', 'selected'),
        ];
    }


    public static function getData($data = null)
    {
        if(isset($data['selected'])) $data['selected'] = !!$data['selected'];

        return parent::getData($data);
    }
}
