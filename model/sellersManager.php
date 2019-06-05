<?php
/**
 * Author       :   yannick.baudraz@cpnv.ch
 * Project      :   Projet-Web DB - sellersManager.php
 * Description  :   Model file that manage the data for sellers
 *
 * Created      :   03.06.2019
 * Updates      :   [dd.mm.yyyy author]
 *                      [description of update]
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
        $lastDate = getLastDateRent($rent['id']);
        $queryResult[$index]['dateEnd'] = $lastDate;

        $status = getRentStatus($rent['id']);
        $queryResult[$index]['status'] = $status;
    }
    //endregion

    return $queryResult;
}

/**
 * Get the latest date of the location by his ID
 *
 * @param $locationID
 * @return string|null $lastDate : Latest date of the location
 */
function getLastDateRent($locationID)
{
    //region Last date query
    $getLastDateRentQuery = "
        SELECT ADDDATE(rents.dateStart, INTERVAL MAX(rent_details.leasingDays) DAY) AS lastDate
            FROM rents
                INNER JOIN rent_details ON rents.id = rent_details.fk_rentId
        WHERE rents.id = $locationID
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
 * @param $locationId
 * @return string $status : Can be "En cours", "Rendu Partiel", "Rendu".
 */
function getRentStatus($locationId)
{
    //region Status query
    $getRentStatusQuery = "
        SELECT rent_details.status
            FROM rent_details
                INNER JOIN rents ON rents.id = rent_details.fk_rentId
        WHERE rents.id = $locationId
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