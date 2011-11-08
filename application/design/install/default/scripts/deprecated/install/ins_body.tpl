<br><br>
  <table width="700">
<tbody><tr>
	  <td width="120px" class="c" align="left"><font size="2px"><?php echo $this->getData('ins_appname')?></font></td>
      <td width="580px" rowspan="2" class="c" align="right"><font size="2px"><?php echo $this->getData('ins_tx_sys')?></font><br /><?php echo $this->getData('ins_tx_state')?> <?php echo $this->getData('ins_state')?></td>
</tr><tr>
	<th rowspan="4"><table border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="124" align="center"><a href="index.php?mode=intro" accesskey="i"><?php echo $this->getData('ins_mnu_intro')?></a></td>
      </tr>
      <tr>
        <td align="center"><a href="index.php?mode=ins&page=1" accesskey="i"><?php echo $this->getData('ins_mnu_inst')?></a></td>
      </tr>
      <tr>
        <td align="center"><a href="index.php?mode=goto&page=1" accesskey="b"><?php echo $this->getData('ins_mnu_goto')?></a></td>
      </tr>
      <tr>
        <td align="center"><a href="index.php?mode=upg" accesskey="u"><?php echo $this->getData('ins_mnu_upgr')?></a></td>
      </tr>
      <tr>
        <td align="center"><a href="index.php?mode=bye" accesskey="b"><?php echo $this->getData('ins_mnu_quit')?></a></td>
      </tr>
    </table></th>
    </tr>
<form action="<?php echo $this->getData('dis_ins_btn')?>" method="post">
<?php echo $this->getData('ins_page')?>
</form>
</table>