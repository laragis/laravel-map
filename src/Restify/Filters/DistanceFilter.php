<?php
namespace TungTT\LaravelMap\Restify\Filters;

use Binaryk\LaravelRestify\Filters\AdvancedFilter;
use Binaryk\LaravelRestify\Http\Requests\RestifyRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class DistanceFilter extends AdvancedFilter
{
    public function filter(RestifyRequest $request, Relation|Builder $query, $value)
    {

    }

    public function rules(Request $request): array
    {
        return [
            'center' => 'required',
            'radius' => 'required'
        ];
    }

};

