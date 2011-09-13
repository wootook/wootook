<script language="JavaScript" type="text/javascript" src="scripts/time.js"></script>
<br>
<table width="519">
	<tr><td class="c" colspan="4"><a href="overview.php?action=rename" title="<?php echo $this->getData('Planet_menu')?>"><?php echo $this->getData('Planet')?> "<?php echo $this->getData('planet_name')?>"</a> (<?php echo $this->getData('user_username')?>)</td></tr>
	<?php echo $this->getData('Have_new_message')?>
	<?php echo $this->getData('Have_new_level_mineur')?>
	<?php //echo $this->getData('Have_new_level_raid')?>
	<tr><th><?php echo $this->getData('Server_time')?></th>
	<th colspan="3"><div id="dateheure"></div></th></tr>
	<tr><th><?php echo $this->getData('MembersOnline')?></th>
	<th colspan="3"><?php echo $this->getData('NumberMembersOnline')?></th></tr>
	<?php echo $this->getData('NewsFrame')?>
	<tr><td colspan="4" class="c"><?php echo $this->getData('Events')?></td>
	</tr>
	<?php echo $this->getData('fleet_list')?>
	<tr><th><?php echo $this->getData('moon_img')?><br><?php echo $this->getData('moon')?></th>
	<th colspan="2"><img src="<?php echo $this->getData('dpath')?>graphics/planeten/<?php echo $this->getData('planet_image')?>.jpg" height="200" width="200"><br><?php echo $this->getData('building')?></th>
	<th class="s"><table class="s" align="top" border="0"><tr><?php echo $this->getData('anothers_planets')?></tr></table></th></tr>
	<tr><th><?php echo $this->getData('Diameter')?></th>
	<th colspan="3"><?php echo $this->getData('planet_diameter')?> km (<a title="<?php echo $this->getData('Developed_fields')?>"><?php echo $this->getData('planet_field_current')?></a> / <a title="<?php echo $this->getData('max_developed_fields')?>"><?php echo $this->getData('planet_field_max')?></a> <?php echo $this->getData('fields')?>)</th></tr>
	<th><?php echo $this->getData('Developed_fields')?></th>
	<th colspan="3" align="center"><div  style="border: 1px solid rgb(153, 153, 255); width: 400px;"><div  id="CaseBarre" style="background-color: <?php echo $this->getData('case_barre_barcolor')?>; width: <?php echo $this->getData('case_barre')?>%;"><font color="#CCF19F"><?php echo $this->getData('case_pourcentage')?></font></div></th>
	<tr><tr><th><?php echo $this->getData('ov_off_level')?></th><th colspan="3" align="center"><table border="0" width="100%"><tbody><tr>
		<td align="center" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('ov_off_mines')?> : <?php echo $this->getData('lvl_minier')?></b></td>
		<td align="center" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('ov_off_raids')?> : <?php echo $this->getData('lvl_raid')?></b></td></tr></tbody></table></th></tr>
	<tr><th><?php echo $this->getData('ov_off_expe')?></th>
	<th colspan="3" align="center"><table border="0" width="100%"><tbody><tr>
		<td align="center" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('ov_off_mines')?> : <?php echo $this->getData('xpminier')?> / <?php echo $this->getData('lvl_up_minier')?></b></td>
		<td align="center" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('ov_off_raids')?> : <?php echo $this->getData('xpraid')?> / <?php echo $this->getData('lvl_up_raid')?></b></td></tr></tbody></table></th></tr>
	<th><?php echo $this->getData('Temperature')?></th>
	<th colspan="3"><?php echo $this->getData('ov_temp_from')?> <?php echo $this->getData('planet_temp_min')?><?php echo $this->getData('ov_temp_unit')?> <?php echo $this->getData('ov_temp_to')?> <?php echo $this->getData('planet_temp_max')?><?php echo $this->getData('ov_temp_unit')?></th></tr>
	<tr><th><?php echo $this->getData('Position')?></th>
	<th colspan="3"><a href="galaxy.php?mode=0&galaxy=<?php echo $this->getData('galaxy_galaxy')?>&system=<?php echo $this->getData('galaxy_system')?>">[<?php echo $this->getData('galaxy_galaxy')?>:<?php echo $this->getData('galaxy_system')?>:<?php echo $this->getData('galaxy_planet')?>]</a></th></tr>
	<tr><th><?php echo $this->getData('ov_local_cdr')?></th>
	<th colspan="3"><?php echo $this->getData('Metal')?> : <?php echo $this->getData('metal_debris')?> / <?php echo $this->getData('Crystal')?> : <?php echo $this->getData('crystal_debris')?><?php echo $this->getData('get_link')?></th></tr>
	<tr><th><?php echo $this->getData('Points')?></th>
	<th colspan="3"><table border="0" width="100%"><tbody><tr>
		<td align="right" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('ov_pts_build')?> :</b></td>
		<td align="left" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('user_points')?></b></td></tr>
		<tr><td align="right" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('ov_pts_fleet')?> :</b></td>
		<td align="left" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('user_fleet')?></b></td></tr>
		<tr><td align="right" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('ov_pts_reche')?> :</b></td>
		<td align="left" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('player_points_tech')?></b></td></tr>
		<tr><td align="right" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('ov_pts_total')?> :</b></td>
		<td align="left" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('total_points')?></b></td></tr>
		<tr><td colspan="2" align="center" width="100%" style="background-color: transparent;"><b>(<?php echo $this->getData('Rank')?> <a href="stat.php?range=<?php echo $this->getData('u_user_rank')?>"><?php echo $this->getData('user_rank')?></a> <?php echo $this->getData('of')?> <?php echo $this->getData('max_users')?>)</b></td></tr></tbody></table></th></tr>
	<th><?php echo $this->getData('Raids')?></th>
	<th colspan="3"><table border="0" width="100%"><tbody><tr>
		<td align="right" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('NumberOfRaids')?> :</b></td>
		<td align="left" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('raids')?></b></td></tr>
		<tr><td align="right" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('RaidsWin')?> :</b></td>
		<td align="left" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('raidswin')?></b></td></tr></tr>
		<tr><td align="right" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('RaidsLoose')?> :</b></td>
		<td align="left" width="50%" style="background-color: transparent;"><b><?php echo $this->getData('raidsloose')?></b></td></tr></tbody></table></th></tr>
	<?php echo $this->getData('bannerframe')?>
	<?php echo $this->getData('ExternalTchatFrame')?>
</table>
<br>
<?php echo $this->getData('ClickBanner')?>
<br>