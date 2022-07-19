<?php
    session_start();
    require_once('./config/db.php');
    require_once('./config/employe.php');
    require_once('./config/terminarz.php');
    require_once('./config/testy.php');

    if(empty($_SESSION['user'])) {
        header("Location: login-page.php");
        exit;
    } else {

        $employe = new Employ($connect);
        $testy = new Testy($connect);
        $terminarz = new Terminarz($connect);

        $user = $employe->getSessionUser($_SESSION['user']);
        $id_testera = $employe->isTester($_SESSION['user']);
        $isTester  =  $id_testera !== -1;
        $privaliges = $employe->getPrivilages($user['stanowisko']);

        $allTerminarze = $isTester ? $terminarz->getAllByTester($id_testera) : $terminarz->getAll();
        if(isset($_GET['all']) && $_GET['all'] == true && isset($_GET['raporty']) && $_GET['raporty'] == true ) {
            if($privaliges["raporty"][0] === 1) {
                $terminarz->loadDataAll($allTerminarze,$employe->getAllEmployers(),$testy->getAllTesty(),$privaliges);
            } else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else if(isset($_GET['all']) && $_GET['all'] == true) {
            if($privaliges["terminarz"][0] === 1) {
                $terminarz->loadData($allTerminarze,$employe->getAllEmployers(),$testy->getAllTesty(),$privaliges);
            } else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else if(isset($_GET['new']) && $_GET['new'] == true) {  
            $testerzy = $employe->getAllTesters();
            $testy = $testy->getAllTesty();
            if(empty($employe) || empty($testy)) {
                echo "<p class='empty-array'>Nie ma testów lub testerów</p>";
            } else {
                $terminarz->loadUpdateData($testerzy,$testy);
            }
        } else if(isset($_GET['saveFile']) && $_GET['saveFile'] == true) {
            $plik = $_FILES['files'];
            $terminarz->saveFile($_GET['terminarz'],$plik);
            echo $_FILES['files']['name'];
        } else if(isset($_GET['donwload']) && $_GET['donwload'] == true) {
            $file = $terminarz->getFile($_GET['id']);
            $name = $file['name'];
            $size = $file['size'];
            $type = $file['type'];
            $content = $file['content'];
	    header("Content-Disposition: attachment; filename=$name");
            header("Content-length: $size");
            header("Content-type: $type");
            echo $content;
            
        } else if(isset($_GET['newFromTable']) && $_GET['newFromTable'] == true) {
            $test = $testy->getTestById($_GET['id']);
            $uwagi = $terminarz->getUwagaAndFileById($_GET['term']);
            $terminarz->loadTest($test,$_GET['term'],$uwagi[0],$uwagi[1]);
        } else if(isset($_GET['save']) && $_GET['save'] == true)  {
            $uwagi = $_POST['uwagi'];
            $terminarz->saveUwagi($_POST['id_terminarz'],$uwagi);
            if(empty($uwagi)) {
                $terminarz->updateStatus($_POST['id_terminarz'],'W TRAKCIE');
            } else {
                $terminarz->updateStatus($_POST['id_terminarz'],'WYKONANE');
            }
            $terminarz->loadData($allTerminarze,$employe->getAllEmployers(),$testy->getAllTesty(),$privaliges);
        } else if(isset($_POST['add']) && $_POST['add'] == true ) {
            if($privaliges["terminarz"][2] === 1) {
                    $terminarz->insertTerminarz($_POST);
                    $terminarz->loadData($allTerminarze,$employe->getAllEmployers(),$testy->getAllTesty(),$privaliges);
            }
            else {
                echo "Nieprawidłowe uprawnienia";
            }
        } else {
            return;
        }
    } 