<br>
<form action="" method="post">
<table width="569">
<tbody>
<tr>
	<td class="c" colspan="5"><?php echo $this->getData('Production_of_resources_in_the_planet')?></td>
</tr><tr>
	<th height="22"></th>
	<th width="60"><?php echo $this->getData('Metal')?></th>
	<th width="60"><?php echo $this->getData('Crystal')?></th>
	<th width="60"><?php echo $this->getData('Deuterium')?></th>
	<th width="60"><?php echo $this->getData('Energy')?></th>
</tr><tr>
	<th height="22"><?php echo $this->getData('Basic_income')?></th>
	<td class="k"><?php echo $this->getData('metal_basic_income')?></td>
	<td class="k"><?php echo $this->getData('crystal_basic_income')?></td>
	<td class="k"><?php echo $this->getData('deuterium_basic_income')?></td>
	<td class="k"><?php echo $this->getData('energy_basic_income')?></td>
</tr>
<?php echo $this->getData('resource_row')?>
<tr>
	<th height="22"><?php echo $this->getData('Stores_capacity')?></th>
	<td class="k"><?php echo $this->getData('metal_max')?></td>
	<td class="k"><?php echo $this->getData('crystal_max')?></td>
	<td class="k"><?php echo $this->getData('deuterium_max')?></td>
	<td class="k"><font color="#00ff00">-</font></td>
	<td class="k"><input name="action" value="<?php echo $this->getData('Calcule')?>" type="submit"></td>
</tr><tr>
	<th height="22">Total:</th>
	<td class="k"><?php echo $this->getData('metal_total')?></td>
	<td class="k"><?php echo $this->getData('crystal_total')?></td>
	<td class="k"><?php echo $this->getData('deuterium_total')?></td>
	<td class="k"><?php echo $this->getData('energy_total')?></td>
</tr>
</tbody>
</table>
</form>
<br>
<table width="569">
<tbody>
<tr>
	<td class="c" colspan="4"><?php echo $this->getData('Widespread_production')?></td>
</tr><tr>
	<th>&nbsp;</th>
	<th><?php echo $this->getData('Daily')?></th>
	<th><?php echo $this->getData('Weekly')?></th>
	<th><?php echo $this->getData('Monthly')?></th>
</tr><tr>
	<th><?php echo $this->getData('Metal')?></th>
	<th><?php echo $this->getData('daily_metal')?></th>
	<th><?php echo $this->getData('weekly_metal')?></th>
	<th><?php echo $this->getData('monthly_metal')?></th>
</tr><tr>
	<th><?php echo $this->getData('Crystal')?></th>
	<th><?php echo $this->getData('daily_crystal')?></th>
	<th><?php echo $this->getData('weekly_crystal')?></th>
	<th><?php echo $this->getData('monthly_crystal')?></th>
</tr><tr>
	<th><?php echo $this->getData('Deuterium')?></th>
	<th><?php echo $this->getData('daily_deuterium')?></th>
	<th><?php echo $this->getData('weekly_deuterium')?></th>
	<th><?php echo $this->getData('monthly_deuterium')?></th>
</tr>
</tbody>
</table>
<br>
<table width="569">
<tbody>
<tr>
	<td class="c" colspan="3"><?php echo $this->getData('Storage_state')?></td>
</tr><tr>
	<th><?php echo $this->getData('Metal')?></th>
	<th><?php echo $this->getData('metal_storage')?></th>
	<th width="250">
		<div style="border: 1px solid rgb(153, 153, 255); width: 250px;">
		<div id="AlmMBar" style="background-color: <?php echo $this->getData('metal_storage_barcolor')?>; width: <?php echo $this->getData('metal_storage_bar')?>px;">
		&nbsp;
		</div>
		</div>
	</th>
</tr><tr>
	<th><?php echo $this->getData('Crystal')?></th>
	<th><?php echo $this->getData('crystal_storage')?></th>
	<th width="250">
		<div style="border: 1px solid rgb(153, 153, 255); width: 250px;">
		<div id="AlmCBar" style="background-color: <?php echo $this->getData('crystal_storage_barcolor')?>; width: <?php echo $this->getData('crystal_storage_bar')?>px; opacity: 0.98;">
		&nbsp;
		</div>
		</div>
	</th>
</tr><tr>
	<th><?php echo $this->getData('Deuterium')?></th>
	<th><?php echo $this->getData('deuterium_storage')?></th>
	<th width="250">
		<div style="border: 1px solid rgb(153, 153, 255); width: 250px;">
		<div id="AlmDBar" style="background-color: <?php echo $this->getData('deuterium_storage_barcolor')?>; width: <?php echo $this->getData('deuterium_storage_bar')?>px;">
		&nbsp;
		</div>
		</div>
	</th>
</tr>
</tbody>
</table>
<br>