<?php

namespace TungTT\LaravelMap\MenuItemTypes;

use Laravel\Nova\Fields\Boolean;
use Outl1ne\MenuBuilder\MenuItemTypes\BaseMenuItemType;

class LayerGroupMenuItemType extends BaseMenuItemType
{
    public static function getIdentifier(): string
    {
        return 'layer-group';
    }

    public static function getName(): string
    {
        return 'Layer Group';
    }

    public static function getType(): string
    {
        return 'text';
    }

    public static function getFields(): array
    {
        return [
            Boolean::make('Selected', 'selected'),
            Boolean::make('Expanded', 'expanded'),
        ];
    }

    public static function getRules(): array
    {
        return [
//            'selected' => 'in:1',
//            'expanded' => 'in:1',
        ];
    }

    public static function getData($data = null)
    {
        if(isset($data['selected'])) $data['selected'] = !!$data['selected'];
        if(isset($data['expanded'])) $data['expanded'] = !!$data['expanded'];

        return parent::getData($data);
    }
}
