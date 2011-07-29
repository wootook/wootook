
	<tr>
	  <th><?php echo $this->getData('username')?></th>
	  <th><a href="messages.php?mode=write&id=<?php echo $this->getData('id')?>" alt="<?php echo $this->getData('write_a_messege')?>" title="<?php echo $this->getData('write_a_messege')?>"><img src="<?php echo $this->getData('dpath')?>img/m.gif" alt="<?php echo $this->getData('write_a_messege')?>" /></a>&nbsp;<a href="buddy.php?a=2&amp;u=<?php echo $this->getData('id')?>" alt="<?php echo $this->getData('buddy_request')?>"><img src="<?php echo $this->getData('dpath')?>img/b.gif" alt="<?php echo $this->getData('buddy_request')?>" title="<?php echo $this->getData('buddy_request')?>" border="0"></a></th>
	  <th><?php echo $this->getData('ally_name')?></th>
	  <th><?php echo $this->getData('planet_name')?></th>
	  <th><a href="galaxy.php?mode=3&galaxy=<?php echo $this->getData('galaxy')?>&system=<?php echo $this->getData('system')?>"><?php echo $this->getData('coordinated')?></a></th>
	  <th><?php echo $this->getData('position')?></th>
	</tr>

