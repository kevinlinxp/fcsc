<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    public function start(Request $request) {
        if ($request->isMethod('post')) {
            $id = $request->input('id');
            $student = Student::find($id);
            if ($student) {
                return view('game')->with('student', $student);
            } else {
                echo "oops";
                exit();
            }
        }
    }
}
