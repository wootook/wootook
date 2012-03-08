<div id='leftmenu'>
<script language="JavaScript">
function f(target_url,win_name) {
  var new_win = window.open(target_url,win_name,'resizable=yes,scrollbars=yes,menubar=no,toolbar=no,width=550,height=280,top=0,left=0');
  new_win.focus();
}
parent.frames['Hauptframe'].location.replace("overview.php");
</script>
<body  class="style" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<center>
<div id='menu'>
<br>
<table width="130" cellspacing="0" cellpadding="0">
<tr>
	<td style="border-top: 1px #545454 solid"><div><center><?php echo $this->getData('servername')?><br>(<a href="changelog.php" target=<?php echo $this->getData('mf')?>><font color=red><?php echo $this->getData('XNovaRelease')?></font></a>)<center></div></td>
</tr><tr>
	<td background="<?php echo $this->getData('dpath')?>img/bg1.gif"><center><?php echo $this->getData('admin')?></center></td>
</tr><tr>
	<td><div><a href="overview.php" accesskey="v" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_over')?></a></div></td>
</tr><tr>
	<td><div><a href="settings.php" accesskey="e" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_conf')?></a></div></td>
</tr><tr>
	<td><div><a href="XNovaResetUnivers.php" accesskey="e" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_reset')?></a></div></td>
</tr><tr>
	<td background="<?php echo $this->getData('dpath')?>img/bg1.gif"><center><?php echo $this->getData('player')?></center></td>
</tr><tr>
	<td><div><a href="userlist.php" accesskey="a" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_plrlst')?></a></div></td>
</tr><tr>
    <td><div><a href="multi.php" accesskey="a" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_multi')?></a></div></td>
</tr><tr>
	<td><div><a href="paneladmina.php" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_plrsch')?></a></div></td>
</tr><tr>
	<td><div><a href="QueryExecute.php" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('qry')?></a></div></td>
</tr><tr>
	<td><div><a href="variables.php" accesskey="k" target="<?php echo $this->getData('mf')?>">PhpInfo</a></div></td>
</tr><tr>
	<td><div><a href="add_money.php" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_addres')?></a></div></td>
</tr><tr>
	<td style="background-color:#FFFFFF" height="1px"></td>
</tr><tr>
	<td><div><a href="planetlist.php" accesskey="1" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_pltlst')?></a></div></td>
</tr><tr>
	<td><div><a href="activeplanet.php" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_actplt')?></a></div></td>
</tr><tr>
	<td style="background-color:#FFFFFF" height="1px"></td>
</tr><tr>
	<td><div><a href="moonlist.php" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_moonlst')?></a></div></td>
</tr><tr>
	<td><div><a href="declare_list.php" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('multis_declared')?></a></div></td>
</tr><tr>
	<td><div><a href="add_moon.php" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_addmoon')?></a></div></td>
</tr><tr>
	<td style="background-color:#FFFFFF" height="1px"></td>
</tr><tr>
	<td><div><a href="ShowFlyingFleets.php" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_fleet')?></a></div></td>
</tr><tr>
	<td style="background-color:#FFFFFF" height="1px"></td>
</tr><tr>
	<td><div><a href="banned.php" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_ban')?></a></div></td>
</tr><tr>
	<td><div><a href="md5changepass.php" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('change_pass')?></a></div></td>
</tr><tr>
	<td><div><a href="unbanned.php" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_unban')?></a></div></td>
</tr><tr>
	<td background="<?php echo $this->getData('dpath')?>img/bg1.gif"><center><?php echo $this->getData('tool')?></center></td>
</tr><tr>
	<td><div><a href="chat.php" accesskey="p" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_chat')?></a></div></td>
</tr><tr>
	<td><div><a href="statbuilder.php" accesskey="p" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_updpt')?></a></div></td>
</tr><tr>
	<td><div><a href="messagelist.php" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_msg')?></a></div></td>
</tr><tr>
	<td><div><a href="md5enc.php" accesskey="p" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_md5')?></a></div></td>
</tr><tr>
	<td><div><a href="ElementQueueFixer.php" accesskey="p" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_build')?></a></div></td>
</tr><tr>
	<td style="background-color:#FFFFFF" height="1px"></td>
</tr><tr>
	<td><div><a href="errors.php" accesskey="e" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_error')?></a></div></td>
</tr><tr>
	<td><div><a href="http://wootook.org/forum/index.php" accesskey="3" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('adm_help')?></a></div></td>
</tr><tr>
	<td><div><a href="../frames.php" accesskey="i" target="_top" style="color:red"><?php echo $this->getData('adm_back')?></a></div></td>
</tr><tr>
	<td background="<?php echo $this->getData('dpath')?>img/bg1.gif"><center><?php echo $this->getData('infog')?></center></td>
</tr>
</table>
</div>
</center>
</body>
</div>