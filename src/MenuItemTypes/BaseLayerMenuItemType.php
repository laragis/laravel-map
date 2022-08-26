<?php

namespace TungTT\LaravelMap\MenuItemTypes;

use TungTT\LaravelMap\Models\MapLayer;
use Illuminate\Validation\Rule;
use Outl1ne\MenuBuilder\MenuBuilder;
use Outl1ne\MenuBuilder\MenuItemTypes\MenuItemSelectType;

class BaseLayerMenuItemType extends MenuItemSelectType
{
    public static function getIdentifier(): string
    {
        return 'base-layer';
    }

    public static function getName(): string
    {
        return 'Base Layer';
    }

    public static function getOptions($locale): array {
        return MapLayer::where('group', 'baselayer')->pluck('title', 'id')->all();
    }

    public static function getDisplayValue($value, ?array $data, $locale) {
        $name = data_get(MapLayer::find($value), 'title');
        return $value.") {$name}";
    }

    public static function getRules(): array
    {
        return [
            'class' => \Illuminate\Validation\Rule::unique(MenuBuilder::getMenuItemsTableName())
                ->where(
                    fn ($query) => $query
                        ->whereRaw(request()->get('created_at') ? '1=0' : '1=1')
                        ->where('class', BaseLayerMenuItemType::class)
                        ->where('menu_id', request()->get('menu_id'))->get()
                ),
            'nestable' => Rule::in([false])
        ];
    }

    public static function getValue($value, ?array $data, $locale)
    {
        return parent::getValue($value, $data, $locale);
    }

    public static function getData($data = null)
    {
        return parent::getData($data);
    }
}
