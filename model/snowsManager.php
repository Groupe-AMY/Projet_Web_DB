<?php
/**
 * This php file is designed to manage all operation regarding snow's management
 * Author   : pascal.benzonana@cpnv.ch
 * Project  : 151
 * Created  : 18.02.2019 - 21:40
 *
 * Last update :    19.02.2019 PBA
 *                  update fields in query
 * Source       :   https://bitbucket.org/pba_cpnv/151-2019_pba
 */

/**
 * This function is designed to get all active snows
 *
 * @return array : containing all information about snows. Array can be empty.
 * @throws \NoConnectionException
 */
function getSnows(){
    $snowsQuery = 'SELECT code, brand, model, snowLength, dailyPrice, qtyAvailable, photo, active FROM snows';

    require_once 'model/dbConnector.php';
    $snowResults = executeQuerySelect($snowsQuery);

    return $snowResults;
}

/**
 * This function is designed to get only one snow
 *
 * @param $snow_code : snow code to display (selected by the user)
 * @return array|null : snow to display. Can be empty.
 * @throws \NoConnectionException
 */
function getASnow($snow_code){
    $strgSeparator = '\'';

    // Query to get the selected snow. The active code must be set to 1 to display only snows to display. It avoids possibilty to user selecting a wrong code (get paramater in url)
    $snowQuery = 'SELECT code, brand, model, snowLength, dailyPrice, qtyAvailable, description, photo FROM snows WHERE code='.$strgSeparator.$snow_code.$strgSeparator.'AND active=1';

    require_once 'model/dbConnector.php';
    $snowResults = executeQuerySelect($snowQuery);

    return $snowResults;
}

/**
 * This function changes the quantity of a specific snowboard
 *
 * @param $snowCode : snow code to change
 * @param $change   : amount to change
 * @throws \NoConnectionException
 */
function updateSnowQuantity($snowCode,$change){
    $getUserTypeQuery = "SELECT qtyAvailable FROM snows WHERE code='".$snowCode."'";
    require_once 'model/dbConnector.php';
    $oldQuantity = executeQuerySelect($getUserTypeQuery)[0]['qtyAvailable'];
    $newQuantity = $oldQuantity-$change;
    $rentInsertQuery2 = "UPDATE snows SET qtyAvailable=".$newQuantity." WHERE code='".$snowCode."'";
    $queryResult2 = executeQueryInsert($rentInsertQuery2);
}

/**
 * This function finds the snow id with the snow code
 *
 * @param $code : snow code that we want to find
 * @return mixed : id of the that snow code
 * @throws \NoConnectionException
 */
function getSnowId($code){
    $getUserTypeQuery = "SELECT id From snows WHERE code='".$code."'";
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getUserTypeQuery);
    return $queryResult[0]['id'];
}