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
 * Displays all rents made on the website
 */
function displaySellersRent()
{
    try {
        if (isset($_SESSION['userType']) && $_SESSION['userType'] == 1) {
            require_once "model/sellersManager.php";
            $sellerRentArray = getAllSellerRents();
            require "view/sellerOverview.php";
        } else {
            home();
        }
    } catch (NoConnectionException $e) {
        $errorConnection = $e->messageGUI;
        writeErrorLog($e->getMessage());
        home($errorConnection);
    }

}

/**
 * Displays the contents of a certain rent
 * @param int|string $rentId
 */
function displayOneSellerRent($rentId)
{
    try {
        require_once "model/sellersManager.php";
        $rentArray = getOneSellerRent($rentId);
        $rentDetailsArray = getSellerRentDetails($rentId);
        require "view/sellerManagerLocation.php";
    } catch (NoConnectionException $e) {
        $errorConnection = $e->messageGUI;
        writeErrorLog($e->getMessage());
        home($errorConnection);
    }
}

/**
 * This function make the process to update a detail for a rent
 *
 * @param array $updateRequestPost The request for the update, from a form in POST.
 */
function updateSellerDetailRentProcess($updateRequestPost)
{
    try {
        require_once "model/sellersManager.php";
        updateSellerDetailsRent($updateRequestPost);
        displayOneSellerRent($updateRequestPost['rentID']);
    } catch (NoConnectionException $e) {
        $errorConnection = $e->messageGUI;
        writeErrorLog($e->getMessage());
        home($errorConnection);
    }
}