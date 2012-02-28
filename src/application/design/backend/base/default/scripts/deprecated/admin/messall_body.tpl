<br><br>
<form action="?mode=change" method="post">
<table width="519">
<tbody>
<tr>
	<td class="c" colspan="2"><?php echo $this->__('General message')?></td>
</tr><tr>
	<th>Sujet</th>
	<th><input name="temat" maxlength="100" size="20" value="" type="text"></th>
</tr><tr>
	<th><?php echo $this->__('Content <span id="cntChars">0</span> / %1$d chars)', 5000)?></th>
	<th><textarea name="tresc" cols="40" rows="10" size="100"><?php echo $this->__('Admin message')?></textarea></th>
</tr><tr>
	<th colspan="2"><input value="Envoyer" type="submit"></th>
</tr>
</tbody>
</table>
</form>