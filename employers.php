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
        $privaliges = $employe->getPrivilages($user['stanowisko']);
        $employers = $employe->getAllEmployers();

        if(isset($_GET['all']) && $_GET['all'] == true) {
            if($privaliges["raporty"][0] === 1 && count($employers) !== 0) {
                $employe->loadData($employers,$privaliges,$user);
            } else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else if(isset($_GET['delete']) && $_GET['delete'] == true && !empty($_GET['id'])) {
            if($privaliges["raporty"][1] === 1  && count($employers) !== 0) {
                if(!empty($wz->getWzByPracowniktId($_GET['id']))) {
                    echo "Istnieje WZ z tym pracownikiem";
                    return;
                }
                if(!empty($pz->getPZByPracowniktId($_GET['id']))) {
                    echo "Istnieje PZ z tym pracownikiem";
                    return;
                }
                $employe->deleteEmployee($_GET['id']);
                $employers = $employe->getAllEmployers();
                $employe->loadData($employers,$privaliges,$user);
            } else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else if(isset($_GET['update']) && $_GET['update'] == true && !empty($_GET['id'])) {
            $employer = $employe->getUserById($_GET['id']);
            if($privaliges["raporty"][3] === 1  && count($employer) !== 0) {
                $employe->loadUpdateData($employer,$_GET['id'],$user['login'],true);
            } else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else if(isset($_GET['new']) && $_GET['new'] == true) {  
            $employe->loadUpdateData(null,null,null,false);
        } else if(isset($_POST['save']) && $_POST['save'] == true && !empty($_POST['id'])) {
             $employer = $employe->getUserById($_POST['id']);
             $needLogin = !empty($_GET['need-login']);
            if($privaliges["raporty"][3] === 1  && count($employer) !== 0) {
                $login = htmlspecialchars(trim($_POST['login']));
                if($login ===  $employer["login"] || empty($employe->checkIfExists($login))) {
                    $employe->updateEmployee($_POST['id'],$_POST);
                    if($needLogin) {
                        session_destroy();
                        echo "Zostałeś poprawnie wylogowany";
                    } else {
                        $employers = $employe->getAllEmployers();
                        $employe->loadData($employers,$privaliges,$user);
                    }
                } else {
                    echo "Login już istnieje w bazie danych";
                }
            } else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else if(isset($_POST['add']) && $_POST['add'] == true ) {
            if($privaliges["raporty"][2] === 1) {
                $imie = htmlspecialchars(trim($_POST['imie']));
                $nazwisko = htmlspecialchars(trim($_POST['nazwisko']));
                $login = htmlspecialchars(trim($_POST['login']));

                $password = htmlspecialchars(trim($_POST['password']));
                $stanowisko = htmlspecialchars(trim($_POST['stanowisko']));
                if(empty($employe->checkIfExists($login))) {
                    $employe->insertEmploy($imie,$nazwisko,$login,$password,$stanowisko);
                    $employers = $employe->getAllEmployers();
                    $employe->loadData($employers,$privaliges,$user);
                } else {
                    echo "Login już istnieje w bazie danych";
                }
            } else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else {
            return;
        }
    } 