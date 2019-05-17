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
 *                  15.05.2019 yannick.baudraz@cpnv.ch
 *                      Function deleteLocation()
 *                  16.05.2019 yannick.baudraz@cpnv.ch
 *                      Quantity verification when changing a location
 * Git source  :    https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/model/cartManager.php
 */

/**
 * Update the cart according to the leasing's days and the snow's code
 *
 * @param array      $currentCartArray   : Cart before the modification
 * @param string|int $snowCodeToAdd      : Code of the snow to add
 * @param string|int $qtyOfSnowsToAdd    : Quantity of the snow to add
 * @param string|int $howManyLeasingDays : Number of leasing days to add
 * @param null       $indexLocation      : Index of the snow to add
 * @return array $cartUpdated : Cart after the modification or null if the verification were unsuccessful
 */
function updateCart($currentCartArray, $snowCodeToAdd, $qtyOfSnowsToAdd, $howManyLeasingDays, $indexLocation = null)
{
    $cartUpdated = null;
    $flagCart = false;
    $quantityAll = $qtyOfSnowsToAdd;
    $flagQuantity = false;

    // If we don't want to change a location
    if ($indexLocation === null) {
        if ($currentCartArray != null) {
            foreach ($currentCartArray as $index => $location) {
                if ($snowCodeToAdd === $location["code"]) {
                    // Get all the quantity to add, for the verification of the quantity
                    $quantityAll += $location['qty'];
                    // If we need to fusion a location with the new one
                    if ($howManyLeasingDays === $location["nbD"]) {
                        $currentCartArray[$index]['qty'] += $qtyOfSnowsToAdd;
                        $flagCart = true;
                    }
                }
            }
        }

        if (verifyQuantity($snowCodeToAdd, $quantityAll, $qtyOfSnowsToAdd)) {
            $cartUpdated = $currentCartArray;
            $flagQuantity = true;
        }

        // If we want to add a new location in the cart
        if (!$flagCart && $flagQuantity) {
            $newSnowLeasing = [
                'code' => $snowCodeToAdd,
                'dateD' => Date("d-m-y"),
                'nbD' => $howManyLeasingDays,
                'qty' => $qtyOfSnowsToAdd
            ];
            array_push($cartUpdated, $newSnowLeasing);
        }
    } else {
        // Get all the quantity of snow to add, except the quantity of the snow we want to change
        foreach ($currentCartArray as $index => $location) {
            if ($snowCodeToAdd === $location["code"] && $index != $indexLocation) {
                $quantityAll += $location['qty'];
            }
        }

        if (verifyQuantity($snowCodeToAdd, $quantityAll, $qtyOfSnowsToAdd)) {
            // Take adding information in an array, to not surcharging the functions with too many parameters.
            $addInformationArray = [
                'quantity' => $qtyOfSnowsToAdd,
                'leasingDays' => $howManyLeasingDays,
                'index' => $indexLocation
            ];
            $cartUpdated = changeLocation($currentCartArray, $addInformationArray);
        }
    }

    return $cartUpdated;
}

/**
 * Verify the quantity of snows to add in the cart is not too much
 *
 * @param string|int $snowCode : Code of the snow to add
 * @param string|int $qtyAll   : All the quantity of the snow (in the cart and the quantity to add)
 * @param string|int $qtyToAdd : Quantity of the snow to add
 * @return bool $isQuantityOk : false if the verification isn't good, else true
 */
function verifyQuantity($snowCode, $qtyAll, $qtyToAdd)
{
    $strSeparator = '\'';
    $getQuantitySnow = 'SELECT qtyAvailable FROM snows WHERE code =' . $strSeparator . $snowCode . $strSeparator;
    require_once 'model/dbConnector.php';

    $isQuantityOk = true;
    if ($queryResult = executeQuerySelect($getQuantitySnow)) {
        if ($qtyToAdd < 0 || $qtyAll > $queryResult[0]['qtyAvailable']) {
            $isQuantityOk = false;
        }
    }

    return $isQuantityOk;
}

/**
 * Update the location according to the index
 *
 * @param array $cart                : Current cart
 * @param array $addInformationArray : Contain the quantity, the number of days and the index of the leasing in the cart
 * @return array $cart : Cart updated
 */
function changeLocation($cart, $addInformationArray)
{
    $cart[$addInformationArray['index']]['qty'] = $addInformationArray['quantity'];
    $cart[$addInformationArray['index']]['nbD'] = $addInformationArray['leasingDays'];

    return $cart;
}

/**
 * Delete a location in the cart according to the index
 *
 * @param array      $cart  : Current cart
 * @param string|int $index : Index of the location to delete
 * @return array $cart : Cart updated
 */
function deleteLocation($cart, $index)
{
    unset($cart[$index]);

    return $cart;
}

//in_array https://www.php.net/manual/en/function.in-array.php
//array_push() https://www.php.net/manual/en/function.array-push.php
//array_search
//unset
