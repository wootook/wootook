<br>
<form action="" method=POST>
<table width=519>
	<tr>
	  <td class=c colspan=2><?php echo $this->getData('question')?></td>
	</tr>
	<tr>
	  <th><?php echo $this->getData('New_name')?></th>
	  <th><input type=text name=<?php echo $this->getData('name')?>> <input type=submit value="<?php echo $this->getData('Change')?>"></th>
	</tr>
	<tr>
	  <td class="c" colspan="9"><a href="alliance.php?mode=admin&edit=ally"><?php echo $this->getData('Return_to_overview')?></a></td>
	</tr>
</table>
</form>