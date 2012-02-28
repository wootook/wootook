<script src="scripts/cntchar.js" type="text/javascript"></script>
<br />
<center>
<form action="messages.php?mode=write&id=<?php echo $this->getData('id')?>" method="post">
<table width="519">
<tr>
	<td class="c" colspan="2"><?php echo $this->getData('Send_message')?></td>
</tr><tr>
	<th><?php echo $this->getData('Recipient')?></th>
	<th><input type="text" name="to" size="40" value="<?php echo $this->getData('to')?>" /></th>
</tr><tr>
	<th><?php echo $this->getData('Subject')?></th>
	<th><input type="text" name="subject" size="40" maxlength="40" value="<?php echo $this->getData('subject')?>" /></th>
</tr><tr>
	<th><?php echo $this->getData('Message')?>(<span id="cntChars">0</span> / 5000 <?php echo $this->getData('characters')?>)</th>
	<th><textarea name="text" cols="40" rows="10" size="100" onkeyup="javascript:cntchar(5000)"><?php echo $this->getData('text')?></textarea></th>
</tr><tr>
	<th colspan="2"><input type="submit" value="<?php echo $this->getData('Envoyer')?>" /></th>
</tr>
</table>
</form>
</center>