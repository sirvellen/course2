<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExceptionController extends Controller
{
    public function index()
    {
        throw new \App\Exceptions\TaskException('Something Went Wrong.');
    }
}
