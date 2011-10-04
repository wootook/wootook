<br>
<center>
<form method="post">
<table width="519">
<tr>
	<td class="c"><?php echo $this->getData('stat_title')?>: <?php echo $this->getData('stat_date')?></td>
</tr><tr>
	<th align="center">
	<table>
	<tr>
		<th width="8%" style="background-color: transparent;">&nbsp;</th>
		<th style="background-color: transparent;"><?php echo $this->getData('stat_show')?>&nbsp;</th>
		<th style="background-color: transparent;"><select name="who" onChange="javascript:document.forms[0].submit()"><?php echo $this->getData('who')?></select></th>
		<th style="background-color: transparent;">&nbsp;<?php echo $this->getData('stat_by')?>&nbsp;</th>
		<th style="background-color: transparent;"><select name="type" onChange="javascript:document.forms[0].submit()"><?php echo $this->getData('type')?></select></th>
		<th style="background-color: transparent;">&nbsp;<?php echo $this->getData('stat_range')?>&nbsp;</th>
		<th style="background-color: transparent;"><select name="range" onChange="javascript:document.forms[0].submit()"><?php echo $this->getData('range')?></select></th>
		<th width="8%" style="background-color: transparent;">&nbsp;</th>
	<tr>
	</table>
	</th>
</tr>
</table>
</form>
<table width="519">
<?php echo $this->getData('stat_header')?>
<?php echo $this->getData('stat_values')?>
</table>
</center>
</body>
</html>