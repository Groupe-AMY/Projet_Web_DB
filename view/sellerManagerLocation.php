<?php
/**
 * This php file is designed to manage all operation regarding cart's management
 * Author   : pascal.benzonana@cpnv.ch
 * Project  : Code
 * Created  : 23.03.2019 - 21:40
 *
 * Last update :    05.06.2019 Alexandre.Fontes@cpnv.ch
 *                      Creation of the page sellerManagerLocation
 *                  07.06.2019 Alexandre.Fontes@cpnv.ch
 *                      Update the foreach
 * Source       :   https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/view/sellerManagerLocation.php
 */

$title = 'Rent A Snow - Gestion des retours';

ob_start();
?>
    <h2>Gestion des retours</h2>
    <table>
        <tr>
            <td>Location : <?= $rentArray['id'] ?></td>
            <td>Email : <?= $rentArray['userEmailAddress'] ?></td>
        </tr>
        <tr>
            <td>Prise : <?= date('d/m/Y', strtotime($rentArray['dateStart'])) ?></td>
            <td>Retour : <?= date('d/m/Y', strtotime($rentArray['dateEnd'])) ?></td>
        </tr>
        <tr>
            <td>Statut : <?= $rentArray['status'] ?></td>
        </tr>
    </table>
    <br>
    <form action="index.php?action=displayOneSellerRent" method="post">
        <article>
            <table class="table table-bordered table-hover">
                <tr>
                    <th>Code</th>
                    <th>Quantité</th>
                    <th>Prise</th>
                    <th>Retour</th>
                    <th>Statut</th>
                </tr>

                <?php foreach ($rentDetailsArray as $index => $article): ?>
                    <tr>
                        <td><?= $article['code'] ?></td>
                        <td><?= $article['qtySnow'] ?></td>
                        <td><?= date('d/m/Y', strtotime($article['dateStart'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($article['dateEnd'])) ?></td>
                        <td>

                            <?php if ($article['status'] == "Rendu") : ?>
                                <?= $article['status'] ?>
                            <?php else : ?>
                                <select name="status" id="status">
                                    <option value="0">En cours</option>
                                    <option value="1">Rendu</option>
                                </select>
                            <?php endif ?>
                        </td>
                        <input type="hidden" name="statusIndex" value="<?= $index ?>">
                    </tr>
                <?php endforeach ?>
            </table>
        </article>

        <div class="row">
            <a class="btn btn-secondary" href="index.php?action=displaySellerRents">Retour à la vue d'ensemble</a>
            <input class="btn btn-success" type="submit" value="Enregistrer les modifications" style="float: right">
        </div>
    </form>

<?php
$content = ob_get_clean();
require 'gabarit.php';
?>