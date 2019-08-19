<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface ImageUpload
{
    public function uploadImage(Request $request);
}
