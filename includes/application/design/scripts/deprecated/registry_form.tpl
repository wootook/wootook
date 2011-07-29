<center>
<br/><br/>
<h2><font size="+3"><?php echo $this->getData('registry')?></font><br><?php echo $this->getData('servername')?></h2>
<form action="" method="post">
<table width="438">
<tbody>
	  <tr>
	    <td colspan="2" class="c"><b><?php echo $this->getData('form')?></b></td>
</tr><tr>
	<th width="293"><?php echo $this->getData('GameName')?></th>
    <th width="293"><input name="character" size="20" maxlength="20" type="text" onKeypress="
     if (event.keyCode==60 || event.keyCode==62) event.returnValue = false;
     if (event.which==60 || event.which==62) return false;"></th>
</tr>
<tr>
  <th><?php echo $this->getData('neededpass')?></th>
  <th><input name="passwrd" size="20" maxlength="20" type="password" onKeypress="
     if (event.keyCode==60 || event.keyCode==62) event.returnValue = false;
     if (event.which==60 || event.which==62) return false;"></th>
</tr>
<tr>
  <th><?php echo $this->getData('E-Mail')?></th>
  <th><input name="email" size="20" maxlength="40" type="text" onKeypress="
     if (event.keyCode==60 || event.keyCode==62) event.returnValue = false;
     if (event.which==60 || event.which==62) return false;"></th>
</tr>
<tr>
  <th><?php echo $this->getData('MainPlanet')?></th>
  <th><input name="planet" size="20" maxlength="20" type="text" onKeypress="
     if (event.keyCode==60 || event.keyCode==62) event.returnValue = false;
     if (event.which==60 || event.which==62) return false;"></th>
</tr>
<tr>
  <th><?php echo $this->getData('Sex')?></th>
  <th><select name="sex">
		<option value=""><?php echo $this->getData('Undefined')?></option>
		<option value="M"><?php echo $this->getData('Male')?></option>
		<option value="F"><?php echo $this->getData('Female')?></option>
		</select></th>
</tr>
<tr>
<?php echo $this->getData('code_secu')?>
<th><?php echo $this->getData('affiche')?></th>
</tr>
<tr>
  <th><input name="rgt" type="checkbox">
    <?php echo $this->getData('accept')?></th>
  <th><input name="submit" type="submit" value="<?php echo $this->getData('signup')?>"></th>
</tr>
</table>
</form>
</center>