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
 * Git source  :    [link]
 */

session_start();
require_once 'controler/controler.php';
require_once 'controler/user.php';
require_once 'controler/snow.php';
require_once 'controler/cart.php';
require_once 'controler/rent.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'home':
            home();
            break;
        case 'register':
            register($_POST);
            break;
        case 'login':
            login($_POST);
            break;
        case 'logout':
            logout();
            break;
        case 'displaySnows':
            displaySnows();
            break;
        case 'snowLeasingRequest':
            snowLeasingRequest($_GET['code']);
            break;
        case 'updateCartRequest':
            updateCartRequest($_GET['code'], $_GET['update'] ?? null, $_GET['delete'] ?? null, $_POST);
            break;
        case 'displayCart':
            displayCart();
            break;
        case 'fixRent':
            displayRent(null);
            break;
        case 'displayRent':
            displayRent($_GET['display']);
            break;
        default:
            home();
    }
} else {
    home();
}