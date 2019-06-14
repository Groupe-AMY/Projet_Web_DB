<?php
/**
 * This php file is designed to manage all operation regarding cart's management
 * Author   : pascal.benzonana@cpnv.ch
 * Project  : Code
 * Created  : 23.03.2019 - 21:40
 *
 * Last update :    20.05.2019 Alexandre.Fontes@cpnv.ch
 *                      Customize the page
 *                  22.05.2019 Alexandre.Fontes@cpnv.ch
 *                      Foreach of the articles
 *                  24.05.2019 Alexandre.Fontes@cpnv.ch
 *                      Format the date of the location
 *                  14.06.2019 Alexandre.Fontes@cpnv.ch
 *                      Add the column "Status" 
 * Source       :   https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/view/rent.php
 */


$title = 'Rent A Snow - Demande de location';

ob_start();
?>
    <h2>Vos locations</h2>
    <p>Votre demande de location a été enregistrée.</p>
    <p>Si vous souhaitez visualiser votre commande, cliquez sur le logo <a href="#">PDF</a>.</p>
    <article>
        <table class="table">
            <tr>
                <th>N° Location</th>
                <th>Code</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Nombre de jours</th>
                <th>Date début de location</th>
                <th>Status</th>
            </tr>

            <?php foreach ($rentArray as $index => $article): ?>
                <tr>
                    <td><?= $article['id'] ?></td>
                    <td><?= $article['code'] ?></td>
                    <td><?= $article['brand'] ?></td>
                    <td><?= $article['model'] ?></td>
                    <td>CHF <?= $article['dailyPrice'] ?></td>
                    <td><?= $article['qtySnow'] ?></td>
                    <td><?= $article['leasingDays'] ?></td>
                    <td><?= date('d-m-Y',strtotime($article['dateStart'])) ?></td>
                    <td><?= $article['status'] ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    </article>
<?php
$content = ob_get_clean();
require 'gabarit.php';
?>