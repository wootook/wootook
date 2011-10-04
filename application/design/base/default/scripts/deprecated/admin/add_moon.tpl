<br><br>
<h2><?php echo $this->getData('addm_title')?></h2>
<form action="add_moon.php" method="post">
<input type="hidden" name="mode" value="addit">
<table width="320" border="0" cellspacing="2" cellpadding="0" style="color:#FFFFFF">
<tr>
	<td class="c" colspan="6"><?php echo $this->getData('addm_addform')?></td>
</tr><tr>
	<th width="150"><?php echo $this->getData('addm_playerid')?></th>
	<th width="0%"><input type="text" name="user" size="3"></th>
</tr><tr>
	<th><?php echo $this->getData('addm_moonname')?></th>
	<th><input type="text" name="name"></th>
</tr><tr>
	<th colspan="2"><input type="submit" value="<?php echo $this->getData('addm_moondoit')?>"></th>
</table>
</form>