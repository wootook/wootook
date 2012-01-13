<br><br>
<h2><?php echo $this->getData('cred_ext')?></h2>
<form action="" method="post">
<input type="hidden" name="opt_save" value="1">
<table width="475">
<tbody>
<tr>
	<td colspan="2" class="c"><b><?php echo $this->getData('cred_info')?></b></td>
</tr><tr>
	<th colspan="2"><?php echo $this->getData('cred_infotxt')?></th></tr>
<tr>
	<td colspan="2" class="c"><b><?php echo $this->getData('cred_credit')?></b></td>
</tr><tr>
	<th width="278">Raito<br>Chlorel<br>e-Zobar<br>Flousedid<br>
	<th width="279"><?php echo $this->getData('cred_creat')?> / <?php echo $this->getData('cred_prog')?><br><?php echo $this->getData('cred_master')?> <?php echo $this->getData('cred_prog')?><br><?php echo $this->getData('cred_design')?> / <?php echo $this->getData('cred_prog')?><br><?php echo $this->getData('cred_web')?>
</tr><tr>
	<td colspan="2" class="c"><b><?php echo $this->getData('cred_ext')?></b></td>
</tr><tr>
	<th colspan="2"><?php echo $this->getData('cred_added')?> <input name="ExtCopyFrame"<?php echo $this->getData('ExtCopyFrame')?> type="checkbox" /></th>
</tr><tr>
	<th><?php echo $this->getData('cred_name')?></th>
	<th><?php echo $this->getData('cred_funct')?></th>
</tr><tr>
	<th><textarea name="ExtCopyOwner" rows="5"><?php echo $this->getData('ExtCopyOwnerVal')?></textarea></th>
	<th><textarea name="ExtCopyFunct" rows="5"><?php echo $this->getData('ExtCopyFunctVal')?></textarea></th>
</tr><tr>
	<th colspan="2"><input value="<?php echo $this->getData('cred_save')?>" type="submit"></th>
</tr>
</table>