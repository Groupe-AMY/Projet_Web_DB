<?php
/**
 * Author       :   yannick.baudraz@cpnv.ch
 * Project      :   phpexceptionmanagement - NoConnectionException.php
 * Description  :   [Description]
 *
 * Created      :   04.06.2019
 * Updates      :   [dd.mm.yyyy author]
 *                      [description of update]
 *
 * Git source   :   [git source]
 *
 * Created with PhpStorm.
 */

/**
 * Class NoConnectionException
 */
class NoConnectionException extends Exception
{
    public $messageGUI = "Notre site est en maintenance, merci pour votre compréhension";
    protected $message = "No connection with Database";
}