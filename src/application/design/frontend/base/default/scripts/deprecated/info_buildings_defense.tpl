<table width="519">
<tbody>
<tr>
	<td class="c" colspan="2"><?php echo $this->getData('nfo_title_head')?> <?php echo $this->getData('element_typ')?></td>
</tr><tr>
	<th><?php echo $this->getData('nfo_name')?></th>
	<th><?php echo $this->getData('name')?></th>
</tr><tr>
	<th colspan="2">
		<table>
		<tbody>
		<tr>
			<td><img src="<?php echo $this->getData('dpath')?>gebaeude/<?php echo $this->getData('image')?>.gif" align="top" border="0" height="120" width="120"></td>
			<td><?php echo $this->getData('description')?><br><br><?php echo $this->getData('rf_info_to')?><?php echo $this->getData('rf_info_from')?></td>
		</tr>
		</tbody>
		</table>
	</th>
</tr><tr>
	<th><?php echo $this->getData('nfo_struct_pt')?></th>
	<th><?php echo $this->getData('hull_pt')?></th>
</tr><tr>
	<th><?php echo $this->getData('nfo_shielf_pt')?></th>
	<th><?php echo $this->getData('shield_pt')?></th>
</tr><tr>
	<th><?php echo $this->getData('nfo_attack_pt')?></th>
	<th><?php echo $this->getData('attack_pt')?></th>
</tr>
</tbody>
</table>