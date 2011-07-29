<br><br>
<h2><?php echo $this->getData('adm_ul_title')?></h2>
<table width="569" style="color:#FFFFFF">
<tr>
	<td class="c" colspan="10"><?php echo $this->getData('adm_ul_ttle2')?></td>
</tr>
<tr>
	<th><a href="?cmd=sort&type=id"><?php echo $this->getData('adm_ul_id')?></a></th>
	<th><a href="?cmd=sort&type=username"><?php echo $this->getData('adm_ul_name')?></a></th>
	<th><a href="?cmd=sort&type=email"><?php echo $this->getData('adm_ul_mail')?></a></th>
	<th><a href="?cmd=sort&type=ip_at_reg"><?php echo $this->getData('adm_ul_data_ip_reg')?></a></th>
	<th><a href="?cmd=sort&type=user_lastip"><?php echo $this->getData('adm_ul_adip')?></a></th>
	<th><a href="?cmd=sort&type=register_time"><?php echo $this->getData('adm_ul_regd')?></a></th>
	<th><a href="?cmd=sort&type=onlinetime"><?php echo $this->getData('adm_ul_lconn')?></a></th>
	<th><a href="?cmd=sort&type=bana"><?php echo $this->getData('adm_ul_bana')?></a></th>
	<th><?php echo $this->getData('adm_ul_detai')?></th>
	<th><?php echo $this->getData('adm_ul_actio')?></th>

</tr>
<?php echo $this->getData('adm_ul_table')?>
<tr>
<th class="b" colspan="10"><?php echo $this->getData('adm_ul_count')?><?php echo $this->getData('adm_ul_playe')?></th>
</tr>
</table>