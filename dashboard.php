<?php 
session_start();
require_once("./modules/header.php"); 
require_once('./config/db.php');
require_once('./config/employe.php');

$employe = new Employ($connect);
$user = null;

if(empty($_SESSION['user'])) {
    header("Location: login-page.php");
    exit;
} else {
  $user = $employe->getSessionUser($_SESSION['user']);
  $imie = $user['imie'];
  $nazwisko = $user['nazwisko'];
  $privaliges = $employe->getPrivilages($user['stanowisko']);
  $allow = $user['stanowisko'] === 'kierownik';
}
?>
<main class="dashboard-page">
<div>
      <nav class="sidebar">
        <div class="hamburger-menu">
          <div class="line line-1"></div>
          <div class="line line-2"></div>
          <div class="line line-3"></div>
        </div>
        <div class="_card">
          <div class="_card-img">
            <img src="images/admin.png" width="50" class="admin-image" />
          </div>
          <div class="_card-body">
            <h2 class="_card-title"><?php echo  $imie ?> <?php echo  $nazwisko ?></h2>
            <p class="_card-subtitle"><?php echo $user['stanowisko'] ?></p>
          </div>
        </div>
        <ul class="_nav-list">
          <li class="_nav-item <?php echo ($privaliges["testy"][0] === 1) ? "p-show" : "p-hide" ?>">
            <a href="#" class="_nav-link"><i class="fas fa-tachometer-alt"></i><span class="_nav-link-text">Testy</span><i class="fas fa-angle-right"></i></a>
            <ul class="_subnav-list">
              <li class="_subnav-item <?php echo $privaliges["testy"][2] === 1 ? "p-show" : "p-hide" ?>"><a href="testy.php?new=true" class="_subnav-link"><i class="far fa-circle"></i> <span>Dodaj</span></a></li>
              <li class="_subnav-item <?php echo $privaliges["testy"][0] === 1 ? "p-show" : "p-hide" ?>"><a href="testy.php?all=true" class="_subnav-link"><i class="far fa-circle"></i> <span>Lista testów</span></a></li>
            </ul>
          </li>
          <li class="_nav-item <?php echo $privaliges["terminarz"][0] === 1 ? "p-show" : "p-hide" ?>">
            <a href="#" class="_nav-link"><i class="fas fa-file"></i><span class="_nav-link-text">Terminarz</span><i class="fas fa-angle-right"></i></a>
            <ul class="_subnav-list">
              <li class="_subnav-item <?php echo $privaliges["terminarz"][2] === 1 ? "p-show" : "p-hide" ?>"><a href="terminarz.php?new=true" class="_subnav-link"><i class="far fa-circle"></i> <span>Dodaj</span></a></li>
              <li class="_subnav-item <?php echo $privaliges["terminarz"][0] === 1 ? "p-show" : "p-hide" ?>"><a href="terminarz.php?all=true" class="_subnav-link"><i class="far fa-circle"></i> <span>Twoje zadania</span></a></li>
            </ul>
          </li>
          <li class="_nav-item <?php echo $privaliges["raporty"][0] === 1 ? "p-show" : "p-hide" ?>">
            <a href="#" class="_nav-link"><i class="far fa-gem"></i><span class="_nav-link-text">Raporty</span><i class="fas fa-angle-right"></i></a>
            <ul class="_subnav-list">
              <li class="_subnav-item <?php echo $privaliges["raporty"][0] === 1 ? "p-show" : "p-hide" ?>"><a href="terminarz.php?all=true&raporty=true" class="_subnav-link"><i class="far fa-circle"></i> <span>Lista zadań</span></a></li>
            </ul>
          </li>
          <li class="_nav-item <?php echo $allow ? "p-show" : "p-hide" ?>">
            <a href="#" class="_nav-link"><i class="far fa-grin-beam"></i><span class="_nav-link-text">Administrator</span><i class="fas fa-angle-right"></i></a>
            <ul class="_subnav-list">
              <li class="_subnav-item"><a href="uprawnienia.php?all=true" class="_subnav-link"><i class="far fa-circle"></i> <span>Uprawnienia</span></a></li>
              <li class="_subnav-item"><a href="employers.php?all=true" class="_subnav-link"><i class="far fa-circle"></i> <span>Pracownicy</span></a></li>
            </ul>
          </li>
          <li class="_nav-item">
            <a href="login.php?login=true" class="_nav-link no-menu"><i class="fas fa-power-off"></i><span class="_nav-link-text">Wyloguj</span></a>
          </li>
        </ul>
      </nav>
      <section class="main-section">
          <h1>Program raportowania w rozproszonym procesie testowania</h1>
          <div class="main">
          </div>
          <p class="praca">Opis do wstawienia</p>
      </section>
      <div class="small-popup"></div>
    </div>
</main> 
<?php require_once("modules/dashboard-footer.php") ?>