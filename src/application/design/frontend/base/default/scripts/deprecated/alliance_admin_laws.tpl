<br>
<table width=519><tr><td class=c colspan=11><?php echo $this->getData('Configure_laws')?></td></tr>

<?php echo $this->getData('list')?>


</table>

<br>

<form action="<?php echo $this->getStaticUrl('alliance.php', array('mode' => 'admin', 'edit' => 'rights', 'add' => 'name'))?>" method="POST">
<table width=519>
  <tr>
    <td class="c" colspan="2"><?php echo $this->getData('Range_make')?></td>
  </tr>
  <tr>
    <th><?php echo $this->getData('Range_name')?></th>
    <th><input type="text" name="newrangname" size="20" maxlength="30"></th>
  </tr>
  <tr>
    <th colspan="2"><input type="submit" value="<?php echo $this->getData('Make')?>"></th>
  </tr>
</table>
</form>

<form action="<?php echo $this->getStaticUrl('alliance.php', array('mode' => 'admin', 'edit' => 'rights'))?>" method="POST">
  <table width="519">
    <tr>
      <td class="c" colspan="2"><?php echo $this->getData('Law_leyends')?></td>
    </tr>
    <tr>
      <th><img src="<?php echo $this->getSkinUrl('graphics/images/r1.png')?>"></th>
      <th><?php echo $this->getData('Alliance_dissolve')?></th>
    </tr>
    <tr>
      <th><img src="<?php echo $this->getSkinUrl('graphics/images/r2.png')?>"></th>
      <th><?php echo $this->getData('Expel_users')?></th>
    </tr>
    <tr>
      <th><img src="<?php echo $this->getSkinUrl('graphics/images/r3.png')?>"></th>
      <th><?php echo $this->getData('See_the_requests')?></th>
    </tr>
    <tr>
      <th><img src="<?php echo $this->getSkinUrl('graphics/images/r4.png')?>"></th>
      <th><?php echo $this->getData('See_the_list_members')?></th>
    </tr>
    <tr>
      <th><img src="<?php echo $this->getSkinUrl('graphics/images/r5.png')?>"></th>
      <th><?php echo $this->getData('Check_the_requests')?></th>
    </tr>
    <tr>
      <th><img src="<?php echo $this->getSkinUrl('graphics/images/r6.png')?>"></th>
      <th><?php echo $this->getData('Alliance_admin')?></th>
    </tr>
    <tr>
      <th><img src="<?php echo $this->getSkinUrl('graphics/images/r7.png')?>"></th>
      <th><?php echo $this->getData('See_the_online_list_member')?></th>
    </tr>
    <tr>
      <th><img src="<?php echo $this->getSkinUrl('graphics/images/r8.png')?>"></th>
      <th><?php echo $this->getData('Make_a_circular_message')?></th>
    </tr>
    <tr>
      <th><img src="<?php echo $this->getSkinUrl('graphics/images/r9.png')?>"></th>
      <th><?php echo $this->getData('Left_hand_text')?></th>
    </tr>
    <tr>
      <td class="c" colspan="2"><a href="alliance.php?mode=admin&edit=ally"><?php echo $this->getData('Return_to_overview')?></a></td>
    </tr>
  </table>
</form>
