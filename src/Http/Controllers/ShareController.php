<?php

namespace TungTT\LaravelMap\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MapShare;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function store(Request $request){
        $user_id = auth()->user()->id;
        $url = $request->post('url');
        $expires_at = $request->post('expires_at');

        $model = MapShare::where('user_id', $user_id)
            ->where('url', $url)
            ->where('expires_at', $expires_at ? Carbon::createFromTimestamp($expires_at) : null)
            ->first() ?: new MapShare();

        $model->fill([
            ...$request->all(),
            'expires_at' => $expires_at ? Carbon::createFromTimestamp($expires_at) : null
        ])->save();

        return [
            'data' => $model
        ];
    }

    public function show($token){
        $model = MapShare::where('token', $token)->firstOrFail();

        return redirect($model->url);
    }
}
