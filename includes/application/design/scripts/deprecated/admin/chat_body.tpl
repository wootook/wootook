<br><br>
<h2><?php echo $this->getData('adm_ch_ttle')?></h2>
<table width="400">
<tr>
<td class="c" colspan="4"><?php echo $this->getData('adm_ch_list')?> [<a href="?deleteall=yes">vider</a>]</td>
</tr>
<tr>
<th><?php echo $this->getData('adm_ch_time')?></th>
<th><?php echo $this->getData('adm_ch_play')?></th>
<th><?php echo $this->getData('adm_ch_msg')?></th>
<th><?php echo $this->getData('adm_ch_delet')?></th>
</tr>
<?php echo $this->getData('msg_list')?>
</table>