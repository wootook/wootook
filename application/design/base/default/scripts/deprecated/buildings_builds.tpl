<center>
<br />
<?php echo $this->getData('BuildListScript')?>
<table width=530>
	<?php echo $this->getData('BuildList')?>
	<tr>
		<th ><?php echo $this->getData('bld_usedcells')?></th>
		<th colspan="2" >
			<font color="#00FF00"><?php echo $this->getData('planet_field_current')?></font> / <font color="#FF0000"><?php echo $this->getData('planet_field_max')?></font> <?php echo $this->getData('bld_theyare')?> <?php echo $this->getData('field_libre')?> <?php echo $this->getData('bld_cellfree')?>
		</th >
	</tr>
	<?php echo $this->getData('BuildingsList')?>
</table>
<br />
</center>