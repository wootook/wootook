<br><br>
<h2><?php echo $this->getData('adm_er_ttle')?></h2>
<table width="520">
<tr>
<td class="c" colspan="4"><?php echo $this->getData('adm_er_list')?> [<a href="?deleteall=yes"><?php echo $this->getData('adm_er_clear')?></a>]</td>
</tr>
<tr>
<th width="25"><?php echo $this->getData('adm_er_idmsg')?></th>
<th width="170"><?php echo $this->getData('adm_er_type')?></th>
<th width="230"><?php echo $this->getData('adm_er_time')?></th>
<th width="95"><?php echo $this->getData('adm_er_delete')?></th>
</tr>
<?php echo $this->getData('errors_list')?>
</table>