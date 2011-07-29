<table width="519">
<tbody>
<tr>
	<td class="c"><?php echo $this->getData('name')?></td>
</tr><tr>
	<th>
	<table>
	<tbody>
	<tr>
		<td><img src="<?php echo $this->getData('dpath')?>gebaeude/<?php echo $this->getData('image')?>.gif" align="top" border="0" height="120" width="120"></td>
		<td><?php echo $this->getData('description')?></td>
	</tr>
	</tbody>
	</table>
	</th>
</tr><tr>
	<th>
		<center>
		<table border="1">
		<tbody>
		<?php echo $this->getData('table_head')?>
		<?php echo $this->getData('table_data')?>
		</tbody>
		</table>
		</center>
	</th>
</tr>
</tbody>
</table>