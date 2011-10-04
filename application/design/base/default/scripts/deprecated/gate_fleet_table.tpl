<br>
<?php echo $this->getData('gate_time_script')?>
<form action="jumpgate.php" method="post">
<table border="1">
<tbody>
<tr>
	<th><?php echo $this->getData('gate_start_moon')?></th>
	<th><?php echo $this->getData('gate_start_link')?></th>
</tr><tr>
	<th><?php echo $this->getData('gate_dest_moon')?></th>
	<th>
		<select name="jmpto">
		<?php echo $this->getData('gate_dest_moons')?>
		</select>
	</th>
</tr>
</tbody>
</table>
<table width="519">
<tbody>
<tr>
	<td class="c" colspan="2"><?php echo $this->getData('gate_use_gate')?> : <?php echo $this->getData('gate_ship_sel')?></td>
</tr>
<tr>
	<th class="l" colspan="2" align="right">
	<table width="100%">
	<tbody>
	<tr>
		<td style=\"background-color: transparent;\" align="right"><?php echo $this->getData('gate_wait_time')?></td>
	</tr>
	</tbody>
	</table>
	</th>
</tr>
<?php echo $this->getData('gate_fleet_rows')?>
<tr>
	<th colspan="2"><input value="<?php echo $this->getData('gate_jump_btn')?>" type="submit"></th>
</tr>
</tbody>
<?php echo $this->getData('gate_script_go')?>
</table>