<?php
/**
 * Created by PhpStorm.
 * User: kelin
 * Date: 25/10/2015
 * Time: 10:51 PM
 */

namespace App\Http\Requests;


use App\Game;
use App\Http\Controllers\GameController;

class AjaxGameRequest extends Request
{
    /**
     * Should be an ajax request
     *
     * @return bool
     */
    protected function authorize()
    {
        return $this->ajax();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * Utility method, try to get the student id from session
     *
     * @return string
     */
    public function getStudentIdFromSession()
    {
        return $this->session()->get(GameController::$KEY_CURRENT_STUDENT_ID);
    }

    /**
     * Utility method, try to get the current game entity from session.
     *
     * @return Game
     */
    public function getGameFromSession()
    {
        return $this->session()->get(GameController::$KEY_CURRENT_GAME);
    }
}