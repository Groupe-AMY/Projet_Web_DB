<?php
/**
 * This php file is designed to manage all operation regarding cart's management
 * Author   : pascal.benzonana@cpnv.ch
 * Project  : Code
 * Created  : 23.03.2019 - 21:40
 *
 * Last update :    08.05.2019 mauro-alexandre.Costa-dos-santos@cpnv.ch
 *                      add button "Finaliser la location"
 *                  10.05.2019 mauro-alexandre.Costa-dos-santos@cpnv.ch
 *                      fix quantity and number of day placement
 *                  15.05.2019 mauro-alexandre.Costa-dos-santos@cpnv.ch
 *                      change forms and add "changer" button
 *                  16.05.2019 mauro-alexandre.Costa-dos-santos@cpnv.ch
 *                      change type of programing for the form in the table
 *                      added condition for the error according to the quantity
 * Source       :   https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/view/cart.php
 */


$title = 'Rent A Snow - Demande de location';

ob_start();
?>
    <h2>Votre panier</h2>
<?php if (!empty($error)): ?>
    <div class="alert alert-error">
        <strong>Erreur sur la quantité demandée.</strong><br>
        Quantité trop èlevée ou inférieur à 1. Vérifiez la disponibilité du stock.
    </div>
<?php endif ?>
    <article>
        <table class="table">
            <tr>
                <th>Code</th>
                <th>Date</th>
                <th>Quantité</th>
                <th>Nombre de Jours</th>
                <th>Retirer / Modifier</th>
            </tr>
            <?php
            // Displays cart session's content
            $cartArray = $_SESSION['cart'];
            foreach ($cartArray as $index => $article): ?>
                <form method="post"
                      action="index.php?action=updateCartRequest&code=<?= $article['code'] ?>&update=<?= $index ?>">
                    <tr>
                        <td><?= $article['code'] ?></td>
                        <td><?= $article['dateD'] ?></td>
                        <td><input type='number' name='inputQuantity' value="<?= $article['qty'] ?>"></td>
                        <td><input type='number' name='inputDays' value="<?= $article['nbD'] ?>"></td>
                        <td>
                            <a href='index.php?action=updateCartRequest&code=<?= $article['code'] ?>&delete=<?= $index ?>'>
                                <img src='view/content/images/delete2.png'>
                            </a>
                            <input class='btn btn-info' type='submit' value='Changer'>
                        </td>
                    </tr>
                </form>
            <?php endforeach ?>
        </table>
        <form method="POST" action="index.php?action=displaySnows">
            <input type="submit" value="Louer encore" class="btn btn-info" name="backToCatalog">
            <input type="submit" value="Vider le panier" class="btn btn-cancel" name="resetCart">
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) != 0): ?>
                <input type="submit" value="Finaliser la location" class="btn btn-success" name="">
            <?php endif ?>
        </form>
    </article>
<?php
$content = ob_get_clean();
require 'gabarit.php';
?>