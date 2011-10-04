<tr>
	<th class="l">
		<a href="infos.php?gid=<?php echo $this->getData('tech_id')?>">
		<img border=0 src="<?php echo $this->getData('dpath')?>gebaeude/<?php echo $this->getData('tech_id')?>.gif" align="top" width=120 height=120></a>
	</th>
	<td class="l">
		<a href="infos.php?gid=<?php echo $this->getData('tech_id')?>"><?php echo $this->getData('tech_name')?></a> <?php echo $this->getData('tech_level')?><br><?php echo $this->getData('tech_descr')?><br>
		<?php echo $this->getData('tech_price')?>
		<?php echo $this->getData('search_time')?>
		<?php echo $this->getData('tech_restp')?>
	</td>
	<th class="l">
		<?php echo $this->getData('tech_link')?>
	</th>
</tr>