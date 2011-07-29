<script type="text/javascript" >
function calcul() {
	var Cristal = document.forms['marchand'].elements['cristal'].value;
	var Deuterium = document.forms['marchand'].elements['deut'].value;

	Cristal   = Cristal * <?php echo $this->getData('mod_ma_res_a')?>;
	Deuterium = Deuterium * <?php echo $this->getData('mod_ma_res_b')?>;

	var Metal = Cristal + Deuterium;
	document.getElementById("metal").innerHTML = Metal;

	if (isNaN(document.forms['marchand'].elements['cristal'].value)) {
		document.getElementById("metal").innerHTML = "<?php echo $this->getData('mod_ma_nbre')?>";
	}
	if (isNaN(document.forms['marchand'].elements['deut'].value)) {
		document.getElementById("metal").innerHTML = "<?php echo $this->getData('mod_ma_nbre')?>";
	}
}
</script>
<br>
<center>
<form id="marchand" action="marchand.php" method="post">
<input type="hidden" name="ress" value="metal">
<table width="569">
<tr>
	<td class="c" colspan="5"><b><?php echo $this->getData('mod_ma_buton')?></b></td>
</tr><tr>
	<th></th>
	<th></th>
	<th><?php echo $this->getData('mod_ma_cours')?></th>
</tr><tr>
	<th><?php echo $this->getData('Metal')?></th>
	<th><span id='metal'></span></th>
	<th><?php echo $this->getData('mod_ma_res')?></th>
</tr><tr>
	<th><?php echo $this->getData('Crystal')?></th>
	<th><input name="cristal" type="text" value="0" onkeyup="calcul()"/></th>
	<th><?php echo $this->getData('mod_ma_res_a')?></th>
</tr><tr>
	<th><?php echo $this->getData('Deuterium')?></th>
	<th><input name="deut" type="text" value="0" onkeyup="calcul()"/></th>
	<th><?php echo $this->getData('mod_ma_res_b')?></th>
</tr><tr>
	<th colspan="6"><input type="submit" value="<?php echo $this->getData('mod_ma_excha')?>" /></th>
</tr>
</form>
</table>
</center>