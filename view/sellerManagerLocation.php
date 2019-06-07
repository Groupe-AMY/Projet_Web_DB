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
    <table class="table">
        <tr>
            <td>Location: <?= $rent['id'] ?></td>
            <td>Email: <?= $rent['userEmailAddress'] ?></td>
        </tr>
        <tr>
            <td>Prise: <?= $rent['dateStart'] ?></td>
            <td>Retour: <?= $rent['dateEnd'] ?></td>
        </tr>
        <tr>
            <td>Statut: <?= $rent['status'] ?></td>
        </tr>
    </table>

    <article>
        <table class="table">
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
                                <option value="En cours"></option>
                                <option value="Rendu"></option>
                            </select>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>
    </article>

    <div class="row">
        <button style="text-align: left" class="favorite styled"
                type="button"> Retour à la vue d'ensemble
        </button>
        <button style="text-align: right" class="favorite styled"
                type="button"> Enregistrer les modifications
        </button>
    </div>

<?php
$content = ob_get_clean();
require 'gabarit.php';
?>