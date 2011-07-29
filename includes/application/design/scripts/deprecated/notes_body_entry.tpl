

	<tr>
	  <th width=20><input name="delmes<?php echo $this->getData('NOTE_ID')?>" value="y" type="checkbox"></th>
	  <th width=150><?php echo $this->getData('NOTE_TIME')?></th>
	  <th>
		<a href="<?php echo $this->getData('PHP_SELF')?>?a=2&amp;n=<?php echo $this->getData('NOTE_ID')?>">
			<font color="<?php echo $this->getData('NOTE_COLOR')?>"><?php echo $this->getData('NOTE_TITLE')?></font>
		</a>
	  </th>
	  <th align="right" width="40"><?php echo $this->getData('NOTE_TEXT')?></th>
	</tr>
