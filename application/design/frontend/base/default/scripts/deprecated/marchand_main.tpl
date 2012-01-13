<form action="marchand.php" method="post">
<input type="hidden" name="action" value="2">
<br>
<table width="600">
<tr>
	<td class="c" colspan="10"><font color="#FFFFFF"><?php echo $this->getData('mod_ma_title')?></font><td>
</tr><tr>
	<th colspan="10"><?php echo $this->getData('mod_ma_typer')?> <select name="choix">
		<option value="metal"><?php echo $this->getData('Metal')?></option>
		<option value="cristal"><?php echo $this->getData('Crystal')?></option>
		<option value="deut"><?php echo $this->getData('Deuterium')?></option>
	</select>
	<br>
	<?php echo $this->getData('mod_ma_rates')?><br /><br />
	<input type="submit" value="<?php echo $this->getData('mod_ma_buton')?>" /></th>
</tr>
</table>
</form>