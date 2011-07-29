<tr>
	<th><a href="../messages.php?mode=write&id=<?php echo $this->getData('adm_ov_data_id')?>"><img src="<?php echo $this->getData('dpath')?>img/<?php echo $this->getData('adm_ov_data_pict')?>" alt="<?php echo $this->getData('adm_ov_altpm')?>" title="<?php echo $this->getData('adm_ov_wrtpm')?>" border="0"></a></th>
	<th><a href= # title="<?php echo $this->getData('adm_ov_data_agen')?>"><?php echo $this->getData('adm_ov_data_name')?> (<?php echo $this->getData('usr_s_id')?>)</a></th>
	<th><a style="color:<?php echo $this->getData('adm_ov_data_clip')?>;" href="http://network-tools.com/default.asp?prog=trace&host=<?php echo $this->getData('adm_ov_data_adip')?>">[<?php echo $this->getData('adm_ov_data_adip')?>]</a></th>
	<th><?php echo $this->getData('adm_ov_data_ally')?></th>
	<th><?php echo $this->getData('adm_ov_data_point')?></th>
	<th><?php echo $this->getData('adm_ov_data_activ')?></th>
	<th><a href="mailto:<?php echo $this->getData('usr_email')?>"><?php echo $this->getData('usr_email')?></a></th>
	<th><?php echo $this->getData('usr_xp_raid')?></th>
	<th><?php echo $this->getData('usr_xp_min')?></th>
	<th><?php echo $this->getData('state_vacancy')?></th>
	<th><?php echo $this->getData('is_banned')?></th>
		<th>[<a href="../galaxy.php?mode=0&galaxy=<?php echo $this->getData('usr_planet_gal')?>&system=<?php echo $this->getData('usr_planet_sys')?>"><?php echo $this->getData('usr_planet_gal')?>:<?php echo $this->getData('usr_planet_sys')?>:<?php echo $this->getData('usr_planet_pos')?></a>]</th>
			<th><?php echo $this->getData('current_page')?></th>
</tr>