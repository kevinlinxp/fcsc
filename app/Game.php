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

    private $round_count;
    //private $round_start_ts;
    public $round_secret;
    private $round_guessed_count;
    //private $round_provisional_mark;
    private $total_mark;

    function __construct()
    {
        $this->round_count = 0;
        $this->total_mark = 0;
    }

    public function guess($studentGuess)
    {
        $this->round_guessed_count++;
    }

    public function sumThisRound()
    {
        // $round_mark = max($this->round_provisional_mark - (time() - $this->round_start_ts) / (30 * 1000), 0);
        $this->total_mark += (10 - $this->round_guessed_count);
    }

    public function newRound()
    {
        $this->round_count++;
        $this->round_secret = Game::randomString();
        // $this->round_start_ts = Carbon::now();
        // $this->round_provisional_mark = 10;
        $this->round_guessed_count = 0;
    }

    public function getRoundCount () {
        return $this->round_count;
    }

}