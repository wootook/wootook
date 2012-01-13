<tr>
	<th height="22"><?php echo $this->getData('type')?> (<?php echo $this->getData('level')?> <?php echo $this->getData('level_type')?>)</th>
	<th><font color="#ffffff"><?php echo $this->getData('metal_type')?></font></th>
	<th><font color="#ffffff"><?php echo $this->getData('crystal_type')?></font></th>
	<th><font color="#ffffff"><?php echo $this->getData('deuterium_type')?></font></th>
	<th><font color="#ffffff"><?php echo $this->getData('energy_type')?></font></th>
	<th>
		<?php if ($this->getData('option') != ''):?>
		<select name="<?php echo $this->getData('name')?>" size="1">
		<?php echo $this->getData('option')?>
		</select>
		<?php endif?>
	</th>
</tr>