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
    $result = NULL;
    $userID = getUserID($userEmail)[0];
    $rentID = countRent();
    foreach ($currentCartArray as $item) {
        $rentinsertQuery1 = 'INSERT INTO  rents VALUES (' . $rentID . ',' . $userID . ',' . date(d / m / Y) . ')';
        $queryResult1 = executeQueryInsert($rentinsertQuery1);
        $rentinsertQuery2 = 'INSERT INTO  rent_details VALUES (' . date(d-m-Y) . ')';
        $queryResult2 = executeQueryInsert($rentinsertQuery2);
    }

    $result = getOneRent($rentID);
    return $result;
}

function countRent()
{
    $getUserTypeQuery = 'SELECT * FROM rent';
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getUserTypeQuery);
    $result = count($queryResult);
    return $result;
}

function getOneRent($rentId)
{
    $getUserTypeQuery = 'SELECT rents.id,snows.code,snows.brand,snows.model,snows.dailyPrice,rent_details.qtySnow,rent_details.leasingDays,rents.dateStart FROM rents INNER JOIN rent_details ON rents.id = rent_details.fk_rentId INNER JOIN snows ON snows.id = rent_details.fk_snowId WHERE rents.id = ' . $rentId;
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getUserTypeQuery);
    return $queryResult;
}