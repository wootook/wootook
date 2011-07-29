<center>
<br><br>
<table width="569">
<tbody>
<tr>
	<td colspan="3" class="c"><b><?php echo $this->getData('ctc_title')?></b></td>
</tr><tr>
	<th colspan="3">
		<font color="orange"><?php echo $this->getData('ctc_intro')?></font>
	</th>
</tr><tr>
	<th><font color="lime"><?php echo $this->getData('ctc_name')?></font></th>
	<th><font color="lime"><?php echo $this->getData('ctc_rank')?></font></th>
	<th><font color="lime"><?php echo $this->getData('ctc_mail')?></font></th>
</tr>
	<?php echo $this->getData('ctc_admin_list')?>
<tr>
</tr>
</tbody>
</table>
</center>