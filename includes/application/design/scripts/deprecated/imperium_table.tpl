<center>
<br><br>
<table border="0" cellpadding="0" cellspacing="1" width="750">
<tbody>
<tr height="20" valign="left">
	<td class="c" colspan="<?php echo $this->getData('mount')?>"><?php echo $this->getData('imperium_vision')?></td>
</tr><tr height="75">
	<th width="75"></th>
	<?php echo $this->getData('file_images')?>
</tr><tr height="20">
	<th width="75"><?php echo $this->getData('name')?></th>
	<?php echo $this->getData('file_names')?>
</tr><tr height="20">
	<th width="75"><?php echo $this->getData('coordinates')?></th>
	<?php echo $this->getData('file_coordinates')?>
</tr><tr height="20">
	<th width="75"><?php echo $this->getData('fields')?></th>
	<?php echo $this->getData('file_fields')?>
</tr><tr height="20">
	<td class="c" colspan="<?php echo $this->getData('mount')?>" align="left"><?php echo $this->getData('resources')?></td>
</tr><tr height="20">
	<th width="75"><?php echo $this->getData('metal')?></th>
	<?php echo $this->getData('file_metal')?>
</tr><tr height="20">
	<th width="75"><?php echo $this->getData('crystal')?></th>
	<?php echo $this->getData('file_crystal')?>
</tr><tr height="20">
	<th width="75"><?php echo $this->getData('deuterium')?></th>
	<?php echo $this->getData('file_deuterium')?>
</tr><tr height="20">
	<th width="75"><?php echo $this->getData('energy')?></th>
	<?php echo $this->getData('file_energy')?>
</tr><tr height="20">
	<td class="c" colspan="<?php echo $this->getData('mount')?>" align="left"><?php echo $this->getData('buildings')?></td>
</tr>
	<!-- Lista de edificios -->
	<?php echo $this->getData('building_row')?>
<tr height="20">
	<td class="c" colspan="<?php echo $this->getData('mount')?>" align="left"><?php echo $this->getData('investigation')?></td>
</tr>
	<!-- Lista de tecnologias -->
	<?php echo $this->getData('technology_row')?>
<tr height="20">
	<td class="c" colspan="<?php echo $this->getData('mount')?>" align="left"><?php echo $this->getData('ships')?></td>
</tr>
	<!-- Lista de naves -->
	<?php echo $this->getData('fleet_row')?>
<tr height="20">
	<td class="c" colspan="<?php echo $this->getData('mount')?>" align="left"><?php echo $this->getData('defense')?></td>
</tr>
	<!-- Lista de defensas -->
	<?php echo $this->getData('defense_row')?>
</tbody>
</table>
<script type="text/javascript" src="scripts/wz_tooltip.js"></script>
</center>