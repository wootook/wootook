<br>
<form action="?mode=make&yes=1" method="POST">

<table width=519>
	<tr>
	  <td class="c" colspan=2><?php echo $this->getData('make_alliance')?></td>
	</tr>
	<tr>
	  <th><?php echo $this->getData('alliance_tag')?> (3-8 <?php echo $this->getData('characters')?>)</th>
	  <th><input type="text" name="atag" size=8 maxlength=8 value=""></th>
	</tr>
	<tr>
	  <th><?php echo $this->getData('allyance_name')?> (max. 35 <?php echo $this->getData('characters')?>)</th>
	  <th><input type="text" name="aname" size=20 maxlength=30 value=""></th>
	</tr>
	<tr>
	  <th colspan=2><input type="submit" value="<?php echo $this->getData('Make')?>"></th>
	</tr>
</table>

</form>
