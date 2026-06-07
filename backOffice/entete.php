<?php 
include ("securite.php");
?>
<div id="Layer1" style="position:absolute; width:102px; height:29px; z-index:3; left: 896px; top: 26px"><a href="logout.php">Se 
  d&eacute;connecter</a> </div>
<div id="grpMenu" style="position:absolute; width:661px; height:27px; z-index:2; left: 227px; top: 21px"> 
  <p id="menu">menu</p></div>
<div id="logoViveParis" style="position:absolute; width:145px; height:31px; z-index:1; left: 42px; top: 12px"> 
  <div id="ligne" style="position:absolute; width:149px; height:30px; z-index:12; left: 24px; top: 22px"> 
    <hr>
  </div>
  <div id="logo" style="position:absolute; width:142px; height:31px; z-index:11; left: 34px"><img src="../images/logoViveParis.gif" width="135" height="28"></div>
  <div id="texteLogo" style="position:absolute; width:139px; height:26px; z-index:10; left: 32px; top: 32px"><font size="2">Copyleft 
    ViveParis 2003 &copy;</font></div>
</div>
<p>&nbsp;</p><br>
<table align="center" width="90%" bgcolor="#ffffff">
<tr>
  <td> 
    <?php 
if ($utilisateur=="intrus"){
?>
    <form name="form1" method="post" action="">
  
    <br>
      <table width="100" border="0" cellspacing="0" cellpadding="3" align="center">
        <tr> 
          <td colspan="2" height="28"> <b>Zone priv&eacute;e (photographes)</b></td>
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
            <input type="text" name="password">
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
    <br>
    
    <?php	
include("piedDePage.php");
echo "</body></html>";
exit();
}
?>
