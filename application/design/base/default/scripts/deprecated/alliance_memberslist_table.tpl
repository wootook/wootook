<br>
<table width=519>
	<tr>
	  <td class=c colspan=8><?php echo $this->getData('Members_list')?> (<?php echo $this->getData('Ammount')?>: <?php echo $this->getData('i')?>)</td>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Number')?></th>
	  <th><a href="?mode=memberslist&sort1=1&sort2=<?php echo $this->getData('s')?>"><?php echo $this->getData('Name')?></a></th>
	  <th></th>
	  <th><a href="?mode=memberslist&sort1=2&sort2=<?php echo $this->getData('s')?>"><?php echo $this->getData('Position')?></a></th>
	  <th><a href="?mode=memberslist&sort1=3&sort2=<?php echo $this->getData('s')?>"><?php echo $this->getData('Points')?></a></th>
	  <th><a href="?mode=memberslist&sort1=0&sort2=<?php echo $this->getData('s')?>"><?php echo $this->getData('Coordinated')?></a></th>
	  <th><a href="?mode=memberslist&sort1=4&sort2=<?php echo $this->getData('s')?>"><?php echo $this->getData('Member_from')?></a></th>
	  <th><a href="?mode=memberslist&sort1=5&sort2=<?php echo $this->getData('s')?>"><?php echo $this->getData('Online')?></a></th>
	</tr>
	<?php echo $this->getData('list')?>
	<tr>
	  <td class="c" colspan="9"><a href="alliance.php"><?php echo $this->getData('Return_to_overview')?></a></td>
	</tr>
</table>
