<?php
/**
 * Created by PhpStorm.
 * User: Pascal.BENZONANA
 * Date: 08.05.2017
 * Time: 09:10
 * Updated by : 12-MAR-2019 - nicolas.glassey
 *                  Add register function
 *              13.05.2019 alexandre.fontes@cpnv.ch
 *                  Redirection according to the verification for the location in the cart
 *              15.05.2019 alexandre.fontes@cpnv.ch
 *                  Update updateCartRequest() for the parameter $update
 *              16.05.2019 alexandre.fontes@cpnv.ch
 *                  Quantity verification for change a location
 *              17.05.2019 alexandre.fontes@cpnv.ch
 *                  Comment the code
 * Git source : https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/controler/controler.php
 */

/**
 * This function is designed to redirect the user to the home page (depending on the action received by the index)
 */
function home()
{
    $_GET['action'] = "home";
    require "view/home.php";
}

/**
 * This function is designed to manage login request
 *
 * @param array $loginRequest containing login fields required to authenticate the user
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
 * This function is designed
 *
 * @param $registerRequest
 */
function register($registerRequest)
{
    //variable set
    if (isset($registerRequest['inputUserEmailAddress']) && isset($registerRequest['inputUserPsw']) && isset($registerRequest['inputUserPswRepeat'])) {

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
    $_SESSION = array();
    session_destroy();
    $_GET['action'] = "home";
    require "view/home.php";
}

/**
 * This function is designed to display Snows
 * There are two different view available.
 * One for the seller, an other one for the customer.
 */
function displaySnows()
{
    if (isset($_POST['resetCart'])) {
        unset($_SESSION['cart']);
    }

    require_once "model/snowsManager.php";
    $snowsResults = getSnows();

    $_GET['action'] = "displaySnows";
    if (isset($_SESSION['userType'])) {
        switch ($_SESSION['userType']) {
            case 1://this is a customer
                require "view/snows.php";
                break;
            case 2://this a seller
                require "view/snowsSeller.php";
                break;
            default:
                require "view/snows.php";
                break;
        }
    } else {
        require "view/snows.php";
    }
}

/**
 * This function is designed to get only one snow results (for aSnow view)
 *
 * @param $snow_code
 */
function displayASnow($snow_code)
{
    if (isset($registerRequest['inputUserEmailAddress'])) {
        //TODO
    }
    require_once "model/snowsManager.php";
    $snowsResults = getASnow($snow_code);
    require "view/aSnow.php";
}

/**
 * This function is designed to display the cart
 * @param bool $error
 */
function displayCart($error = false)
{
    $_GET['action'] = "cart";
    require "view/cart.php";
}

/**
 * This function is designed to manage the snow leasing request
 * @param $snowCode
 * @param bool $error
 */
function snowLeasingRequest($snowCode, $error = false)
{
    require "model/snowsManager.php";
    $snowsResults = getASnow($snowCode);
    $_GET['action'] = "snowLeasingRequest";
    require "view/snowLeasingRequest.php";
}

/**
 * This function designed to manage all request impacting the cart content
 *
 * @param $snowCode
 * @param $update
 * @param $delete
 * @param $snowLocationRequest
 */
function updateCartRequest($snowCode, $update, $delete, $snowLocationRequest)
{
    if (!isset($_SESSION['cart'])) {
        $cart = array();
    } else {
        $cart = $_SESSION['cart'];
    }

    require "model/cartManager.php";
    if ($delete === null) {
        $cartArrayTemp = updateCart($cart, $snowCode, $snowLocationRequest['inputQuantity'], $snowLocationRequest['inputDays'], $update);
        if ($cartArrayTemp != null) {
            $_SESSION['cart'] = $cartArrayTemp;
            $_GET['action'] = "displayCart";
            displayCart();
        } else if ($update !== null) {
            displayCart(true);
        } else {
            snowLeasingRequest($snowCode, true);
        }
    } else {
        $cartArrayTemp = deleteLocation($cart, $delete);
        $_SESSION['cart'] = $cartArrayTemp;
        $_GET['action'] = "displayCart";
        displayCart();
    }
}

/**
 * Take the rent of the user connected
 */
function getRent()
{
    // TODO
}

/**
 * Display the cart page of the user connected
 */
function displayRent()
{
    // TODO
}