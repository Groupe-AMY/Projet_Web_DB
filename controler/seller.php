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
 *
 */
function displaySellersRent()
{
    require_once "model/sellersManager.php";
    $sellerRentArray=getAllSellerRents();
    require "view/sellerOverview.php";

}
function displayOneSellerRent($rentId){
    require_once "model/sellersManager.php";
    $rentArray=getOneSellerRent($rentId);
    $rentDetailsArray=getSellerRentDetails($rentId);
    require "view/sellerManagerLocation.php";
}