<br />
<center>
<h1><?php echo $this->getData('rename_and_abandon_planet')?></h1>
<form action="overview.php?mode=renameplanet&pl=<?php echo $this->getData('planet_id')?>" method="POST">
<table width=519>
<tr>
	<td class="c" colspan=3><?php echo $this->getData('your_planet')?></td>
</tr><tr>
	<th><?php echo $this->getData('coords')?></th>
	<th><?php echo $this->getData('name')?></th>
	<th><?php echo $this->getData('functions')?></th>
</tr><tr>
	<th><?php echo $this->getData('galaxy_galaxy')?>:<?php echo $this->getData('galaxy_system')?>:<?php echo $this->getData('galaxy_planet')?></th>
	<th><?php echo $this->getData('planet_name')?></th>
	<th><input type="submit" name="action" value="<?php echo $this->getData('colony_abandon')?>" alt="<?php echo $this->getData('colony_abandon')?>"></th>
</tr><tr>
	<th><?php echo $this->getData('namer')?></th>
	<th><input type="text" name="newname" size=25 maxlength=20></th>
	<th><input type="submit" name="action" value="<?php echo $this->getData('namer')?>"></th>
</tr>
</table>
</form>
</center>
</body>
</html>