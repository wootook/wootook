<br><br>
<h2><?php echo $this->getData('adm_mt_title')?></h2>
<table width="570" style="color:#FFFFFF">
<tr>
	<td class="c" colspan="9"><?php echo $this->getData('adm_mt_list')?></td>
</tr>
<tr>
	<th width="20%"><?php echo $this->getData('adm_mt_player')?></th>
	<th width="80%"><?php echo $this->getData('adm_mt_text')?></th>
</tr>
<?php echo $this->getData('adm_mt_table')?>
<tr>
<th class="b" colspan="9"><?php echo $this->getData('adm_mt_count')?> <?php echo $this->getData('adm_mt_multi')?></th>
</tr>
</table>