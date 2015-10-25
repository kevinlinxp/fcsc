<?php
/**
 * Created by PhpStorm.
 * User: kelin
 * Date: 25/10/2015
 * Time: 10:54 PM
 */

namespace App\Http\Requests;


use App\Http\Controllers\GameController;

class AjaxGuessRequest extends AjaxGameRequest
{

    /**
     * Do not authorise if the current session data are not in correct status.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!parent::authorize()) {
            return false;
        }

        if (!$this->isMethod('post')) {
            return false;
        }

        if (is_null($this->getStudentIdFromSession())) {
            return false;
        }

        $currentGame = $this->getGameFromSession();
        if (is_null($currentGame)) {
            return false;
        }

        if ($currentGame->getRoundCount() >= 6) {
            return false;
            //return response()->json([
            //   'result' => 'error',
            //   'reason' => 'Hey buddy, this game has ended.'
            //]);
        }

        return true;
    }

    // TODO custom validate rules
}