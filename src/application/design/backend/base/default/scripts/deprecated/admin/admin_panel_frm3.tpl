<br >
<center>
<table width="300">
<form action="" method="get">
	<input type="hidden" name="s" value="<?php echo $this->getData('csrf_hack')?>" />
	<tr>
	  <td class="c" colspan="6"><?php echo $this->getData('adm_mod_level')?></td>
	</tr>
	<tr>
       <th><?php echo $this->getData('adm_player_nm')?></th>
	  <th><input type="text" name="player" style="width:150"></th>
	</tr>
	<tr>
	  <th colspan="2"><select name="authlvl"><?php echo $this->getData('adm_level_lst')?></select></th>
    </tr>
	<tr>
	  <th colspan="2"><input type="submit" value="<?php echo $this->getData('adm_bt_change')?>"></th>
	  </tr>
<input type="hidden" name="result" value="usr_level">
</form>
</table>
</center>