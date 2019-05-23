<?php
/**
 * Author   : nicolas.glassey@cpnv.ch
 * Project  : 151_2019_code
 * Created  : 04.04.2019 - 18:48
 *
 * Last update :    08.05.2019 yannick.baudraz@cpnv.ch
 *                      Fusion of the articles in the cart
 *                  13.05.2019 yannick.baudraz@cpnv.ch
 *                      Function verifyQuantity()
 * Git source  :    [link]
 */

/**
 * Update the cart according to the leasing's days and the snow's code
 *
 * @param $currentCartArray
 * @param $userEmail
 * @return array
 */
function saveRent($currentCartArray, $userEmail)
{
    require_once 'model/usersManager.php';
    require_once 'model/snowsManager.php';
    $result = NULL;
    $userID = getUserID($userEmail)[0]['id'];
    $rentID = getLastRentID() + 1;
    $rentInsertQuery1 = "INSERT INTO  rents VALUES (" . $rentID . ", " . $userID . ", DATE(NOW()))";
    $queryResult1 = executeQueryInsert($rentInsertQuery1);
    foreach ($currentCartArray as $item) {
        $itemID = getsnowId($item["code"]);
        $rentInsertQuery2 = "INSERT INTO  rent_details VALUES (" . $rentID . "," . $itemID . "," . $item['nbD'] . "," . $item['qty'] . ")";
        $queryResult2 = executeQueryInsert($rentInsertQuery2);
        updateSnow($item["code"], $item["qty"]);
    }

    $result = getOneRent($rentID);

    return $result;
}

function getLastRentID()
{
    $getUserTypeQuery = 'SELECT id FROM rents ORDER BY id DESC LIMIT 1';
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getUserTypeQuery);
    if (!$queryResult) {
        $queryResult[0]['id'] = 0;
    }
    return $queryResult[0]['id'];
}

function getOneRent($rentId)
{
    $getUserTypeQuery
          = 'SELECT rents.id,snows.code,snows.brand,snows.model,snows.dailyPrice,rent_details.qtySnow,rent_details.leasingDays,rents.dateStart FROM rents INNER JOIN rent_details ON rents.id = rent_details.fk_rentId INNER JOIN snows ON snows.id = rent_details.fk_snowId WHERE rents.id = '
          . $rentId;
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getUserTypeQuery);

    return $queryResult;
}
function getUserRent($userEmailAddress)
{
    $userID = getUserID($userEmailAddress)[0];
    $getUserTypeQuery
          = 'SELECT rents.id,snows.code,snows.brand,snows.model,snows.dailyPrice,rent_details.qtySnow,rent_details.leasingDays,rents.dateStart FROM rents INNER JOIN rent_details ON rents.id = rent_details.fk_rentId INNER JOIN snows ON snows.id = rent_details.fk_snowId WHERE rents.fk_userId = '
          . $userID;
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getUserTypeQuery);

    return $queryResult;
}