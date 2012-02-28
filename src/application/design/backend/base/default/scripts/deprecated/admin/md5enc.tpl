<br><br>
<h2><?php echo $this->getData('md5_title')?></h2>
<form method="post" action="md5enc.php">
<table width="305" border="0" cellspacing="2" cellpadding="0" style="color:#FFFFFF">
<tr>
	<td class="c" colspan="6"><?php echo $this->getData('md5_pswcyp')?></td>
</tr>
<tr>
	<th width="130"><?php echo $this->getData('md5_psw')?></th>
	<th width="171"><input type="text" name="md5q" value="<?php echo $this->getData('md5_md5')?>"></th>
</tr><tr>
	<th width="130"><?php echo $this->getData('md5_pswenc')?></th>
	<th width="171"><input type="text" name="md5w" value="<?php echo $this->getData('md5_enc')?>"></th>
</tr><tr>
	<th width="130">&nbsp;</th>
	<th width="171"><input type="submit" name="ok" value="<?php echo $this->getData('md5_doit')?>"></th>
</tr>
</table>
</form>