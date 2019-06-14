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
 *                  03.06.2019 yannick.baudraz@cpnv.cj
 *                      Update request "rent_detailsInsert" for new column "status"
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
    $rentsInsert = "INSERT INTO rents VALUES (" . $rentID . ", " . $userID . ", DATE(NOW()))";
    executeQueryInsert($rentsInsert);
    foreach ($currentCartArray as $item) {
        $itemID = getSnowId($item["code"]);
        $rent_detailsInsert = "
            INSERT INTO rent_details (fk_rentId, fk_snowId, leasingDays, qtySnow)
                VALUES (" . $rentID . "," . $itemID . "," . $item['nbD'] . "," . $item['qty'] . ")
        ";

        executeQueryInsert($rent_detailsInsert);
        updateSnowQuantity($item["code"], $item["qty"]);
    }

    // Get the rent to display. The rent is the one we just saved.
    $result = getOneRent($rentID);

    return $result;
}

/**
 * Function that finds the last inserted Rent
 *
 * @return mixed : id of that last rent
 */
function getLastRentID()
{
    $getLastRentIdQuery = 'SELECT id FROM rents ORDER BY id DESC LIMIT 1';
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getLastRentIdQuery);
    if (!$queryResult) { // If there isn't any rent, return 0
        $queryResult[0]['id'] = 0;
    }

    return $queryResult[0]['id'];
}

/**
 * Gets all data of a specific rent
 *
 * @param $rentId : rent we want to get
 * @return array|null : array with rent's information
 */
function getOneRent($rentId)
{
    $getOneRentQuery = "
        SELECT rents.id,
               snows.code,
               snows.brand,
               snows.model,
               snows.dailyPrice,
               rent_details.qtySnow,
               rent_details.leasingDays,
               rents.dateStart,
               rent_details.status
        FROM rents 
            INNER JOIN rent_details ON rents.id = rent_details.fk_rentId 
            INNER JOIN snows ON snows.id = rent_details.fk_snowId 
        WHERE rents.id = " . $rentId;

    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getOneRentQuery);

    foreach ($queryResult as $index => $rentDetail) {
        $queryResult[$index]['status'] = convertStatusFromCode($rentDetail['status']);
    }

    return $queryResult;
}

/**
 * Gets all data of all rents from a user
 *
 * @param $userEmailAddress : user who's rents we want to get
 * @return array|null : array with all of the rents
 */
function getUserRent($userEmailAddress)
{
    require_once 'model/usersManager.php';
    $userID = getUserID($userEmailAddress)[0]['id'];
    $getUserRentQuery = "
        SELECT rents.id,
               snows.code,
               snows.brand,
               snows.model,
               snows.dailyPrice,
               rent_details.qtySnow,
               rent_details.leasingDays,
               rents.dateStart,
               rent_details.status
        FROM rents 
            INNER JOIN rent_details ON rents.id = rent_details.fk_rentId 
            INNER JOIN snows ON snows.id = rent_details.fk_snowId 
        WHERE rents.fk_userId = " . $userID;

    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getUserRentQuery);

    foreach ($queryResult as $index => $rentDetail) {
        $queryResult[$index]['status'] = convertStatusFromCode($rentDetail['status']);
    }

    return $queryResult;
}

function getOneRentDetail($rentID, $snowID, $leasingDays)
{
    $getRentDetailQuery = "
        SELECT * 
        FROM rent_details
        WHERE fk_rentId = " . $rentID . " AND fk_snowID = " . $snowID . " AND leasingDays = " . $leasingDays . "
    ";
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getRentDetailQuery);

    return $queryResult;
}

/**
 * Convert the status of a detail's rent from his database's code
 *
 * @param int $status
 * @return string $status
 *
 * @example status is 0, return "En cours"
 *          status is 1, return "Rendu"
 */
function convertStatusFromCode($status)
{
    if ($status == 1) {
        $status = 'Rendu';
    } else {
        $status = 'En cours';
    }

    return $status;
}