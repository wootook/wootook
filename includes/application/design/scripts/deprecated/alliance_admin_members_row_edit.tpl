<br>
<form action="alliance.php?mode=admin&edit=members&id=<?php echo $this->getData('id')?>" method=POST>
	<tr>
	  <th colspan=3><?php echo $this->getData('Rank_for')?></th>
	  <th><select name="newrang"><?php echo $this->getData('options')?></select></th>
	  <th colspan=5><input type=submit value="<?php echo $this->getData('Save')?>"></th>
	</tr>
</form>
