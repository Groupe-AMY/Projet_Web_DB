<?php
/**
 * Author   : alexandre.fontes@cpnv.ch
 * Project  : Projet web BD
 * Created  : 03.05.2019
 *
 * Last update :    05.03.2019 alexandre.fontes@cpnv.ch
 *                    function isLoginCorrect() added
 * Git source  :    https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/view/controler.php
 */

/**
 * This function is designed to redirect the user to the home page (depending on the action received by the index)
 */
function home(){
    $_GET['action'] = "home";
    require "view/home.php";
}

/**
 * This function is designed to manage a register request
 *
 * @param $registerRequest : containing register fields required to register the user
 */
function register($registerRequest)
{
    if (isset($registerRequest['inputUserEmailAddress']) && isset($registerRequest['inputUserPsw'])
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
            } else {
                $_GET['registerError'] = true;
                $_GET['action'] = "register";
                require "view/register.php";
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
}
/**
 * This function is designed to manage the login request
 *
 * @param $loginRequest : containing login fields required to authenticate the user
 */

function login($loginRequest)
{
    //if a login request was submitted
    if (isset($loginRequest['inputUserEmailAddress']) && isset($loginRequest['inputUserPsw'])) {
        //extract login parameters
        $userEmailAddress = $loginRequest['inputUserEmailAddress'];
        $userPsw = $loginRequest['inputUserPsw'];

        //try to check if user/psw are matching with the database
        require_once "model/usersManager.php";
        if (isLoginCorrect($userEmailAddress, $userPsw)) {
            createSession($userEmailAddress);
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
}

/**
 * This function is designed to create a new user session
 *
 * @param $userEmailAddress : user unique id
 */
function createSession($userEmailAddress)
{
    $_SESSION['userEmailAddress'] = $userEmailAddress;
}

/**
 * This function is designed to manage the logout request
 */
function logout()
{
    $_SESSION = [];
    session_destroy();
    $_GET['action'] = "home";
    require "view/home.php";
}
//region users management
//endregion

//region courses management
//endregion