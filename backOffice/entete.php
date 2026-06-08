<?php 
include ("securite.php");
?>
<!-- Compatibility layers for legacy JS scripts -->
<div id="Layer1" style="display:none;"><a href="logout.php">Se déconnecter</a></div>
<div id="grpMenu" style="display:none;"><p id="menu">menu</p></div>
<div id="logoViveParis" style="display:none;"></div>

<header class="app-header">
  <div class="logo-container">
    <img src="../images/logoViveParis.gif" alt="ViveParis" class="app-logo">
    <span class="logo-text">Copyleft ViveParis 2003 &copy;</span>
  </div>
  
  <nav class="app-nav">
    <a href="../index.php" class="nav-link">🏠 Retour au site</a>
    <a href="galerie.php" class="nav-link">🖼️ Galerie</a>
    <a href="galerie.php?critere=lieu" class="nav-link">📍 Liste des Lieux</a>
  </nav>

  <?php if ($utilisateur !== "intrus"): ?>
    <div class="user-menu">
      <span class="user-name">👤 <?php echo htmlspecialchars($utilisateur); ?></span>
      <a href="logout.php" class="logout-btn">Se déconnecter</a>
    </div>
  <?php endif; ?>
</header>

<table class="app-layout-table" align="center" width="90%">
<tr>
  <td> 
    <?php 
if ($utilisateur=="intrus"){
?>
    <form name="form1" method="post" action="">
      <table border="0" cellspacing="0" cellpadding="3" align="center">
        <tr> 
          <td colspan="2"><b>Zone privée (photographes)</b></td>
        </tr>
        <tr> 
          <td>Login</td>
          <td> 
            <input type="text" name="login">
          </td>
        </tr>
        <tr> 
          <td>Password</td>
          <td> 
            <input type="password" name="password">
          </td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
          <td align="right"> 
            <input type="submit" name="Submit" value="Valider">
          </td>
        </tr>
      </table>
    </form>
    
    <?php	
include("piedDePage.php");
echo "</body></html>";
exit();
}
?>
