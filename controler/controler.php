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