<br>
<form action="" method=POST>

<table width=519>
	<tr>
	  <td class=c colspan=2><?php echo $this->getData('your_apply')?></td>
	</tr>
	<tr>
	  <th colspan=2><?php echo $this->getData('request_text')?></th>
	</tr>
	<tr>
	  <th colspan=2><input type=submit name="bcancel" value="<?php echo $this->getData('button_text')?>"></th>
	</tr>
</table>
</form>