<?php
    session_start();
    require_once('./config/db.php');
    require_once('./config/employe.php');
    require_once('./config/testy.php');

    if(empty($_SESSION['user'])) {
        header("Location: login-page.php");
        exit;
    } else {

        $employe = new Employ($connect);
        $test = new Testy($connect);

        $user = $employe->getSessionUser($_SESSION['user']);
        $privaliges = $employe->getPrivilages($user['stanowisko']);
        $testy =$test->getAllTesty();

        if(isset($_GET['all']) && $_GET['all'] == true) {
            if($privaliges["testy"][0] === 1) {
                $test->loadData($testy,$privaliges);
            } else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else if(isset($_GET['update']) && $_GET['update'] == true && !empty($_GET['id'])) {
            $tescik = $test->getTestById($_GET['id']);
            if($privaliges["testy"][3] === 1  && count($tescik) !== 0) {
                $test->loadUpdateData($tescik,$_GET['id'],true,false);
            } else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else if(isset($_GET['show']) && $_GET['show'] == true && !empty($_GET['id'])) {
            $tescik = $test->getTestById($_GET['id']);
            if($privaliges["testy"][0] === 1  && count($tescik) !== 0) {
                $test->loadUpdateData($tescik,$_GET['id'],true,true);
            } else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else if(isset($_GET['new']) && $_GET['new'] == true) {  
            $test->loadUpdateData(null,null,false,false);
        } else if(isset($_POST['save']) && $_POST['save'] == true && !empty($_POST['id'])) {
            $tescik = $test->getTestById($_POST['id']);
            if($privaliges["testy"][2] === 1  && count($tescik) !== 0) {
                $nazwa = htmlspecialchars(trim($_POST['test_nazwa']));
                if($nazwa ===  $tescik["test_nazwa"] || empty($test->checkIfExists($nazwa))) {
                        $test->updateTest($_POST['id'],$_POST);
                        $testy =$test->getAllTesty();
                        $test->loadData($testy,$privaliges);
                } else {
                    echo "Test o takiej nazwie już istnieje w bazie danych";
                }
            } else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else if(isset($_POST['add']) && $_POST['add'] == true ) {
            if($privaliges["testy"][2] === 1) {
                $nazwa = htmlspecialchars(trim($_POST['test_nazwa']));
                if(empty($test->checkIfExists($nazwa))) {
                    $test->insertTest($_POST);
                    $testy =$test->getAllTesty();
                    $test->loadData($testy,$privaliges);
                } else {
                    echo "Test o takiej nazwie już istnieje w bazie danych";
                }
            } else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else {
            return;
        }
    } 