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
 * @param array      $currentCartArray
 * @param string|int $snowCodeToAdd
 * @param string|int $qtyOfSnowsToAdd
 * @param string|int $howManyLeasingDays
 * @param null       $indexLocation
 * @return array $cartUpdated
 */
function updateCart($currentCartArray, $snowCodeToAdd, $qtyOfSnowsToAdd, $howManyLeasingDays, $indexLocation = null)
{
    $cartUpdated = null;
    $flagCart = false;
    $quantityAll = $qtyOfSnowsToAdd;
    $flagQuantity = false;

    if ($indexLocation === null) {
        if ($currentCartArray != null) {
            foreach ($currentCartArray as $index => $location) {
                if ($snowCodeToAdd === $location["code"]) {
                    $quantityAll += $location['qty'];
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

        if (!$flagCart && $flagQuantity) {
            $newSnowLeasing = array('code' => $snowCodeToAdd, 'dateD' => Date("d-m-y"), 'nbD' => $howManyLeasingDays, 'qty' => $qtyOfSnowsToAdd);
            array_push($cartUpdated, $newSnowLeasing);
        }
    } else {
        $addInformationArray = [
            'quantity' => $qtyOfSnowsToAdd,
            'leasingDays' => $howManyLeasingDays,
            'index' => $indexLocation
        ];

        foreach ($currentCartArray as $index => $location) {
            if ($snowCodeToAdd === $location["code"]) {
                $quantityAll += $location['qty'];
            }
        }
        if (verifyQuantity($snowCodeToAdd, $quantityAll, $qtyOfSnowsToAdd)) {
            $cartUpdated = changeLocation($currentCartArray, $addInformationArray);
        }
    }

    return $cartUpdated;
}

/**
 * Verify the quantity of snow to add in the cart
 *
 * @param $snowCode
 * @param $qtyAll
 * @param $qtyToAdd
 * @return bool $isQuantityOk : false if the check isn't good, else true
 */
function verifyQuantity($snowCode, $qtyAll, $qtyToAdd)
{
    $isQuantityOk = true;

    $strSeparator = '\'';
    $getQuantitySnow = 'SELECT qtyAvailable FROM snows WHERE code =' . $strSeparator . $snowCode . $strSeparator;
    require_once 'model/dbConnector.php';
    $queryResult = executeQuerySelect($getQuantitySnow);

    if ($qtyToAdd < 0 || $qtyAll > $queryResult[0]['qtyAvailable']) {
        $isQuantityOk = false;
    }

    return $isQuantityOk;
}

/**
 * Update the location according to the index
 *
 * @param array $cart                Current cart
 * @param array $addInformationArray Contain the quantity, the number of days and the index of the leasing in the cart
 * @return array $cart Cart updated
 */
function changeLocation($cart, $addInformationArray)
{
    $cart[$addInformationArray['index']]['qty'] = $addInformationArray['quantity'];
    $cart[$addInformationArray['index']]['nbD'] = $addInformationArray['leasingDays'];

    return $cart;
}

//in_array https://www.php.net/manual/en/function.in-array.php
//array_push() https://www.php.net/manual/en/function.array-push.php
//array_search
//unset
