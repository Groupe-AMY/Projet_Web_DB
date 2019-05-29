<?php
/**
 * Author       :   yannick.baudraz@cpnv.ch
 * Project      :   Projet web DB - cart.php
 * Description  :   Controller file for the cart
 *
 * Created      :   22.05.2019
 * Updates      :   [dd.mm.yyyy author]
 *                      [description of update]
 *
 * Git source   :   https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/controler/cart.php
 *
 * Created with PhpStorm.
 */

/**
 * This function is designed to display the cart
 *
 * @param bool $error : True if there is an error to display in the web page, else false.
 */
function displayCart($error = false)
{
    $_GET['action'] = "cart";
    require "view/cart.php";
}

/**
 * This function is designed to manage the snow leasing request
 *
 * @param string|int $snowCode
 * @param bool       $error : True if there is an error to display in the web page, else false.
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
 * @param string|int  $snowCode
 * @param null|string $update
 * @param null|string $delete
 * @param array       $snowLocationRequest
 */
function updateCartRequest($snowCode, $update, $delete, $snowLocationRequest)
{
    if (!isset($_SESSION['cart'])) {
        $cart = [];
    } else {
        $cart = $_SESSION['cart'];
    }

    require "model/cartManager.php";
    if ($delete === NULL) { // Add or update a leasing's request
        $cartArrayTemp = updateCart(
              $cart,
              $snowCode,
              $snowLocationRequest['inputQuantity'],
              $snowLocationRequest['inputDays'],
              $update
        );

        if ($cartArrayTemp != NULL) {
            $_SESSION['cart'] = $cartArrayTemp;
            $_GET['action'] = "displayCart";
            displayCart();
        } elseif ($update !== NULL) {
            displayCart(true);
        } else {
            snowLeasingRequest($snowCode, true);
        }
    } else { // Delete a leasing's request
        if ($cartArrayTemp = deleteLocation($cart, $delete)) { // The cart still have element(s)
            $_SESSION['cart'] = $cartArrayTemp;
            $_GET['action'] = "displayCart";
            displayCart();
        } else { // The cart doesn't have any leasing's request
            require_once 'controler/snow.php';
            $_GET['action'] = "displaySnows";
            $_SESSION['cart'] = [];
            displaySnows();
        }
    }
}