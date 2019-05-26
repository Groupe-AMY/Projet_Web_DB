<?php
/**
 * Author   : nicolas.glassey@cpnv.ch
 * Project  : Epreuve_151
 * Created  : 09.04.2019 - 13:46
 *
 * Last update :    08.05.2019 alexandre.fontes@cpnv.ch
 *                      cases displaySnows, snowLeasingRequest, updateCartRequest, displayCart
 *                  16.05.2019 alexandre.fontes@cpnv.ch
 *                      added parameters for snowLeasingRequest() and updateCartRequest()
 *                  22.05.2019
 *                      action "fixRent" and "displayRent"
 *                  25.05.2019 yannick.BAUDRAZ@cpnv.ch
*                       require in each actions
 * Git source  :    https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/index.php
 */

session_start();
require_once 'controler/controler.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'home':
            home();
            break;
        case 'register':
            require_once 'controler/user.php';
            register($_POST);
            break;
        case 'login':
            require_once 'controler/user.php';
            login($_POST);
            break;
        case 'logout':
            require_once 'controler/user.php';
            logout();
            break;
        case 'displaySnows':
            require_once 'controler/snow.php';
            displaySnows();
            break;
        case 'snowLeasingRequest':
            require_once 'controler/cart.php';
            snowLeasingRequest($_GET['code']);
            break;
        case 'updateCartRequest':
            require_once 'controler/cart.php';
            updateCartRequest($_GET['code'], $_GET['update'] ?? null, $_GET['delete'] ?? null, $_POST);
            break;
        case 'displayCart':
            require_once 'controler/cart.php';
            displayCart();
            break;
        case 'fixRent':
            require_once 'controler/rent.php';
            displayRent(null);
            break;
        case 'displayRent':
            require_once 'controler/rent.php';
            displayRent($_GET['display']);
            break;
        default:
            home();
    }
} else {
    home();
}