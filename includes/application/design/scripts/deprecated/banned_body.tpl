<br>
<table width="600" style="color:#FFFFFF">
<tr>
	<td class="c" colspan="6"><?php echo $this->getData('ban_title')?></td>
</tr><tr>
	<th><?php echo $this->getData('ban_name')?></th>
	<th><?php echo $this->getData('ban_reason')?></th>
	<th><?php echo $this->getData('ban_from')?></th>
	<th><?php echo $this->getData('ban_to')?></th>
	<th><?php echo $this->getData('ban_by')?></th>
</tr>
<?php echo $this->getData('banned')?>
</table>