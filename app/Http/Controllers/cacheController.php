<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class cacheController extends Controller
{
    function index()
    {
        return view('welcome');
    }

    function borrarCache()
    {
        $exitCode = Artisan::call('route:cache');

        $exitCode = Artisan::call('config:cache');

        $exitCode = Artisan::call('cache:clear');

        $exitCode = Artisan::call('view:clear');
        return 'View cache cleared';
    }
    function manifiest(){
        return [
            'name' => config('app.name'),
            'gcm_sender_id' => config('webpush.gcm.sender_id')
        ];
    }
}
