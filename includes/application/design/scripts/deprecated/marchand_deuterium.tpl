<script type="text/javascript" >
function calcul() {
	var Metal   = document.forms['marchand'].elements['metal'].value;
	var Cristal = document.forms['marchand'].elements['cristal'].value;

	Metal   = Metal * <?php echo $this->getData('mod_ma_res_a')?>;
	Cristal = Cristal * <?php echo $this->getData('mod_ma_res_b')?>;

	var Deuterium = Metal + Cristal;
	document.getElementById("deut").innerHTML=Deuterium;

	if (isNaN(document.forms['marchand'].elements['metal'].value)) {
		document.getElementById("deut").innerHTML="<?php echo $this->getData('mod_ma_nbre')?>";
	}
	if (isNaN(document.forms['marchand'].elements['cristal'].value)) {
		document.getElementById("deut").innerHTML="<?php echo $this->getData('mod_ma_nbre')?>";
	}
}
</script>
<br>
<center>
<form id="marchand" action="marchand.php" method="post">
<input type="hidden" name="ress" value="deuterium">
<table width="569">
<tr>
	<td class="c" colspan="5"><b><?php echo $this->getData('mod_ma_buton')?></b></td>
</tr><tr>
	<th></th>
	<th></th>
	<th><?php echo $this->getData('mod_ma_cours')?></th>
</tr><tr>
	<th><?php echo $this->getData('Deuterium')?></th>
	<th><span id='deut'></span></th>
	<th><?php echo $this->getData('mod_ma_res')?></th>
</tr><tr>
	<th><?php echo $this->getData('Metal')?></th>
	<th><input name="metal" type="text" value="0" onkeyup="calcul()"/></th>
	<th><?php echo $this->getData('mod_ma_res_a')?></th>
</tr><tr>
	<th><?php echo $this->getData('Crystal')?></th>
	<th><input name="cristal" type="text" value="0" onkeyup="calcul()"/></th>
	<th><?php echo $this->getData('mod_ma_res_b')?></th>
</tr><tr>
	<th colspan="6"><input type="submit" value="<?php echo $this->getData('mod_ma_excha')?>" /></th>
</tr>
</table>
</form>