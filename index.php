<?php
/**
 * Author   : nicolas.glassey@cpnv.ch
 * Project  : Epreuve_151
 * Created  : 09.04.2019 - 13:46
 *
 * Last update :    08.05.2019 alexandre.fontes@cpnv.ch
 *                      cases displaySnows, snowLeasingRequest, updateCartRequest, displayCart
 * Git source  :    [link]
 */

session_start();
require "controler/controler.php";

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
            updateCartRequest($_GET['code'],$_GET['update'] ?? null, $_POST);
            break;
        case 'displayCart':
            displayCart();
            break;
        default:
            home();
    }
} else {
    home();
}