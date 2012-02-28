<br>
<table width=519>
	<tr>
	  <td class=c colspan=2><?php echo $this->getData('your_alliance')?></td>
	</tr>
	
	<?php echo $this->getData('ally_image')?>
	
	<tr>
	  <th><?php echo $this->getData('Tag')?></th>
	  <th><?php echo $this->getData('ally_tag')?></th>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Name')?></th>
	  <th><?php echo $this->getData('ally_name')?></th>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Members')?></th>
	  <th><?php echo $this->getData('ally_members')?><?php echo $this->getData('members_list')?></th>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Range')?></th>
	  <th><?php echo $this->getData('range')?><?php echo $this->getData('alliance_admin')?></th>
	</tr>
	
	<?php echo $this->getData('requests')?>
	
	<?php echo $this->getData('send_circular_mail')?>

	<tr>
	  <th colspan=2 height=100><?php echo $this->getData('ally_description')?></th>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Main_Page')?></th>
	  <th><a href="<?php echo $this->getData('ally_web')?>"><?php echo $this->getData('ally_web')?></a></th>
	</tr>
	<tr>
	  <td class=c colspan=2><?php echo $this->getData('Inner_section')?></th>
	</tr>
	<tr>
	  <th colspan=2 height=100><?php echo $this->getData('ally_text')?></th>
	</tr>
</table>
	
	<?php echo $this->getData('ally_owner')?>
