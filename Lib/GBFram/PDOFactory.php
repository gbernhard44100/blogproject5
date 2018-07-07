<?php

namespace Lib\GBFram;

class PDOFactory
{

    public static function getMysqlConnexion($appName)
    {
        $xml = new \DOMDocument;
        $xml->load(__DIR__ . '/../../App//' . $appName . '/Config/mysqlconnection.xml');
        $pdo = $xml->getElementsByTagName('define')[0];
        $dao = new \PDO('mysql:host=' . $pdo->getAttribute('host') . ';dbname=' . $pdo->getAttribute('dbname') . ';charset=utf8', $pdo->getAttribute('user'), $pdo->getAttribute('password'));
        $dao->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $dao->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);

        return $dao;
    }

}
