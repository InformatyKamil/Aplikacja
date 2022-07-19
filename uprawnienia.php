<?php
    session_start(); 
    require_once('./config/db.php');
    require_once('./config/employe.php');

    if(empty($_SESSION['user'])) {
        header("Location: login-page.php");
        exit;
    } else {

        $employe = new Employ($connect);
        $user = $employe->getSessionUser($_SESSION['user']);

        if(isset($_GET['all']) && $_GET['all'] == true) {
            if($user['stanowisko'] === 'kierownik') {
                $employe->loadPrivilages();
            }
        }
    }