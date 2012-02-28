<br><br>
<h2><?php echo $this->getData('adm_pl_title')?></h2>
<table width="519">
	<tr><td class="c" colspan="4"><?php echo $this->getData('adm_pl_activ')?></td></tr>
	<tr>
	<th><?php echo $this->getData('adm_pl_name')?></th>
	<th><?php echo $this->getData('adm_pl_posit')?></th>
	<th><?php echo $this->getData('adm_pl_point')?></th>
	<th><?php echo $this->getData('adm_pl_since')?></th>
	</tr>
	<?php echo $this->getData('online_list')?>
</table>