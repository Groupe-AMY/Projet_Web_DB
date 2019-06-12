<?php
/**
 * Author       :   yannick.baudraz@cpnv.ch
 * Project      :   Projet web DB - rent.php
 * Description  :   Controller file for rents
 *
 * Created      :   22.05.2019
 * Updates      :   [dd.mm.yyyy author]
 *                      [description of update]
 *
 * Git source   :   https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/controler/rent.php
 *
 * Created with PhpStorm.
 */

/**
 * Display the cart page of the user connected
 *
 * @param null|string $wayToDisplay : The way to display the rent. Can be NULL or "all"
 */
function displayRent($wayToDisplay)
{
    try {
        if (isset($_SESSION['userEmailAddress'])) { // The user is logged
            $userEmail = $_SESSION['userEmailAddress'];
            require 'model/rentManager.php';
            if ($wayToDisplay === 'all') { // Display all the snows
                $rentArray = getUSerRent($userEmail);
            } else { // Save a leasing's request and display the new location
                $cart = $_SESSION['cart'];
                $rentArray = saveRent($cart, $userEmail);
                unset($_SESSION['cart']);
                $_SESSION['hasLocations'] = true;
            }
            require 'view/rent.php';
        } else {
            require 'view/login.php';
        }
    } catch (NoConnectionException $e) {
        $errorConnection = $e->messageGUI;
        writeErrorLog($e->getMessage());
        home($errorConnection);
    }
}