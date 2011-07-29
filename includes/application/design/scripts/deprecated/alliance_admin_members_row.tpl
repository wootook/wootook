<br>
	<tr>
	  <th><?php echo $this->getData('i')?></th>
	  <th><?php echo $this->getData('username')?></th>
	  <th><a href="messages.php?mode=write&id=<?php echo $this->getData('id')?>"><img src="<?php echo $this->getData('dpath')?>img/m.gif" border=0 alt="<?php echo $this->getData('Write_a_message')?>"></a></th>
	  <th><?php echo $this->getData('ally_range')?></th>
	  <th><?php echo $this->getData('points')?></th>
	  <th><a href="galaxy.php?mode=0&galaxy=<?php echo $this->getData('galaxy')?>&system=<?php echo $this->getData('system')?>"><?php echo $this->getData('galaxy')?>:<?php echo $this->getData('system')?>:<?php echo $this->getData('planet')?></a></th>
	  <th><?php echo $this->getData('ally_register_time')?></th>
	  <th><?php echo $this->getData('onlinetime')?></th>
	  <th><?php echo $this->getData('functions')?>&nbsp;&nbsp;&nbsp;</th>
	</tr>
