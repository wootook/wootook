<center>
<br />
<table align="top">
<tr>
	<td>
		<form action="buildings.php?mode=fleet" method="post">
		<table width=530>
		<?php echo $this->getData('buildlist')?>
		<tr>
			<td class="c" colspan=2 align="center"><input type="submit" value="<?php echo $this->getData('Construire')?>"></td>
		</tr>
		</table>
		</form>
	</td>
	  <td valign="top"></td>
	</tr>
</table>
<?php echo $this->getData('buildinglist')?>
</center>