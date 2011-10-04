<br><br>
<h2><?php echo $this->getData('adm_ov_title')?></h2>
<table width="600">
<tr>
	<td class="c" colspan="2"><?php echo $this->getData('adm_ov_infos')?></td>
</tr><tr>
	<td class="b" style="color:#FFFFFF"><?php echo $this->getData('adm_ov_yourv')?>: <strong><?php echo $this->getData('adm_ov_data_yourv')?></strong></td>
	<td class="b" style="color:#FFFFFF"><?php echo $this->getData('adm_ov_lastv')?>: <b><a style="color:orange;" href="http://www.xnova-ng.org/"><?php echo $this->getData('adm_ov_here')?></a></b></td>
</tr>
</table>
<br>
<table width="600">
<tr>
	<td class="c" colspan="13"><?php echo $this->getData('adm_ov_onlin')?></td>
</tr>
<tr>
	<th><a href="?cmd=sort&type=id"><?php echo $this->getData('adm_ul_id')?></a></th>
	<th><a href="?cmd=sort&type=username"><?php echo $this->getData('adm_ul_name')?></a></th>
	<th><a href="?cmd=sort&type=user_lastip"><?php echo $this->getData('adm_ul_adip')?></a></th>
	<th><a href="?cmd=sort&type=ally_name"><?php echo $this->getData('adm_ov_ally')?></a></th>
	<th><?php echo $this->getData('adm_ov_point')?></th>
	<th><a href="?cmd=sort&type=onlinetime"><?php echo $this->getData('adm_ov_activ')?></a></th>
	<th><?php echo $this->getData('usr_email')?></th>
	<th><?php echo $this->getData('xp_raid')?></th>
	<th><?php echo $this->getData('xp_min')?></th>
	<th><?php echo $this->getData('lang_vacancy')?></th>
	<th><?php echo $this->getData('banned_lang')?></th>
	<th><?php echo $this->getData('usr_current_planet')?></th>
	<th><?php echo $this->getData('usr_current_page')?></th>
</tr>
	<?php echo $this->getData('adm_ov_data_table')?>
<tr>
	<th class="b" colspan="13"><?php echo $this->getData('adm_ov_count')?>: <?php echo $this->getData('adm_ov_data_count')?></th>
</tr>
</table>