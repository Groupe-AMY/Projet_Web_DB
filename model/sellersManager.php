<?php
/**
 * Author       :   yannick.baudraz@cpnv.ch
 * Project      :   Projet-Web DB - sellersManager.php
 * Description  :   Model file that manage the data for sellers
 *
 * Created      :   03.06.2019
 * Updates      :   06.06.2019
 *                      Add function getOneSellerRent and getSellerRentDetails
 *                  07.06.2019
 *                      Add function updateSellerDetailsRent and extractDataUpdateDetails
 *
 *
 * Git source   :   https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/model/sellersManager.php
 *
 * Created with PhpStorm.
 */

/**
 * Display all locations present in the database.
 *
 * @return null|array $allRents : If OK, contains location's ID, user's mail, location's start date, locations's end
 * date and status of the location's details.
 * @throws \NoConnectionException
 */
function getAllSellerRents()
{
    //region All rents query
    $getRentsQuery = "
        SELECT rents.id, users.userEmailAddress, rents.dateStart
            FROM rents
                INNER JOIN rent_details on rents.id = rent_details.fk_rentId
                INNER JOIN users on rents.fk_userId = users.id
        GROUP BY rents.id
    ";
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getRentsQuery);
    //endregion

    //region Put end's date and status in the array
    foreach ($queryResult as $index => $rent) {
        $queryResult[$index]['dateEnd'] = getLastDateRent($rent['id']);
        $queryResult[$index]['status'] = getRentStatus($rent['id']);
    }
    //endregion

    return $queryResult;
}

/**
 * Get the latest date of the location by his ID
 *
 * @param $rentID
 * @return string|null $lastDate : Latest date of the location
 * @throws \NoConnectionException
 */
function getLastDateRent($rentID)
{
    //region Last date query
    $getLastDateRentQuery = "
        SELECT ADDDATE(rents.dateStart, INTERVAL MAX(rent_details.leasingDays) DAY) AS lastDate
            FROM rents
                INNER JOIN rent_details ON rents.id = rent_details.fk_rentId
        WHERE rents.id = $rentID
        GROUP BY rents.id;
    ";
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getLastDateRentQuery);
    //endregion

    //region Get last date in a variable
    if ($queryResult) {
        $lastDate = $queryResult[0]['lastDate'];
    } else {
        $lastDate = NULL;
    }
    //endregion

    return $lastDate;
}

/**
 * Get the status of the location by his ID
 *
 * @param $rentId
 * @return string $status : Can be "En cours", "Rendu Partiel", "Rendu".
 * @throws \NoConnectionException
 */
function getRentStatus($rentId)
{
    //region Status query
    $getRentStatusQuery = "
        SELECT rent_details.status
            FROM rent_details
                INNER JOIN rents ON rents.id = rent_details.fk_rentId
        WHERE rents.id = $rentId
    ";
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getRentStatusQuery);
    //endregion

    //region Get status in a variable
    $rentStatus = "En cours"; // Status by default is "En cours"
    $statusSum = 0; // Needed to check if all snows are restored
    foreach ($queryResult as $rentDetail) {
        //region Check if at least one snow is restored
        if ($rentDetail['status'] == 1) {
            $rentStatus = "Rendu Partiel";
            $statusSum += $rentDetail['status'];
        }
        //endregion
    }
    //region Check if all snows are restored
    if ($statusSum === count($queryResult)) {
        $rentStatus = "Rendu";
    }
    //endregion
    //endregion

    return $rentStatus;
}

/**
 * Get one rent for the seller's view
 *
 * @param string|int $rentID
 * @return array|null $rent : The rent
 * @throws \NoConnectionException
 */
function getOneSellerRent($rentID)
{
    //region One rent query
    $getRentsQuery = "
        SELECT rents.id, users.userEmailAddress, rents.dateStart
            FROM rents
                INNER JOIN rent_details on rents.id = rent_details.fk_rentId
                INNER JOIN users on rents.fk_userId = users.id
        WHERE rents.id = $rentID
        GROUP BY rents.id
    ";
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getRentsQuery);
    $rent = $queryResult[0];
    //endregion
    //region Put end's date and status in the array
    $rent['dateEnd'] = getLastDateRent($rent['id']);
    $rent['status'] = getRentStatus($rent['id']);
    //endregion

    return $rent;
}

/**
 * Get details of a rent for the seller's view
 *
 * @param string|int $rentID
 * @return array|null $queryResult : If all it's ok, all the article of the rent
 * @throws \NoConnectionException
 */
function getSellerRentDetails($rentID)
{
    //region Rent details query
    $getRentsQuery = "
        SELECT s.code,
               r_d.qtySnow,
               r.dateStart,
               ADDDATE(r.dateStart, INTERVAL r_d.leasingDays DAY) AS dateEnd,
               r_d.status
        FROM rent_details as r_d
            INNER JOIN snows s on r_d.fk_snowId = s.id
            INNER JOIN rents r ON r_d.fk_rentId = r.id
        WHERE r.id = $rentID
    ";
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getRentsQuery);
    //endregion

    //region Convert coded status in human's strings
    require_once 'model/rentManager.php';
    foreach ($queryResult as $index => $rentDetail) {
        $queryResult[$index]['status'] = convertStatusFromCode($rentDetail['status']);
    }
    //endregion

    return $queryResult;
}

/**
 * Update rent's details from the seller request
 *
 * @param array $updateRequest : Request from the seller
 * @throws \NoConnectionException
 */
function updateSellerDetailsRent($updateRequest)
{
    $rentID = $updateRequest['rentID'];

    $detailsRentArray = extractDataUpdateDetails($updateRequest);

    foreach ($detailsRentArray as $rentDetail) {
        //region Update status of rent's details
        if (isset($rentDetail['status']) AND $rentDetail['status'] == 1) {
            require_once 'model/snowsManager.php';
            $snowID = getSnowId($rentDetail['code']);
            $updateRentDetailQuery = "
                UPDATE rent_details
                SET status = 1
                WHERE fk_rentID = {$rentID}
                    AND fk_snowID = {$snowID}
                    AND leasingDays = {$rentDetail['leasingDays']};
            ";
            executeQuerySelect($updateRentDetailQuery);

            require_once 'model/rentManager.php';
            $snowQtyOFDetail = getOneRentDetail(
                  $rentID,
                  $snowID,
                  $rentDetail['leasingDays']
            )[0]['qtySnow'];
            updateSnowQuantity($rentDetail['code'], -$snowQtyOFDetail);
        }
        //endregion
    }
}

/**
 * Extract date from the update request when the seller want to update a rent's details
 *
 * @param array $updateRequest : Request from the seller. From super global form POST
 * @return array $detailRentArray : Data extracted
 */
function extractDataUpdateDetails($updateRequest): array
{
    //region Variables initialization
    $detailsRentArray = [];
    $counter = 0;
    //endregion

    //region Extract data
    foreach ($updateRequest as $index => $item) {
        if (strstr($index, 'status')) {
            $detailsRentArray[$counter]['status'] = $item;
        } elseif (strstr($index, 'code')) {
            $detailsRentArray[$counter]['code'] = $item;
        } elseif (strstr($index, 'prise')) {
            $detailsRentArray[$counter]['dateStart'] = $item;
        } elseif (strstr($index, 'retour')) {
            $difference = date(
                  'j',
                  strtotime($item) - strtotime($detailsRentArray[$counter]['dateStart'])
            );
            $detailsRentArray[$counter]['leasingDays'] = $difference - 1;
            $counter++;
        }
    }
    //endregion

    return $detailsRentArray;
}