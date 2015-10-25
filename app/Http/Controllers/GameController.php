<?php

namespace App\Http\Controllers;

use App\Game;
use App\Http\Requests\AjaxGameRequest;
use App\Http\Requests\AjaxGuessRequest;
use App\Http\Requests\AjaxStartGameRequest;
use App\Http\Requests\ToGameRequest;
use App\Student;
use Carbon\Carbon;
use App\Http\Requests;

class GameController extends Controller
{
    public static $KEY_CURRENT_STUDENT_ID = "KEY_CURRENT_STUDENT_ID";
    public static $KEY_CURRENT_GAME = "KEY_CURRENT_GAME";

    public static function isGameEnded()
    {
        return Carbon::now()->gt(Carbon::createFromFormat('Y-m-d H:i:s', env('END_DATE')));
    }

    public static function isDebug()
    {
        return env('APP_DEBUG', true);
    }

    public function asyncRanking(AjaxGameRequest $request)
    {
        //highestMark descending order first, and then recordDate ascending order
        //$students = Student::where('highestMark', '>', 0)->orderBy('highestMark', 'DESC')->get();
        $students = Student::where('highestMark', '>', 0)->orderBy('highestMark', 'DESC')->oldest('recordDate')->get();

        //return response()->json($students);
        return $students;
    }

    public function toGame(ToGameRequest $request)
    {
        // TODO put to GameRequest redirect
        if (GameController::isGameEnded()) {
            echo "oops";
            exit();
        }

        $studentId = $request->input('studentId');
        $student = Student::find($studentId);
        $request->session()->put(GameController::$KEY_CURRENT_STUDENT_ID, $studentId);
        return view('game')->with('student', $student);
    }

    public function asyncStartGame(AjaxStartGameRequest $request)
    {
        // TODO put to GameRequest redirect
        if (GameController::isGameEnded()) {
            return response()->json([
                'result' => 'error',
                'reason' => 'Game ended.'
            ]);
        }

        // Retrieve the student entity from database.
        $student = Student::find($request->session()->get(GameController::$KEY_CURRENT_STUDENT_ID));
        // Time check, should be more than 6 hrs since last play
        // $lastPlayed = Carbon::parse($student['lastPlayed']);
        // if (Carbon::now()->diffInSeconds($lastPlayed) <= 16) {
        //     return response()->json([
        //         'result' => 'limited',
        //         'reason' => 'not time yet'
        //     ]);
        // }
        $student['lastPlayed'] = Carbon::now();
        $student->save();

        $currentGame = new Game();
        $request->session()->put(GameController::$KEY_CURRENT_GAME, $currentGame);
        $currentGame->newRound();

        return response()->json([
            'result' => 'roundStarted',
            'roundData' => [
                'roundCount' => $currentGame->getRoundCount()
            ]
        ]);
    }

    public function asyncGuess(AjaxGuessRequest $request)
    {
        if (GameController::isGameEnded()) {
            return response()->json([
                'result' => 'error',
                'reason' => 'Game ended.'
            ]);
        }

        $guess = $request->input('guess');
        $studentId = $request->getStudentIdFromSession();
        $currentGame = $request->getGameFromSession();

        $guessResult = $currentGame->guess($guess);
        $roundCount = $currentGame->getRoundCount();
        $guessCount = $currentGame->getRoundGuessCount();
        $correctness = $currentGame->getCorrectness();
        $roundPoints = 0;
        $totalPoints = $currentGame->getTotalPoints();

        if ($correctness || ($roundCount < 5 && $guessCount == 10)) {
            $currentGame->completeCurrentRound();
            $roundPoints = $currentGame->getRoundPoints();
            $totalPoints = $currentGame->getTotalPoints();
            $currentGame->newRound();

            // update points record and find out ranking info.
            // Retrieve the student entity from database.
            $student = Student::find($studentId);
            if (!$student) {
                return response()->json([
                    'result' => 'error',
                    'reason' => 'student data do not exist.'
                ]);
            }
            if ($student['highestMark'] < $totalPoints) {
                // update
                if (!in_array($studentId, ['a1203212', 'a1165070'])) {
                    $student['highestMark'] = $totalPoints;
                    $student['recordDate'] = Carbon::now();
                    $student->save();
                }
            }
        }

        $jsonResponse = [
            'result' => 'guessResult',
            'roundData' => [
                'roundCount' => $roundCount,
                'resultText' => $guessResult,
                'guessCount' => $guessCount,
                'roundPoints' => $roundPoints,
                'totalPoints' => $totalPoints,
                'correctness' => $correctness
            ]
        ];

        if (GameController::isDebug()) {
            $jsonResponse['roundData']['secret'] = $currentGame->getRoundSecret();
        }

        return response()->json($jsonResponse);
    }
}
