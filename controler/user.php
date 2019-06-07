<?php
/**
 * Author       :   yannick.baudraz@cpnv.ch
 * Project      :   Projet web DB - user.php
 * Description  :   Controller file for users
 *
 * Created      :   22.05.2019
 * Updates      :   24.05.2019
 *                      created session variable "hasLocations"
 *
 * Git source   :   https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/controler/user.php
 *
 * Created with PhpStorm.
 */

/**
 * This function is designed to manage login request
 *
 * @param array $loginRequest containing login fields required to authenticate the user
 */
function login($loginRequest)
{
    try {//if a login request was submitted
        if (isset($loginRequest['inputUserEmailAddress']) && isset($loginRequest['inputUserPsw'])) {
            //extract login parameters
            $userEmailAddress = $loginRequest['inputUserEmailAddress'];
            $userPsw = $loginRequest['inputUserPsw'];

            //try to check if user/psw are matching with the database
            require_once "model/usersManager.php";
            if (isLoginCorrect($userEmailAddress, $userPsw)) {
                createSession($userEmailAddress);
                if (checkHasLocations($userEmailAddress)) {
                    $_SESSION['hasLocations'] = true;
                }
                $_GET['loginError'] = false;
                $_GET['action'] = "home";
                require "view/home.php";
            } else { //if the user/psw does not match, login form appears again
                $_GET['loginError'] = true;
                $_GET['action'] = "login";
                require "view/login.php";
            }
        } else { //the user does not yet fill the form
            $_GET['action'] = "login";
            require "view/login.php";
        }
    } catch (NoConnectionException $e) {
        $errorConnection = $e->messageGUI;
        writeErrorLog($e->getMessage());
        home($errorConnection);
    }
}

/**
 * This function is designed
 *
 * @param $registerRequest
 */
function register($registerRequest)
{
    try {//variable set
        if (isset($registerRequest['inputUserEmailAddress'])
            && isset($registerRequest['inputUserPsw'])
            && isset($registerRequest['inputUserPswRepeat'])) {

            //extract register parameters
            $userEmailAddress = $registerRequest['inputUserEmailAddress'];
            $userPsw = $registerRequest['inputUserPsw'];
            $userPswRepeat = $registerRequest['inputUserPswRepeat'];

            if ($userPsw == $userPswRepeat) {
                require_once "model/usersManager.php";
                if (registerNewAccount($userEmailAddress, $userPsw)) {
                    createSession($userEmailAddress);
                    $_GET['registerError'] = false;
                    $_GET['action'] = "home";
                    require "view/home.php";
                }
            } else {
                $_GET['registerError'] = true;
                $_GET['action'] = "register";
                require "view/register.php";
            }
        } else {
            $_GET['action'] = "register";
            require "view/register.php";
        }
    } catch (NoConnectionException $e) {
        $errorConnection = $e->messageGUI;
        writeErrorLog($e->getMessage());
        home($errorConnection);
    }
}

/**
 * This function is designed to create a new user session
 *
 * @param $userEmailAddress : user unique id
 */
function createSession($userEmailAddress)
{
    $_SESSION['userEmailAddress'] = $userEmailAddress;
    //set user type in Session
    $userType = getUserType($userEmailAddress);
    $_SESSION['userType'] = $userType;
}

/**
 * This function is designed to manage logout request
 */
function logout()
{
    $_SESSION = [];
    session_destroy();
    $_GET['action'] = "home";
    require "view/home.php";
}