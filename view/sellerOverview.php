<?php
/**
 * This php file is designed to manage all operation regarding cart's management
 * Author   : pascal.benzonana@cpnv.ch
 * Project  : Code
 * Created  : 23.03.2019 - 21:40
 *
 * Last update :    05.06.2019 Alexandre.Fontes@cpnv.ch
 *                      Creation of the page sellerOverview
 * Source       :   https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/view/sellerOverview.php
 */


$title = 'Rent A Snow - Location en cours';

ob_start();
?>
    <h2>Locations en cours</h2>
    <article>
        <table class="table">
            <tr>
                <th>Location</th>
                <th>Client</th>
                <th>Prise</th>
                <th>Retour</th>
                <th>Statut</th>
            </tr>

            <?php foreach ($sellerRentArray as $index => $article): ?>
                <tr>
                    <td><a href="index.php?action=displayOneSellerRent&rentID=<?= $article['id'] ?>"><?= $article['id'] ?></a></td>
                    <td><?= $article['userEmailAddress'] ?></td>
                    <td><?= date('d/m/Y',strtotime($article['dateStart'])) ?></td>
                    <td><?= date('d/m/Y',strtotime($article['dateEnd'])) ?></td>
                    <td><?= $article['status'] ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </article>
<?php
$content = ob_get_clean();
require 'gabarit.php';
?>