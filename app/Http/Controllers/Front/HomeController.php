<?php

namespace App\Http\Controllers\Front;

class HomeController extends BaseController
{
    public function index()
    {
        return view('welcome');
    }
}
