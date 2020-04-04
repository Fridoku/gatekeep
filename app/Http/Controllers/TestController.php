<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Gate;

class TestController extends Controller
{
    public function root(Request $request){
        $errors = ['te st', 'foo', 'bar'];
        return $this->quickView('templates.base');
    }

}
