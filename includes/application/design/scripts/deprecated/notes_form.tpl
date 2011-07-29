
<form action="<?php echo $this->getData('PHP_SELF')?>" method=post>
  <?php echo $this->getData('inputs')?>
  <table width=519>
	<tr>
	  <td class=c colspan=2><?php echo $this->getData('TITLE')?></td>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Priority')?></th>
	  <th>
		<select name=u>
		  <?php echo $this->getData('c_Options')?>
		</select>
	  </th>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Subject')?></th>
	  <th>
		<input type="text" name="title" size="30" maxlength="30" value="<?php echo $this->getData('title')?>">
	  </th>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Note')?> (<span id="cntChars"><?php echo $this->getData('cntChars')?></span> / 5000 <?php echo $this->getData('characters')?>)</th>
	  <th>
	    <textarea name="text" cols="60" rows="10" onkeyup="javascript:cntchar(5000)"><?php echo $this->getData('text')?></textarea>
	  </th>
	</tr>
	<tr>
	  <td class="c"><a href="<?php echo $this->getData('PHP_SELF')?>"><?php echo $this->getData('Back')?></a></td>
	  <td class="c">
		<input type="reset" value="<?php echo $this->getData('Reset')?>">
		<input type="submit" value="<?php echo $this->getData('Save')?>">
	  </td>
	</tr>
  </table>
</form>
