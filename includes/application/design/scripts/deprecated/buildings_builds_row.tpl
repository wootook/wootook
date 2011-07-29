<tr>
	<td class="l">
		<a href="infos.php?gid=<?php echo $this->getData('i')?>">
		<img border="0" src="<?php echo $this->getData('dpath')?>gebaeude/<?php echo $this->getData('i')?>.gif" align="top" width="120" height="120">
		</a>
	</td>
	<td class="l">
		<a href="infos.php?gid=<?php echo $this->getData('i')?>"><?php echo $this->getData('n')?></a><?php echo $this->getData('nivel')?><br>
		<?php echo $this->getData('descriptions')?><br>
		<?php echo $this->getData('price')?>
		<?php echo $this->getData('time')?>
		<?php echo $this->getData('rest_price')?>
	</td>
	<td class="k"><?php echo $this->getData('click')?></td>
</tr>