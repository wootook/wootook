<br><br>
<h2><?php echo $this->getData('adm_am_ttle')?></h2>
<form action="add_money.php" method="post">
<input type="hidden" name="mode" value="addit">
<table width="305">
<tbody>
<tr>
	<td class="c" colspan="6"><?php echo $this->getData('adm_am_form')?></td>
</tr><tr>
	<th width="130"><?php echo $this->getData('adm_am_plid')?></th>
	<th width="155"><input name="id" type="text" value="0" size="3" /></th>
</tr><tr>
	<th><?php echo $this->getData('Metal')?></th>
	<th><input name="metal" type="text" value="0" /></th>
</tr><tr>
	<th><?php echo $this->getData('Crystal')?></td>
	<th><input name="cristal" type="text" value="0" /></th>
</tr><tr>
	<th><?php echo $this->getData('Deuterium')?></td>
	<th><input name="deut" type="text" value="0" /></th>
</tr><tr>
	<th colspan="2"><input type="Submit" value="<?php echo $this->getData('adm_am_add')?>" /></th>
</tbody>
</tr>
</table>
</form>