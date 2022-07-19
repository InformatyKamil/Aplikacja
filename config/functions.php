<?php

function getStanowiska() {
    return array("kierownik","tester"); 
}

function jm() {
    return array("szt","kpl","kg","litr","m3","m2");
}

function getVat() {
    return array("0","5","8","23");
}

function getPrivilagesArray() {
    return array(
        'kierownik' => array(
            "testy" => array(1,0,1,1),
            "terminarz" => array(1,1,1,1),
            "raporty" => array(1,0,1,1)),
        'tester' => array(
            "testy" => array(1,0,0,0),
            "terminarz" => array(1,1,0,1),
            "raporty" => array(0,0,0,0)));
}

class DbConnect {
    private static $db;
    private static $host = "mysql2.superhost.pl";
    private static $dbName = "db100063539";
    private static $user = "db100063539";
    private static $pass = "63yTlE7RB2sfM";

    private static $isConnect = false;

    public static function connect() {
        if(!DbConnect::$db) {
            try {
                    DbConnect::$db = new PDO('mysql:host='.DbConnect::$host.';dbname='.DbConnect::$dbName, DbConnect::$user, DbConnect::$pass);
                    DbConnect::$isConnect = true;
                }
                catch(PDOException $e) {
                    DbConnect::$isConnect = false;
                    die();
                }
        }
        return DbConnect::$db; 
    }

    public static function getConnectStatus() {
        return DbConnect::$isConnect;
    }
}