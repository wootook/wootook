<tr>
  <th colspan="2">
<br><?php echo $this->getData('ins_tx_acc1')?><br>
<?php echo $this->getData('ins_tx_acc2')?><br><br>
<table width="270" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><?php echo $this->getData('ins_acc_user')?>:</td>
    <td><input name="adm_user" size="20" maxlength="20" type="text" onKeypress="
     if (event.keyCode==60 || event.keyCode==62) event.returnValue = false;
     if (event.which==60 || event.which==62) return false;"></td>
  </tr>
  <tr>
    <td><?php echo $this->getData('ins_acc_pass')?>:</td>
    <td><input name="adm_pass" size="20" maxlength="20" type="password" onKeypress="
     if (event.keyCode==60 || event.keyCode==62) event.returnValue = false;
     if (event.which==60 || event.which==62) return false;"></td>
  </tr>
  <tr>
    <td><?php echo $this->getData('ins_acc_email')?>:</td>
    <td><input name="adm_email" size="20" maxlength="40" type="text" onKeypress="
     if (event.keyCode==60 || event.keyCode==62) event.returnValue = false;
     if (event.which==60 || event.which==62) return false;"></td>
  </tr>
  <tr>
    <td><?php echo $this->getData('ins_acc_planet')?>:</td>
    <td><input name="adm_planet" size="20" maxlength="20" type="text" onKeypress="
     if (event.keyCode==60 || event.keyCode==62) event.returnValue = false;
     if (event.which==60 || event.which==62) return false;"></td>
  </tr>
  <tr>
    <td><?php echo $this->getData('ins_acc_sex')?>:</td>
    <td><select name="adm_sex">
	<option value=""><?php echo $this->getData('ins_acc_sex0')?></option>
	<option value="M"><?php echo $this->getData('ins_acc_sex1')?></option>
	<option value="F"><?php echo $this->getData('ins_acc_sex2')?></option>
	</select></td>
  </tr>
</table>
<br>
</th>
</tr>
<tr>
  <th colspan="2"><input type="button" name="next" onclick="submit();" value="<?php echo $this->getData('ins_btn_creat')?>" ></th>
</tr>