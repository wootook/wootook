<tr>
	<th><?php echo $this->getData('ally_rank')?></th>
	<th><?php echo $this->getData('ally_rankplus')?></th>
	<th><a href="alliance.php?mode=ainfo&tag=<?php echo $this->getData('ally_tag')?>" target='_ally'><?php echo $this->getData('ally_name')?></a></th>
	<th><?php echo $this->getData('ally_mes')?></th>
	<th><?php echo $this->getData('ally_members')?></th>
	<th><?php echo $this->getData('ally_points')?></th>
	<th><?php echo $this->getData('ally_members_points')?></th>
</tr>