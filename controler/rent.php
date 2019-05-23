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
 * Display the cart page of the user connected
 *
 * @param $display
 */
function displayRent($display)
{
    if (isset($_SESSION['userEmailAddress'])) {
        $userEmail = $_SESSION['userEmailAddress'];
        require 'model/rentManager.php';
        if ($display === 'all') {
            $rentArray = getUSerRent($userEmail);
        } else {
            $cart = $_SESSION['cart'];
            $rentArray = saveRent($cart, $userEmail);
            unset($_SESSION['cart']);
        }
        require 'view/rent.php';
    } else {
        require 'view/login.php';
    }
}