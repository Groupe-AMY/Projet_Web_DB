<?php
/**
 * Author       :   yannick.baudraz@cpnv.ch
 * Project      :   App - adverts.php
 * Description  :   [Description here]
 * Created      :   22.05.2019
 *
 * Updates      :   [dd.mm.yyyy author]
 *                  [description of update]
 * Git source   :   bitbucket.org/YannickClifford/gestiondesprestations
 * Created with PhpStorm.
 */

/**
 * This function is designed to display the cart
 *
 * @param bool $error
 */
function displayCart($error = false)
{
    $_GET['action'] = "cart";
    require "view/cart.php";
}

/**
 * This function is designed to manage the snow leasing request
 *
 * @param      $snowCode
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