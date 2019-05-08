<?php
/**
 * Author   : nicolas.glassey@cpnv.ch
 * Project  : 151_2019_code
 * Created  : 04.04.2019 - 18:48
 *
 * Last update :    08.05.2019 yannick.baudraz@cpnv.ch
 *                      Fusion of the articles in the cart
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
    $cartUpdated = array();
    $flagCart = false;

    if ($currentCartArray != null) {
        foreach ($currentCartArray as $index => $snow) {
            if ($snowCodeToAdd === $snow["code"] && $howManyLeasingDays === $snow["nbD"]) {
                $currentCartArray[$index]['qty'] += $qtyOfSnowsToAdd;
                $flagCart = true;
            }
        }

        $cartUpdated = $currentCartArray;
    }
    if (!$flagCart) {
        $newSnowLeasing = array('code' => $snowCodeToAdd, 'dateD' => Date("d-m-y"), 'nbD' => $howManyLeasingDays, 'qty' => $qtyOfSnowsToAdd);
        array_push($cartUpdated, $newSnowLeasing);
    }

    return $cartUpdated;
}

//in_array https://www.php.net/manual/en/function.in-array.php
//array_push() https://www.php.net/manual/en/function.array-push.php
//array_search
//unset
