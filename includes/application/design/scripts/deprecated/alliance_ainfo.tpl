<br>
<table width="519">
	<tr>
	  <td class="c" colspan="2"><?php echo $this->getData('Alliance_information')?></td>
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
	  <th><?php echo $this->getData('ally_member_scount')?></th>
	</tr>
<?php echo $this->getData('ally_description')?>

<?php echo $this->getData('ally_web')?>

<?php echo $this->getData('bewerbung')?>
</table>
	