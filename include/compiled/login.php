<?php include template("header"); ?>
<div class="info-block"style="display:block;width: 100%; float: left;">
<form action="" method="post" name="login" id="form-login">
	  <fieldset class="input">
	    <p id="form-login-username">
	      <label for="modlgn_username">Логин</label>
	      <input id="modlgn_username" type="text" name="username" class="inputbox" size="18">
	    </p>
	    <p id="form-login-password">
	      <label for="modlgn_passwd">Пароль</label>
	      <input id="modlgn_passwd" type="password" name="password" class="inputbox" size="18">
	    </p>  
	    <input type="submit" name="Submit" class="button" value="Вход">
	  </fieldset>
</form>
</div>
<?php include template("footer"); ?>