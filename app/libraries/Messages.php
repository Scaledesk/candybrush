<?php
/**
 * Created by PhpStorm.
 * User: tushar
 * Date: 12/10/15
 * Time: 12:06 PM
 */

namespace app\libraries;


class Messages {
    /*
     * callable messages method
     */
    public static function showErrorMessages($validator){
        $messages = $validator->messages();
        $errors=[];
        foreach ($messages->all() as $message)
        {
            array_push($errors,$message);
        }
        return $errors;
    }
}