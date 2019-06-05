<?php
/**
 * This php file is designed to manage all operations regarding user's management
 * Author   : nicolas.glassey@cpnv.ch
 * Project  : Code
 * Created  : 31.01.2019 - 18:40
 *
 * Last update :    [01.12.2018 author]
 *                  [add $logName in function setFullPath]
 * Source       :   pascal.benzonana
 */

/**
 * This function is designed to verify user's login
 * @param $userEmailAddress
 * @param $userPsw
 * @return bool : "true" only if the user and psw match the database. In all other cases will be "false".
 */
function isLoginCorrect($userEmailAddress, $userPsw){
    $result = false;

    $strSeparator = '\'';
    $loginQuery = 'SELECT userHashPsw FROM users WHERE userEmailAddress = '. $strSeparator . $userEmailAddress . $strSeparator;

    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($loginQuery);

    if (count($queryResult) == 1)
    {
        $userHashPsw = $queryResult[0]['userHashPsw'];
        $result = password_verify($userPsw, $userHashPsw);
    }
    return $result;
}

/**
 * This function is designed to register a new account
 * @param $userEmailAddress
 * @param $userPsw
 * @return bool|null
 */
function registerNewAccount($userEmailAddress, $userPsw){
    $result = false;

    $strSeparator = '\'';

    $userHashPsw = password_hash($userPsw, PASSWORD_DEFAULT);

    $registerQuery = 'INSERT INTO users (`userEmailAddress`, `userHashPsw`) VALUES (' .$strSeparator . $userEmailAddress .$strSeparator . ','.$strSeparator . $userHashPsw .$strSeparator. ')';

    require_once 'model/dbConnector.php';
    $queryResult = executeQueryInsert($registerQuery);
    if($queryResult){
        $result = $queryResult;
    }
    return $result;
}

/**
 * This function is designed to get the type of user
 * For the webapp, it will adapt the behavior of the GUI
 * @param $userEmailAddress
 * @return int (1 = customer ; 2 = seller)
 */
function getUserType($userEmailAddress){
    $result = 0;//we fix the result to 0 -> customer

    $getUserTypeQuery = "SELECT userType FROM users WHERE userEmailAddress = '$userEmailAddress'";

    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getUserTypeQuery);

    if (count($queryResult) == 1){
        $result = $queryResult[0]['userType'];
    }

    return $result;
}

/**
 * Take the ID of the user by his email
 *
 * @param $userEmailAddress
 * @rekturn array|null
 * @return array|null
 */
function getUserID($userEmailAddress){
    $getUserIDQuery = "SELECT id From users WHERE userEmailAddress = '$userEmailAddress'";
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getUserIDQuery);
    return $queryResult;
}

/**
 * Check if the user connected has location(s)
 *
 * @param string $userEmail : Email of the user
 * @return bool $result
 */
function checkHasLocations($userEmail)
{
    require_once 'model/dbConnector.php';
    $userID = getUserID($userEmail)[0]['id'];
    $query = "SELECT id FROM rents WHERE fk_userId = ". $userID;
    if (executeQuerySelect($query)) {
        $result = true;
    } else {
        $result = false;
    }

    return $result;
}
