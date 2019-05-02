<?php
/**
 * Author   : yannick.baudraz@cpnv.ch
 * Project  : Projet web BD
 * Created  : 03.05.2019
 *
 * Last update :    05.03.2019 yannick.baudraz@cpnv.ch
 *                    function isLoginCorrect() added
 * Git source  :    https://github.com/Groupe-AMY/Projet_Web_DB/blob/master/model/usersManager.php
 */

/**
 * Verify the user's login
 *
 * @param string $userEmailAddress : user's mail to request
 * @param string $userPsw          : user's password to verify with the password in the database
 * @return bool : "true" only if the user and psw match the database. In all other cases will be "false".
 */
function isLoginCorrect($userEmailAddress, $userPsw)
{
  $result = false;

  $loginQuery = "SELECT userHashPsw FROM users WHERE userEmailAddress = '$userEmailAddress '";

  require_once 'model/dbConnector.php';
  $queryResult = executeQuerySelect($loginQuery);

  // If the user exists
  if (count($queryResult) == 1) {
    $userHashPsw = $queryResult[0]['userHashPsw'];
    $result = password_verify($userPsw, $userHashPsw);
  }

  return $result;
}