<center>
<br><br>
<form action="<?php echo $this->getData('PHP_SELF')?>?mode=change" method="post">
<table width="519">
<tbody>
<?php echo $this->getData('opt_adm_frame')?>
<tr>
	<td class="c" colspan="2"><?php echo $this->getData('userdata')?></td>
</tr><tr>
	<th><?php echo $this->getData('username')?></th>
	<th><input name="db_character" size="20" value="<?php echo $this->getData('opt_usern_data')?>" type="text"></th>
</tr><tr>
	<th><?php echo $this->getData('lastpassword')?></th>
	<th><input name="db_password" size="20" value="" type="password" autocomplete="off"></th>
</tr><tr>
	<th><?php echo $this->getData('newpassword')?></th>
	<th><input name="newpass1"    size="20" maxlength="40" type="password"></th>
</tr><tr>
	<th><?php echo $this->getData('newpasswordagain')?></th>
	<th><input name="newpass2"    size="20" maxlength="40" type="password"></th>
</tr><tr>
	<th><a title="<?php echo $this->getData('emaildir_tip')?>"><?php echo $this->getData('emaildir')?></a></th>
	<th><input name="db_email" maxlength="100" size="20" value="<?php echo $this->getData('opt_mail1_data')?>" type="text"></th>
</tr><tr>
	<th><?php echo $this->getData('permanentemaildir')?></th>
	<th><?php echo $this->getData('opt_mail2_data')?></th>
</tr><tr>
	<th colspan="2"></th>
</tr><tr>
	<td class="c" colspan="2"><?php echo $this->getData('general_settings')?></td>
</tr><tr>
	<th><?php echo $this->getData('opt_lst_ord')?></th>
	<th>
		<select name="settings_sort">
		<?php echo $this->getData('opt_lst_ord_data')?>
		</select>
	</th>
</tr><tr>
	<th><?php echo $this->getData('opt_lst_cla')?></th>
	<th>
		<select name="settings_order">
		<?php echo $this->getData('opt_lst_cla_data')?>
		</select>
	</th>
</tr><tr>
	<th><?php echo $this->getData('skins_example')?><br> <a href="http://80.237.203.201/download/" target="_blank"><?php echo $this->getData('Download')?></a></th>
	<th><input name="dpath" maxlength="80" size="40" value="<?php echo $this->getData('opt_dpath_data')?>" type="text"> <br>
		<select name="dpaths" size="1">
			<option selected="selected">  </option>
			<?php echo $this->getData('opt_lst_skin_data')?>
		</select>
	</th>
</tr><tr>
	<th><?php echo $this->getData('opt_chk_skin')?></th>
	<th><input name="design"<?php echo $this->getData('opt_sskin_data')?> type="checkbox"></th>
</tr><tr>
	<th><?php echo $this->getData('avatar_example')?><br> <a href="http://www.google.com.ar/imghp" target="_blank"><?php echo $this->getData('Search')?></a></th>
	<th><input name="avatar" maxlength="80" size="40" value="<?php echo $this->getData('opt_avata_data')?>" type="text"></th>
</tr><tr>
	<th><a title="<?php echo $this->getData('untoggleip_tip')?>"><?php echo $this->getData('untoggleip')?></a></th>
	<th><input name="noipcheck"<?php echo $this->getData('opt_noipc_data')?> type="checkbox" /></th>
</tr><tr>
	<td class="c" colspan="2"><?php echo $this->getData('galaxyvision_options')?></td>
</tr><tr>
	<th><a title="<?php echo $this->getData('spy_cant_tip')?>"><?php echo $this->getData('spy_cant')?></a></th>
	<th><input name="spio_anz" maxlength="2" size="2" value="<?php echo $this->getData('opt_probe_data')?>" type="text"></th>
</tr><tr>
	<th><?php echo $this->getData('tooltip_time')?></th>
	<th><input name="settings_tooltiptime" maxlength="2" size="2" value="<?php echo $this->getData('opt_toolt_data')?>" type="text"> <?php echo $this->getData('seconds')?></th>
</tr><tr>
	<th><?php echo $this->getData('mess_ammount_max')?></th>
	<th><input name="settings_fleetactions" maxlength="2" size="2" value="<?php echo $this->getData('opt_fleet_data')?>" type="text"></th>
</tr><tr>
	<th><?php echo $this->getData('show_ally_logo')?></th>
	<th><input name="settings_allylogo"<?php echo $this->getData('opt_allyl_data')?> type="checkbox" /></th>
</tr><tr>
	<th><?php echo $this->getData('shortcut')?></th>
	<th><?php echo $this->getData('show')?></th>
</tr><tr>
	<th><img src="<?php echo $this->getData('dpath')?>img/e.gif" alt="">   <?php echo $this->getData('spy')?></th>
	<th><input name="settings_esp"<?php echo $this->getData('user_settings_esp')?> type="checkbox" /></th>
</tr><tr>
	<th><img src="<?php echo $this->getData('dpath')?>img/m.gif" alt="">   <?php echo $this->getData('write_a_messege')?></th>
	<th><input name="settings_wri"<?php echo $this->getData('user_settings_wri')?> type="checkbox" /></th>
</tr><tr>
	<th><img src="<?php echo $this->getData('dpath')?>img/b.gif" alt="">   <?php echo $this->getData('add_to_buddylist')?></th>
	<th><input name="settings_bud"<?php echo $this->getData('user_settings_bud')?> type="checkbox" /></th>
</tr><tr>
	<th><img src="<?php echo $this->getData('dpath')?>img/r.gif" alt="">   <?php echo $this->getData('attack_with_missile')?></th>
	<th><input name="settings_mis"<?php echo $this->getData('user_settings_mis')?> type="checkbox" /></th>
</tr><tr>
	<th><img src="<?php echo $this->getData('dpath')?>img/s.gif" alt="">   <?php echo $this->getData('show_report')?></th>
	<th><input name="settings_rep"<?php echo $this->getData('user_settings_rep')?> type="checkbox" /></th>
</tr><tr>
	<td class="c" colspan="2"><?php echo $this->getData('delete_vacations')?></td>
</tr><tr>
	<th><a title="<?php echo $this->getData('vacations_tip')?>"><?php echo $this->getData('mode_vacations')?></a></th>
	<th><input name="urlaubs_modus"<?php echo $this->getData('opt_modev_data')?> type="checkbox" /></th>
</tr><tr>
	<th><a title="<?php echo $this->getData('deleteaccount_tip')?>"><?php echo $this->getData('deleteaccount')?></a></th>
	<th><input name="db_deaktjava"<?php echo $this->getData('opt_delac_data')?> type="checkbox" /></th>
</tr><tr>
	<th colspan="2"><input value="<?php echo $this->getData('save_settings')?>" type="submit"></th>
</tr>
</tbody>
</table>
</form>
</center>