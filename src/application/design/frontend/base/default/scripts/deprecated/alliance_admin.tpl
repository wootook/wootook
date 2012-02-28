<script src="scripts/cntchar.js" type="text/javascript"></script>
<br>
<table width=519>
	<tr>
	  <td class=c colspan=2><?php echo $this->getData('Alliance_admin')?></td>
	</tr>
	<tr>
	  <th colspan=2><a href="?mode=admin&edit=rights"><?php echo $this->getData('Law_settings')?></a></th>
	</tr>
	<tr>
	  <th colspan=2><a href="?mode=admin&edit=members"><?php echo $this->getData('Members_administrate')?></a></th>
	</tr>
	<tr>
	  <th colspan=2><a href="?mode=admin&edit=tag"><?php echo $this->getData('Change_the_ally_tag')?></a></th>
	</tr>
	<!--<img src="<?php echo $this->getData('dpath')?>pic/appwiz.gif" border=0 alt="">-->
	<tr>
	  <th colspan=2><a href="?mode=admin&edit=name"><?php echo $this->getData('Change_the_ally_name')?></a></th>
	</tr>
	<!--<img src="<?php echo $this->getData('dpath')?>pic/appwiz.gif" border=0 alt="">-->
</table>
<br>
<form action="" method="POST">
<input type="hidden" name="t" value="<?php echo $this->getData('t')?>">
<table width=519>
	<tr>
	  <td class="c" colspan=3><?php echo $this->getData('Texts')?></td>
	</tr>
	<tr>
	  <th><a href="?mode=admin&edit=ally&t=1"><?php echo $this->getData('External_text')?></a></th>
	  <th><a href="?mode=admin&edit=ally&t=2"><?php echo $this->getData('Internal_text')?></a></th>
	  <th><a href="?mode=admin&edit=ally&t=3"><?php echo $this->getData('Request_text')?></a></th>
	</tr>
	<tr>
	  <td class=c colspan=3><?php echo $this->getData('Show_of_request_text')?> (<span id="cntChars">0</span> / 5000 <?php echo $this->getData('characters')?>)</td>
	</tr>
	<tr>
	  <th colspan=3><textarea name="text" cols=70 rows=15 onkeyup="javascript:cntchar(5000)"><?php echo $this->getData('text')?></textarea>
<?php echo $this->getData('request_type')?>
	</th>
	</tr>
	<tr>
	  <th colspan=3>
	  <input type="hidden" name=t value=<?php echo $this->getData('t')?>><input type="reset" value="<?php echo $this->getData('Reset')?>"> 
	  <input type="submit" value="<?php echo $this->getData('Save')?>">
	  </th>
	</tr>
</table>
</form>

<br>

<form action="" method="POST">
<table width=519>
	<tr>
	  <td class=c colspan=2><?php echo $this->getData('Options')?></td>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Main_Page')?></th>
	  <th><input type=text name="web" value="<?php echo $this->getData('ally_web')?>" size="70"></th>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Alliance_logo')?></th>
	  <th><input type=text name="image" value="<?php echo $this->getData('ally_image')?>" size="70"></th>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Requests')?></th>
	  <th>
	  <select name="request_notallow"><option value=1<?php echo $this->getData('ally_request_notallow_0')?>><?php echo $this->getData('No_allow_request')?></option>
	  <option value=0<?php echo $this->getData('ally_request_notallow_1')?>><?php echo $this->getData('Allow_request')?></option></select>
	  </th>
	</tr>
	<tr>
	  <th><?php echo $this->getData('Founder_name')?></th>
	  <th><input type="text" name="owner_range" value="<?php echo $this->getData('ally_owner_range')?>" size=30></th>
	</tr>
	<tr>
	  <th colspan=2><input type="submit" name="options" value="<?php echo $this->getData('Save')?>"></th>
	</tr>
</table>
</form>

<?php echo $this->getData('Disolve_alliance')?>
<br>
<?php echo $this->getData('Transfer_alliance')?>

