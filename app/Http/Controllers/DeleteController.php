<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeleteController extends Controller
{
    public function OnDelete(){
        $result=DB::table('sliders')->truncate();
    }
}
