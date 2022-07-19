<?php 
session_start();
require_once('./config/db.php');
require_once('./config/employe.php');

$employe = new Employ($connect);

if(isset($_GET) && !empty($_GET) && $_GET['login'] === 'true') {
    session_destroy();
    echo "1,Zostałeś poprawnie wylogowany";
} else {
    $login = htmlspecialchars(trim($_POST['login']));
    $password = htmlspecialchars(trim($_POST['password']));
    
    if(isset($_SESSION['user']) && $_SESSION['user'] === $login) {
        echo "2,Jesteś już zalogowany";
    }
    
    if(!empty($employe->getUser($login,$password))) {
        $_SESSION['user'] = $login;
        echo "1,Poprawne logowanie";
    } else {
        echo "0,Musisz być najpierw dodany do bazy danych";
    }
}