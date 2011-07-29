<tr>
	<th><a href="infos.php?gid=<?php echo $this->getData('fleet_id')?>"><?php echo $this->getData('fleet_name')?></a> (<?php echo $this->getData('fleet_max')?> <?php echo $this->getData('gate_ship_dispo')?>)</th>
	<th><input tabindex="<?php echo $this->getData('idx')?>" name="c<?php echo $this->getData('fleet_id')?>" size="7" maxlength="7" value="0" type="text"></th>
</tr>