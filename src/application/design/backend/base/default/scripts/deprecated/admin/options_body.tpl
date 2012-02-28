<br /><br />
<h2><?php echo $this->getData('adm_opt_title')?></h2>
<form action="" method="post">
<input type="hidden" name="opt_save" value="1">
<table width="519" style="color:#FFFFFF">
<tbody>
<tr>
	<td class="c" colspan="2"><?php echo $this->getData('adm_opt_game_settings')?></td>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_game_name')?></th>
	<th><input name=game_name size=20 value=<?php echo $this->getData('game_name')?> type=text></th>
</tr>
<tr>
	<th><?php echo $this->getData('adm_opt_menu_link_enable')?></th>
	<th><input name="enable_link_" size="20" value="<?php echo $this->getData('enable_link')?>" type="text"></th>
</tr>
<tr>
	<th><?php echo $this->getData('adm_opt_menu_link_text')?></th>
	<th><input name="name_link_" size="20" value="<?php echo $this->getData('name_link')?>" type="text"></th>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_menu_link_url')?></th>
	<th><input name="url_link_" size="20" value="<?php echo $this->getData('url_link')?>" type="text"></th>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_game_gspeed')?></th>
	<th><input name="game_speed" size="2" value="<?php echo $this->getData('game_speed')?>" type="text"></th>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_game_fspeed')?></th>
	<th><input name="fleet_speed" size="2" value="<?php echo $this->getData('fleet_speed')?>" type="text"></th>
</tr><tr>
	<th><?php echo $this->getData('stat_settings_desc')?></th>
	<th><?php echo $this->getData('stat_desc')?><input name="stat_settings" size="2" value="<?php echo $this->getData('stat_settings')?>" type="text"><?php echo $this->getData('stat_units')?></th>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_game_pspeed')?></th>
	<th><input name="resource_multiplier" maxlength="8" size="10" value="<?php echo $this->getData('resource_multiplier')?>" type="text"></th>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_game_forum')?><br /></th>
	<th><input name="forum_url" size="40" maxlength="254" value="<?php echo $this->getData('forum_url')?>" type="text"></th>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_game_online')?><br /></th>
	<th><input name="closed"<?php echo $this->getData('closed')?> type="checkbox" /></th>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_game_offreaso')?><br /></th>
	<th><textarea name="close_reason" cols="80" rows="5" size="80" ><?php echo $this->getData('close_reason')?></textarea></th>
</tr><tr>
	<td class="c" colspan="2"><?php echo $this->getData('messages_settings')?></td>
</tr><tr>
	<th><?php echo $this->getData('bbcode_settings')?><br /></th>
	<th><input name="bbcode_field" size="1" maxlength="254" value="<?php echo $this->getData('enable_bbcode')?>" type="text"></th>
</tr>
<tr>
	<td class="c" colspan="2"><?php echo $this->getData('multi_bot_settings')?></td>
</tr><tr>
	<th><?php echo $this->getData('bot_active')?></th>
	<th><input name="bot_enable" size="1" value="<?php echo $this->getData('enable_bot')?>" type="text"></th>
</tr><tr>
	<th><?php echo $this->getData('bot_name_multi')?></th>
<th><textarea name="name_bot" cols="1" rows="1" size="80" ><?php echo $this->getData('bot_name')?></textarea></th>
</tr><tr>
	<th><?php echo $this->getData('bot_adress_multi')?></th>
<th><textarea name="adress_bot" cols="80" rows="1" size="80" ><?php echo $this->getData('bot_adress')?></textarea></th>
</tr><tr>
	<th><?php echo $this->getData('bot_ban_duration')?></th>
	<th><input name="duration_ban" size="20" value="<?php echo $this->getData('ban_duration')?>" type="text"></th>
</tr><tr>
	<td class="c" colspan="2"><?php echo $this->getData('adm_opt_plan_settings')?></td>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_plan_initial')?></th>
	<th><input name="initial_fields" maxlength="80" size="10" value="<?php echo $this->getData('initial_fields')?>" type="text"> cases</th>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_plan_base_inc')?><?php echo $this->getData('Metal')?></th>
	<th><input name="metal_basic_income" maxlength="2" size="10" value="<?php echo $this->getData('metal_basic_income')?>" type="text"> par heure</th>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_plan_base_inc')?><?php echo $this->getData('Crystal')?></th>
	<th><input name="crystal_basic_income" maxlength="2" size="10" value="<?php echo $this->getData('crystal_basic_income')?>" type="text"> par heure   </th>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_plan_base_inc')?><?php echo $this->getData('Deuterium')?></th>
	<th><input name="deuterium_basic_income" maxlength="2" size="10" value="<?php echo $this->getData('deuterium_basic_income')?>" type="text"> par heure   </th>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_plan_base_inc')?><?php echo $this->getData('Energy')?></th>
	<th><input name="energy_basic_income" maxlength="2" size="10" value="<?php echo $this->getData('energy_basic_income')?>" type="text"> par heure</th>
</tr><tr>
	<td class="c" colspan="2"><?php echo $this->getData('adm_opt_control_pages')?></td>
</tr><tr>
	<th><?php echo $this->getData('enable_the_anounces')?></th>
	<th><input name="enable_announces_" size=1" value="<?php echo $this->getData('enable_announces')?>" type="text"></th>
</tr>
<tr>
	<th><?php echo $this->getData('enable_the_marchand')?></th>
	<th><input name="enable_marchand_" size="1" value="<?php echo $this->getData('enable_marchand')?>" type="text"></th>
</tr><tr>
	<th><?php echo $this->getData('enable_the_notes')?></th>
	<th><input name="enable_notes_" size="1" value="<?php echo $this->getData('enable_notes')?>" type="text"></th>
</tr>
<tr>
	<td class="c" colspan="2"><?php echo $this->getData('adm_opt_game_oth_info')?></td>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_game_oth_bann')?><br /></th>
	<th><input name="bannerframe"<?php echo $this->getData('bannerframe')?> type="checkbox" />(<?php echo $this->getData('adm_opt_warning1')?>)</th>
</tr><tr>
	<th><?php echo $this->getData('adm_opt_game_oth_news')?><br /></th>
	<th><input name="newsframe"<?php echo $this->getData('newsframe')?> type="checkbox" /></th>
</tr><tr>
	<th colspan="2"><textarea name="NewsText" cols="80" rows="5" size="80" ><?php echo $this->getData('NewsTextVal')?></textarea></th>
</tr><tr>
	<th><?php echo $this->__('Enable Google Analytics')?></th>
	<td><input name="ga"<?php echo $this->getData('ga')?> type="checkbox" /></td>
</tr><tr>
	<th><?php echo $this->__('Google Analytics Identifier')?></th>
	<td><input name=ga_id type="text" value="<?php echo $this->escape($this->getData('ga_id'))?>" /></td>
</tr>
<tr>
	<th><?php echo $this->getData('banner')?></th>
	<th><textarea name="banner_source_post" cols="80" rows="1" size="80" ><?php echo $this->getData('banner_source_post')?></textarea></th>


</tr>
<tr>
	<th colspan="2"><img src="<?php echo $this->getData('banner_source_post')?>" alt="<?php echo $this->getData('banner_source_post')?>" title="<?php echo $this->getData('banner_source_post')?>"></th>
</tr>


</tr>

	<th colspan="3"><input value="<?php echo $this->getData('adm_opt_btn_save')?>" type="submit"></th>
</tr>
</tbody>
</table>
</form>