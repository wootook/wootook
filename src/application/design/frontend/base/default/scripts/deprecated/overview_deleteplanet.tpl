<h1><?php echo $this->getData('ov_rena_dele')?></h1>
<form action="overview.php?mode=renameplanet&pl=<?php echo $this->getData('planet_id')?>" method="POST">
<table width="519">
<tr>
	<td colspan="3" class="c"><?php echo $this->getData('security_query')?></td>
</tr><tr>
	<th colspan="3"><?php echo $this->getData('confirm_planet_delete')?> <?php echo $this->getData('galaxy_galaxy')?>:<?php echo $this->getData('galaxy_system')?>:<?php echo $this->getData('galaxy_planet')?> <?php echo $this->getData('confirmed_with_password')?></th>
</tr><tr>
	<th><?php echo $this->getData('password')?></th>
	<th><input type="password" name="pw"></th>
	<th><input type="submit" name="action" value="<?php echo $this->getData('deleteplanet')?>" alt="<?php echo $this->getData('colony_abandon')?>"></th>
</tr>
</table>
<input type="hidden" name="kolonieloeschen" value="1">
<input type="hidden" name="deleteid" value ="<?php echo $this->getData('planet_id')?>">
</form>