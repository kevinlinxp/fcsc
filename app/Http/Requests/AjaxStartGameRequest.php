<?php
/**
 * Created by PhpStorm.
 * User: kelin
 * Date: 25/10/2015
 * Time: 10:54 PM
 */

namespace App\Http\Requests;


class AjaxStartGameRequest extends AjaxGameRequest
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

        return true;
    }

}