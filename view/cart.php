<?php
/**
 * This php file is designed to manage all operation regarding cart's management
 * Author   : pascal.benzonana@cpnv.ch
 * Project  : Code
 * Created  : 23.03.2019 - 21:40
 *
 * Last update :    08.05.2019 mauro-alexandre.Costa-dos-santos@cpnv.ch
 *                      add button "Finaliser la location"
 * Source       :   [git source]
 */



$title = 'Rent A Snow - Demande de location';

ob_start();
?>
    <h2>Votre panier</h2>
    <article>
        <form method="POST" action="index.php?action=displaySnows">
            <table class="table">
                <tr>
                    <th>Code</th><th>Date</th><th>Nombre de jours</th><th>Quantité</th><th>Retirer</th>
                </tr>
                <?php
                // Displays cart session's content
                $cartArray = $_SESSION['cart'];
                foreach ($cartArray as $article){
                    echo "<tr>";
                    echo "<td>".$article['code']."</td>";
                    echo "<td>".$article['dateD']."</td>";
                    echo "<form method='POST' action='index.php?action=updateCartItem'>";
                    echo "<td><input type='number' name='uQty' value='".$article['qty']."' disabled></td>";
                    echo "<td><input type='number' name='uNbD' value='".$article['nbD']."' disabled></td>";

                    echo "<td><a href='index.php?action=updateCartRequest&code=".$article['code']."'><img src='view/content/images/delete2.png'></a></td>";
                    echo "</form></tr>";
                }
                ?>
            </table>
            <input type="submit" value="Louer encore" class="btn btn-info" name="backToCatalog">
            <input type="submit" value="Vider le panier" class="btn btn-cancel" name="resetCart">
            <input type="submit" value="Finaliser la location" class="btn btn-success" name="">
        </form>
    </article>
<?php
$content = ob_get_clean();
require 'gabarit.php';
?>