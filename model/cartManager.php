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
 * @param $snowCodeToAdd
 * @param $qtyOfSnowsToAdd
 * @param $howManyLeasingDays
 * @return array
 */
function updateCart($currentCartArray, $snowCodeToAdd, $qtyOfSnowsToAdd, $howManyLeasingDays)
{
    $cartUpdated = null;
    $flagCart = false;
    $quantityAll = $qtyOfSnowsToAdd;
    $flagQuantity = false;

    if ($currentCartArray != null) {
        foreach ($currentCartArray as $index => $snow) {
            if ($snowCodeToAdd === $snow["code"]) {
                $quantityAll += $snow['qty'];
                if ($howManyLeasingDays === $snow["nbD"]) {
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

//in_array https://www.php.net/manual/en/function.in-array.php
//array_push() https://www.php.net/manual/en/function.array-push.php
//array_search
//unset
