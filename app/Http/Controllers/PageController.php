<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PageController extends Controller
{

    public function home() {
        return view("home");
    }

    public function contact() {

        $name = 'Keith <span style="color:red">Lin</span>';
        $first = 'Keith';
        $last = '<span style="color:green">Lin</span>';

        $dob = "12";

        return view("pages.contact")->with([
            'first'=>$first, 'last'=>$last, 'name'=>$name
        ]);
    }

    public function about() {
        return view('pages.about');
    }
}