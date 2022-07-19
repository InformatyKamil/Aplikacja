<?php 
session_start();
if(isset($_SESSION['user'])) {
  header("Location: dashboard.php");
  exit;
}
require_once("./modules/header.php"); 
require_once('./config/db.php');

?>
<main class="login-page">
<div class="main-container">
      <div class="banner">
        <p>Program raportowania w rozproszonym procesie testowania</p>
        <button type="button" class="btn-left">Zaloguj się</button>
      </div>
      <div class="status">
        <p class="<?php echo DbConnect::getConnectStatus() ? "status-ok" : "status-not-ok";  ?>">Połączenie z bazą danych</p>   
      </div>
      <section class="popup-overflow">
        <div class="popup" id="popup"></div>
      </section>
      <div class="small-popup"></div>
</div>
</main> 
<?php require_once("modules/footer.php") ?>