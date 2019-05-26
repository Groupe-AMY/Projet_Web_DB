<?php
/**
 * Author       :   yannick.baudraz@cpnv.ch
 * Project      :   Projet web DB - snow.php
 * Description  :   Controller file for snows
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