<br>
<table width=519>
	<tr>
	  <td class=c colspan=2><?php echo $this->getData('Apply_ally_overview')?> [<?php echo $this->getData('ally_tag')?>]</td>
	</tr>
	<?php echo $this->getData('request')?>
	<tr>
	  <th colspan=2><?php echo $this->getData('There_is_hanging_request')?></th>
	</tr>
	<tr>
	  <td class=c><center><a href="alliance.php?mode=admin&edit=requests&show=0&sort=1"><?php echo $this->getData('Candidate')?></a></center></td>
	  <td class=c><center><a href="alliance.php?mode=admin&edit=requests&show=0&sort=0"><?php echo $this->getData('Date_of_the_request')?></a></center></th>
	</tr>
	<?php echo $this->getData('list')?>
	<tr>
	  <td class=c colspan=2><a href="alliance.php"><?php echo $this->getData('Back')?></a></td>
	</tr>
</table>
