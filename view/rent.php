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
 *                      foreach of the articles
 * Source       :   https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/view/fixRent.php
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
                <th>N°Location</th>
                <th>Code</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Nombre de jours</th>
                <th>Date début de location</th>
            </tr>

            <?php
            foreach ($rentArray as $index => $article): ?>
                <form method="post"
                      action="index.php?action=fixRent=<?= $article['code'] ?>&update=<?= $index ?>">
                    <tr>
                        <td><?= $article['numLoc'] ?></td>
                        <td><?= $article['code'] ?></td>
                        <td><?= $article['marque'] ?></td>
                        <td><?= $article['modele'] ?></td>
                        <td><?= $article['prix'] ?></td>
                        <td><?= $article['qty'] ?></td>
                        <td><?= $article['nbj'] ?></td>
                        <td><?= $article['debutLoc'] ?></td>
                    </tr>
                </form>
            <?php endforeach ?>
        </table>
    </article>
<?php
$content = ob_get_clean();
require 'gabarit.php';
?>