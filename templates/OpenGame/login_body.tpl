<div id="main">
<script type="text/javascript">
var lastType = "";
function changeAction(type) {
	if (document.formular.Uni.value == '') {
		alert('{log_univ}');
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
		{User_name} <input name="username" value="" type="text">
		{Password} <input name="password" value="" type="password">
	</td>
</tr><tr>
	<td style="padding-right: 4px;">
		{Remember_me} <input name="rememberme" type="checkbox"> <script type="text/javascript">document.formular.Uni.focus(); </script><input name="submit" value="{Login}" type="submit">
	</td>
</tr><tr>
	<td style="padding-right: 4px;">
		<a href="lostpassword.php">{PasswordLost}</a>
	</td>
</tr>
</tbody>
</table>
</form>
</div>
</div>
<div id="mainmenu" style="margin-top: 20px;">
<a href="reg.php">{log_reg}</a>
<a href="{forum_url}">Forum</a>
<a href="contact.php">Contact</a>
</div>
<div id="rightmenu" class="rightmenu">
<div id="title">{log_welcome} {servername}</div>
<div id="content">
<center>
<div id="text1">
<div style="text-align: left;"><strong>{servername}</strong> {log_desc} {servername}.
</div>
</div>
<div id="register" class="bigbutton" onclick="document.location.href='reg.php';"><font color="#cc0000">{log_toreg}</font></div>
<div id="text2">
<div id="text3">
<center><b><font color="#00cc00">{log_online}: </font>
<font color="#c6c7c6">{online_users}</font> - <font color="#00cc00">{log_lastreg}: </font>
<font color="#c6c7c6">{last_user}</font> - <font color="#00cc00">{log_numbreg}:</font> <font color="#c6c7c6">{users_amount}</font>
</b></center>
</div>
</div>
</center>
</div>
</div>
</div>