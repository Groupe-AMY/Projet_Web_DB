<?php
/**
 * Author       :   yannick.baudraz@cpnv.ch
 * Project      :   Projet-Web DB - fileManager.php
 * Description  :   [Description]
 *
 * Created      :   05.06.2019
 * Updates      :   [dd.mm.yyyy author]
 *                      [description of update]
 *
 * Git source   :   [git source]
 *
 * Created with PhpStorm.
 */

/**
 * This function is designed to append a path with the fileName received as parameter
 * -The path will be found by the function
 *
 * @param $fName : The file name to be append to the path
 * @return  [String] full path to the log file expressed as a string
 * @example File Name : testFile.log / after function : [pathToFile]\testFile.log
 */
function setFullPath($fName)
{
    /* Help
        get current directory -> http://php.net/manual/en/function.getcwd.php
    */

    $currentPath = getcwd();

    $tempPath = $currentPath . "\\" . $fName;

    return $tempPath;
}

/**
 * This function is designed to write a string message in a file.
 * -The opening and closing action is managed by the function
 *
 * @param $fileFullPath : The path containing expressing the path from the root to the filename
 * @param $lineToWrite  : Is the content to write in the file.
 */
function writeMsgInFile($fileFullPath, $lineToWrite)
{
    // Open the file
    $myFile = fopen($fileFullPath, 'a');

    // Write one the file
    fwrite($myFile, $lineToWrite . "\n");

    // Close the file
    fclose($myFile);
}

/**
 * This function is designed to prepare the message to be written in the log
 *
 * @param $msg         : Contents the message
 * @param $levelNumber : Contents the level of message ("Warning", "Info",...)
 * @return string : Gets the message ready to be written
 * @example INPUT : $msg = "My message"
 *                     INPUT : $levelNumber = 1
 *                     OUTPUT: TIMESTAMP   Info MyMessage
 *
 *          TIMESTAMP FORMAT : 2018-01-30 12:15:59
 *
 *          The separator between each fields is the tab ("\t")
 */
function prepareMsgToWrite($msg)
{
    date_default_timezone_set('Europe/Zurich');
    $timeStamp = date("o-d-m H:i:s");   // Obtenir le timeStamp actuel
    $fullMsg = $timeStamp . "\t\t" . $msg;  // Concatener dans une variable le timeStamp,

    return $fullMsg;
}

/**
 * Appends the error in a log file
 *
 * @param string $message
 */
function writeErrorLog($message)
{
    //<editor-fold desc="private attributes"> //add region in phpstorm -> https://blog.jetbrains.com/phpstorm/2012/03/new-in-4-0-custom-code-folding-regions/
    $logName = "/log/error.log";//define log file name
    $fileFullPath = setFullPath($logName);//define the full path until the log file
    //    $logHeader = "TimeStamp\t\t\t\tMessage";//set the header of the future log file
    //</editor-fold>

    //create file and set header
    $fullMsg = prepareMsgToWrite($message);
    writeMsgInFile($fileFullPath, $fullMsg);
}
