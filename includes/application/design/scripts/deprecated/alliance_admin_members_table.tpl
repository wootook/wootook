<br>
<table width="519">
	<tr>
	  <td class="c" colspan="9"><?php echo $this->getData('Members_list')?> (<?php echo $this->getData('Ammount')?>: <?php echo $this->getData('memberzahl')?>)</td>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Number')?></th>
	  <th><a href="alliance.php?mode=admin&edit=members&sort1=1&sort2=<?php echo $this->getData('s')?>"><?php echo $this->getData('Name')?></a></th>
	  <th> </th>
	  <th><a href="alliance.php?mode=admin&edit=members&sort1=2&sort2=<?php echo $this->getData('s')?>"><?php echo $this->getData('Position')?></a></th>
	  <th><a href="alliance.php?mode=admin&edit=members&sort1=3&sort2=<?php echo $this->getData('s')?>"><?php echo $this->getData('Points')?></a></th>
	  <th><a href="alliance.php?mode=admin&edit=members&sort1=0&sort2=<?php echo $this->getData('s')?>"><?php echo $this->getData('Coordinated')?></a></th>
	  <th><a href="alliance.php?mode=admin&edit=members&sort1=4&sort2=<?php echo $this->getData('s')?>"><?php echo $this->getData('Member_from')?></a></th>
	  <th><a href="alliance.php?mode=admin&edit=members&sort1=5&sort2=<?php echo $this->getData('s')?>">Duree d inactivite</a></th>
	  <th>Fonction</th>
	</tr>
	<?php echo $this->getData('memberslist')?>
	<tr>
	  <td class="c" colspan="9"><a href="alliance.php?mode=admin&edit=ally"><?php echo $this->getData('Return_to_overview')?></a></td>
	</tr>
</table>
<script src="scripts/wz_tooltip.js" type="text/javascript"></script>
