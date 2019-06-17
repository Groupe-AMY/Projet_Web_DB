<?php
/**
 * Author   : nicolas.glassey@cpnv.ch
 * Project  : dbConnector
 * Created  : 28.01.2019 - 20:13
 *
 * Last update :    [01.12.2018 author]
 *                  [add $logName in function setFullPath]
 * Git source  :    [link]
 */

/**
 * This function is designed to execute a query received as parameter
 *
 * @param $query : must be correctly build for sql (synthaxis) but the protection against sql injection will be done
 *               there
 * @return array|null : get the query result (can be null)
 * @source : http://php.net/manual/en/pdo.prepare.php
 * @throws \NoConnectionException
 */
function executeQuerySelect($query)
{
    $queryResult = NULL;

    $dbConnexion = openDBConnexion();//open database connexion
    if ($dbConnexion != NULL) {
        $statement = $dbConnexion->prepare($query);//prepare query
        $statement->execute();//execute query
        $queryResult = $statement->fetchAll();//prepare result for client
    }
    $dbConnexion = NULL;//close database connexion
    return $queryResult;
}

/**
 * This function is designed to insert value in database
 *
 * @param $query
 * @return bool|null : $statement->execute() return true is the insert was successful
 * @throws \NoConnectionException
 */
function executeQueryInsert($query)
{
    $queryResult = NULL;

    $dbConnexion = openDBConnexion();//open database connexion
    if ($dbConnexion != NULL) {
        $statement = $dbConnexion->prepare($query);//prepare query
        $queryResult = $statement->execute();//execute query
    }
    $dbConnexion = NULL;//close database connexion
    return $queryResult;
}

/**
 * This function is designed to manage the database connexion. Closing will be not proceeded there. The client is
 * responsible of this.
 *
 * @return PDO|null
 * Source : http://php.net/manual/en/pdo.construct.php
 * @throws \NoConnectionException
 */
function openDBConnexion()
{
    $tempDbConnexion = NULL;

    $sqlDriver = 'mysql';
    $hostname = 'localhost';
    $port = 3306;
    $charset = 'utf8';
    $dbName = 'snows';
    $userName = 'appliConnector';
    $userPwd = '123qweasD$';
    $dsn = $sqlDriver . ':host=' . $hostname . ';dbname=' . $dbName . ';port=' . $port . ';charset=' . $charset;

    try {
        $tempDbConnexion = new PDO($dsn, $userName, $userPwd);
    } catch (PDOException $exception) {
        require_once 'fileManager.php';
        require_once 'NoConnectionException.php';
        throw new NoConnectionException();
    }
    return $tempDbConnexion;
}