<div id='leftmenu'>
<script language="JavaScript">
function f(target_url,win_name) {
  var new_win = window.open(target_url,win_name,'resizable=yes,scrollbars=yes,menubar=no,toolbar=no,width=550,height=280,top=0,left=0');
  new_win.focus();
}
</script>
<body  class="style" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<center>
<div id='menu'>
<br>
<table width="130" cellspacing="0" cellpadding="0">
<tr>
	<td colspan="2" style="border-top: 1px #545454 solid"><div><center><?php echo $this->getData('servername')?><br>(<a href="changelog.php" target=<?php echo $this->getData('mf')?>><font color=red><?php echo $this->getData('XNovaRelease')?></font></a>)<center></div></td>
</tr><tr>
	<td colspan="2" background="<?php echo $this->getData('dpath')?>img/bg1.gif"><center><?php echo $this->getData('devlp')?></center></td>
</tr><tr>
	<td colspan="2"><div><a href="overview.php" accesskey="g" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Overview')?></a></div></td>
</tr><tr>

	<td height="1px" colspan="2" style="background-color:#FFFFFF"></td>
</tr><tr>
	<td colspan="2"><div><a href="buildings.php" accesskey="b" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Buildings')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="buildings.php?mode=research" accesskey="r" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Research')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="buildings.php?mode=fleet" accesskey="f" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Shipyard')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="buildings.php?mode=defense" accesskey="d" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Defense')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="officier.php" accesskey="o" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Officiers')?></a></div></td>
</tr><tr>
	<?php echo $this->getData('marchand_link')?>
</tr><tr>
	<td colspan="2" background="<?php echo $this->getData('dpath')?>img/bg1.gif"><center><?php echo $this->getData('navig')?></center></td>
</tr><tr>
	<td colspan="2"><div><a href="alliance.php" accesskey="a" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Alliance')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="fleet.php" accesskey="t" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Fleet')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="messages.php" accesskey="c" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Messages')?></a></div></td>
</tr><tr>

	<td colspan="2" background="<?php echo $this->getData('dpath')?>img/bg1.gif"><center><?php echo $this->getData('observ')?></center></td>
</tr><tr>
	<td colspan="2"><div><a href="galaxy.php?mode=0" accesskey="s" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Galaxy')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="imperium.php" accesskey="i" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Imperium')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="resources.php" accesskey="r" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Resources')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="techtree.php" accesskey="g" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Technology')?></a></div></td>
</tr><tr>

	<td height="1px" colspan="2" style="background-color:#FFFFFF"></td>
</tr><tr>
	<td colspan="2"><div><a href="records.php" accesskey="3" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Records')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="stat.php?range=<?php echo $this->getData('user_rank')?>" accesskey="k" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Statistics')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="search.php" accesskey="b" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Search')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="banned.php" accesskey="3" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('blocked')?></a></div></td>
</tr><?php echo $this->getData('announce_link')?><tr>


	<td colspan="2" background="<?php echo $this->getData('dpath')?>img/bg1.gif"><center><?php echo $this->getData('commun')?></center></td>
	</tr><tr>
	<td colspan="2"><div><a href="#" onClick="f('buddy.php', '');" accesskey="c"><?php echo $this->getData('Buddylist')?></a></div></td>
</tr></tr><?php echo $this->getData('notes_link')?><tr><tr>
	<td colspan="2"><div><a href="chat.php" accesskey="a" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Chat')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="<?php echo $this->getData('forum_url')?>" accesskey="1" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Board')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="add_declare.php" accesskey="1" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('multi')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="rules.php"  accesskey="c" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Rules')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="contact.php" accesskey="3" target="<?php echo $this->getData('mf')?>" ><?php echo $this->getData('Contact')?></a></div></td>
</tr><tr>
	<td colspan="2"><div><a href="options.php" accesskey="o" target="<?php echo $this->getData('mf')?>"><?php echo $this->getData('Options')?></a></div></td>
</tr>
	<?php echo $this->getData('ADMIN_LINK')?>
<tr>
</tr>
	<?php echo $this->getData('added_link')?>
<tr>
	<td colspan="2"><div><a href="javascript:top.location.href='logout.php'" accesskey="s" style="color:red"><?php echo $this->getData('Logout')?></a></div></td>
</tr><tr>
	<td colspan="2" background="<?php echo $this->getData('dpath')?>img/bg1.gif"><center><?php echo $this->getData('infog')?></center></td>
</tr>
	<?php echo $this->getData('server_info')?>
</table>
</div>
</center>
</body>
</div>