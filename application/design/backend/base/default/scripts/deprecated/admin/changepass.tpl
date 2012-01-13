<br><br>
<h2><?php echo $this->getData('md5_title')?></h2>
<form method="post" action="md5changepass.php">
<table width="305" border="0" cellspacing="2" cellpadding="0" style="color:#FFFFFF">
<tr>
	<td class="c" colspan="6"><?php echo $this->getData('md5_pswcyp')?></td>
</tr>
<tr>
	<th width="130"><?php echo $this->getData('user_to_change')?></th>
	<th width="171"><input type="text" name="user" value="Utilisateur?"></th>
</tr><tr>
	<th width="130"><?php echo $this->getData('md5_psw')?></th>
	<th width="171"><input type="text" name="md5q" value="<?php echo $this->getData('md5_md5')?>"></th>
</tr>
</table><input type="submit" name="ok" value="<?php echo $this->getData('md5_doit')?>">
</form>