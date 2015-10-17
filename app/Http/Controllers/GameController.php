<?php

namespace App\Http\Controllers;

use App\Game;
use App\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;

class GameController extends Controller
{
    public static $KEY_CURRENT_STUDENT_ID = "KEY_CURRENT_STUDENT_ID";
    public static $KEY_CURRENT_GAME = "KEY_CURRENT_GAME";

    public function prepare(Request $request)
    {
        if ($request->isMethod('get')) {
            $studentId = $request->input('studentId');
            $student = Student::find($studentId);
            if ($student) {
                $request->session()->put(GameController::$KEY_CURRENT_STUDENT_ID, $studentId);
                return view('game')->with('student', $student);
            } else {
                // TODO redirect
                echo "oops";
                exit();
            }
        }
    }

    public function asyncStartGame(Request $request)
    {
        // Must be ajax post request
        if (!$request->ajax() || !$request->isMethod('post')) {
            // TODO give error response
            echo "oops";
            exit();
        }

        // Retrieve the current student id from session.
        $studentId = $request->session()->get(GameController::$KEY_CURRENT_STUDENT_ID);
        if (!$studentId) {
            return response()->json([
                'result' => 'error',
                'reason' => 'session expired.'
            ]);
        }

        // Retrieve the student entity from database.
        $student = Student::find($studentId);
        if (!$student) {
            return response()->json([
                'result' => 'error',
                'reason' => 'student data do not exist.'
            ]);
        }

        // Time check, should be more than 6 hrs since last play
        $lastPlayed = Carbon::parse($student['lastPlayed']);
        if (Carbon::now()->diffInSeconds($lastPlayed) <= 16) {
            return response()->json([
                'result' => 'limited',
                'reason' => 'not time yet'
            ]);
        }
        $student['lastPlayed'] = Carbon::now();
        $student->save();

        $currentGame = new Game();
        $request->session()->put(GameController::$KEY_CURRENT_GAME, $currentGame);
        $currentGame->newRound();

        return response()->json([
            'result' => 'roundStarted',
            'roundData' => [
                'roundCount' => $currentGame->getRoundCount(),
                'secret' => $currentGame->round_secret
            ]
        ]);
    }

    public function asyncGuess(Request $request)
    {
        // Must be ajax post request
        if (!$request->ajax() || !$request->isMethod('post')) {
            // TODO give error response
            echo "oops";
            exit();
        }

        // Retrieve the current student id from session.
        $studentId = $request->session()->get(GameController::$KEY_CURRENT_STUDENT_ID);
        if (!$studentId) {
            return response()->json([
                'result' => 'error',
                'reason' => 'session expired.'
            ]);
        }

        $guess = $request->input('guess');
        $currentGame = $request->session()->get(GameController::$KEY_CURRENT_GAME);

        $guessResult = $currentGame->guess($guess);

    }
}
