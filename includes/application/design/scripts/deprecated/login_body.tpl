<div id="main">
<script type="text/javascript">
var lastType = "";
function changeAction(type) {
	if (document.formular.Uni.value == '') {
		alert('<?php echo $this->getData('log_univ')?>');
	} else {
		if(type == "login" && lastType == "") {
			var url = "http://" + document.formular.Uni.value + "";
			document.formular.action = url;
		} else {
			var url = "http://" + document.formular.Uni.value + "/reg.php";
			document.formular.action = url;
			document.formular.submit();
		}
	}
}
</script>
<div id="login">
<div id="login_input">
<form name="formular" action="" method="post" onsubmit="changeAction('login');">
<table width="400" border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr style="vertical-align: top;">
	<td style="padding-right: 4px;">
		<?php echo $this->getData('User_name')?> <input name="username" value="" type="text">
		<?php echo $this->getData('Password')?> <input name="password" value="" type="password">
	</td>
</tr><tr>
	<td style="padding-right: 4px;">
		<?php echo $this->getData('Remember_me')?> <input name="rememberme" type="checkbox"> <script type="text/javascript">document.formular.Uni.focus(); </script><input name="submit" value="<?php echo $this->getData('Login')?>" type="submit">
	</td>
</tr><tr>
	<td style="padding-right: 4px;">
		<a href="lostpassword.php"><?php echo $this->getData('PasswordLost')?></a>
	</td>
</tr>
</tbody>
</table>
</form>
</div>
</div>
<div id="mainmenu" style="margin-top: 20px;">
<a href="reg.php"><?php echo $this->getData('log_reg')?></a>
<a href="<?php echo $this->getData('forum_url')?>">Forum</a>
<a href="contact.php">Contact</a>
</div>
<div id="rightmenu" class="rightmenu">
<div id="title"><?php echo $this->getData('log_welcome')?> <?php echo $this->getData('servername')?></div>
<div id="content">
<center>
<div id="text1">
<div style="text-align: left;"><strong><?php echo $this->getData('servername')?></strong> <?php echo $this->getData('log_desc')?> <?php echo $this->getData('servername')?>.
</div>
</div>
<div id="register" class="bigbutton" onclick="document.location.href='reg.php';"><font color="#cc0000"><?php echo $this->getData('log_toreg')?></font></div>
<div id="text2">
<div id="text3">
<center><b><font color="#00cc00"><?php echo $this->getData('log_online')?>: </font>
<font color="#c6c7c6"><?php echo $this->getData('online_users')?></font> - <font color="#00cc00"><?php echo $this->getData('log_lastreg')?>: </font>
<font color="#c6c7c6"><?php echo $this->getData('last_user')?></font> - <font color="#00cc00"><?php echo $this->getData('log_numbreg')?>:</font> <font color="#c6c7c6"><?php echo $this->getData('users_amount')?></font>
</b></center>
</div>
</div>
</center>
</div>
</div>
</div>