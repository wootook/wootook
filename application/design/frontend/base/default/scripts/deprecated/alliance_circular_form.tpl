<br>
<form action="?a=17&sendmail=1" method=post>
  <table width=519>
	<tr>
	  <td class=c colspan=2><?php echo $this->getData('Send_circular_mail')?></td>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Destiny')?></th>
	  <th>
		<select name=r>
		  <?php echo $this->getData('r_list')?>
		</select>
	  </th>
	</tr>

	<tr>
	  <th><?php echo $this->getData('Text_mail')?> (<span id="cntChars">0</span> / 5000 <?php echo $this->getData('characters')?>)</th>
	  <th>
	    <textarea name="text" cols="60" rows="10" onkeyup="javascript:cntchar(5000)"></textarea>
	  </th>
	</tr>
	<tr>
	  <td class="c"><a href="alliance.php"><?php echo $this->getData('Back')?></a></td>
	  <td class="c">
		<input type="reset" value="<?php echo $this->getData('Clear')?>">
		<input type="submit" value="<?php echo $this->getData('Send')?>">
	  </td>
	</tr>
  </table>
</form>
