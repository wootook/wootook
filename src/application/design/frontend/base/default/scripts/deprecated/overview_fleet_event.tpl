<tr class="<?php echo $this->getData('fleet_status')?>">
	<?php echo $this->getData('fleet_javai')?>
	<th>
		<div id="bxx<?php echo $this->getData('fleet_order')?>" class="z">-</div>
		<font color="lime"><?php echo $this->getData('fleet_time')?></font>
	</th><th colspan="3">
		<span class="<?php echo $this->getData('fleet_status')?> <?php echo $this->getData('fleet_prefix')?><?php echo $this->getData('fleet_style')?>"><?php echo $this->getData('fleet_descr')?></span>
	</th>
	<?php echo $this->getData('fleet_javas')?>
</tr>