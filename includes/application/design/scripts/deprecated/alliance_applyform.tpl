<script src="scripts/cntchar.js" type="text/javascript"></script>
<br>
<h1><?php echo $this->getData('Send_Apply')?></h1>

<table width=519>
<form action="alliance.php?mode=apply&allyid=<?php echo $this->getData('allyid')?>" method=POST>

	<tr>
	  <td class=c colspan=2><?php echo $this->getData('Write_to_alliance')?></td>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Message')?> (<span id="cntChars"><?php echo $this->getData('chars_count')?></span> / 6000 <?php echo $this->getData('characters')?>)</th>
	  <th><textarea name="text" cols=40 rows=10 onkeyup="javascript:cntchar(6000)"><?php echo $this->getData('text_apply')?></textarea></th>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Help')?></th>
	  <th><input type=submit name="further" value="<?php echo $this->getData('Reload')?>"></th>
	</tr>
	<tr>
	  <th colspan=2><input type=submit name="further" value="<?php echo $this->getData('Send')?>"></th>
	</tr>
</table>

</form>

<script language="JavaScript" src="js/wz_tooltip.js"></script>