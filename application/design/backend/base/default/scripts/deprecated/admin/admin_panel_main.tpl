<br><br>
<h2><?php echo $this->getData('adm_panel_mnu')?></h2>
<table width="555">
	<tr>
	  <td class="c" colspan="6"><?php echo $this->getData('adm_panel_ttl')?></td>
	</tr>
	<tr>
       <th><a href="?action=usr_search"><?php echo $this->getData('adm_search_pl')?></a></th>
	  <th><a href="?action=ip_search"><?php echo $this->getData('adm_search_ip')?></a></th>
	  <th><a href="?action=usr_data"><?php echo $this->getData('adm_stat_play')?></a></th>
	  <th><a href="?action=usr_level"><?php echo $this->getData('adm_mod_level')?></a></th>
	</tr>
</table>
<?php echo $this->getData('adm_sub_form1')?>
<?php echo $this->getData('adm_sub_form2')?>
<?php echo $this->getData('adm_sub_form3')?>
