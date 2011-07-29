
<br>
<form action="<?php echo $this->getData('PHP_SELF')?>" method=post>
  <table width=519>
	<tr>
	  <td class=c colspan=4><?php echo $this->getData('Notes')?></td>
	</tr>
	<tr>
	  <th colspan=4><a href="<?php echo $this->getData('PHP_SELF')?>?a=1"><?php echo $this->getData('MakeNewNote')?></a></th>
	</tr>
	<tr>
	  <td class=c></td>
	  <td class=c><?php echo $this->getData('Date')?></td>
	  <td class=c><?php echo $this->getData('Subject')?></td>
	  <td class=c><?php echo $this->getData('Size')?></td>
	</tr>

	<?php echo $this->getData('BODY_LIST')?>

<tr>
	  <td colspan=4><input value="<?php echo $this->getData('Delete')?>" type="submit"></td>
	</tr>
  </table>
</form>
</center>
</body>
</html>
