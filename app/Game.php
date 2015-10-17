<?php
/**
 * Created by PhpStorm.
 * User: kelin
 * Date: 16/10/2015
 * Time: 11:41 AM
 */

namespace App;


use Carbon\Carbon;

class Game
{

    private static function randomString()
    {
        $result = [];
        while (sizeof($result) != 4) {
            while (in_array($randomNum = rand(0, 9), $result)) ;
            $result[] = "" . $randomNum;
        }
        return implode("", $result);
    }

    public $round_secret;

    private $round_count;
    private $round_start_ts;
    private $round_guessed_count;
    private $round_points;
    private $total_points;
    private $round_correctness;

    function __construct()
    {
        $this->round_count = 0;
        $this->total_points = 0;
    }

    public function guess($studentGuess)
    {
        $this->round_guessed_count++;

        $A = 0;
        $B = 0;

        for ($i = 0; $i < 4; $i++) {
            $index = strpos($this->round_secret, $studentGuess{$i});
            //if(is_numeric($index)){
            //    continue;
            //}
            if ($index === $i) {
                $A++;
            } else if(is_numeric($index)) {
                $B++;
            }
//            if (!$index ) {
//                if ($index == $i) {
//                    $A++;
//                } elseif ($index >= 0) {
//                    $B++;
//                }
//            }
        }

        $this->round_correctness = ($A == 4);

        return $A . 'A' . $B . 'B';
    }

    public function getRoundPoints(){
        return $this->round_points;
    }

    public function getTotalPoints(){
        return $this->total_points;
    }

    public function getCorrectness(){
        return $this->round_correctness;
    }

    public function getRoundGuessCount() {
        return $this->round_guessed_count;
    }

    public function completeCurrentRound()
    {
        $this->round_points = (10 - $this->round_guessed_count + 1);

        $elapsedSeconds = Carbon::now()->diffInSeconds($this->round_start_ts);
        $this->round_points = max($this->round_points - floor($elapsedSeconds / 30), 0);

        $this->total_points += $this->round_points;
    }

    public function newRound()
    {
        $this->round_count++;
        $this->round_secret = Game::randomString();
        $this->round_start_ts = Carbon::now();
        $this->round_points = 0;
        $this->round_guessed_count = 0;
    }

    public function getRoundCount()
    {
        return $this->round_count;
    }

}