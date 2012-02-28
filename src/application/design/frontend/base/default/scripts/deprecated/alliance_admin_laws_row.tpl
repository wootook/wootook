<tr>
  <th>
    <?php if ($this->getData('a') > 0):?>
    <a href="<?php echo $this->getStaticUrl('alliance.php', array('mode' => 'admin', 'edit' => 'rights', 'd' => $this->getData('a')))?>">
      <img src="<?php echo $this->getSkinUrl('graphics/pic/abort.gif')?>" alt="<?php echo $this->__('Remove rank')?>" border="0" />
    </a>
    <?php endif?>
    <input type="hidden" name="id[]" value="<?php echo $this->getData('a')?>">
  </th>
  <th><?php echo $this->getData('r0')?></th>
  <th><?php echo $this->getData('r1')?></th>
  <th><?php echo $this->getData('r2')?></th>
  <th><?php echo $this->getData('r3')?></th>
  <th><?php echo $this->getData('r4')?></th>
  <th><?php echo $this->getData('r5')?></th>
  <th><?php echo $this->getData('r6')?></th>
  <th><?php echo $this->getData('r7')?></th>
  <th><?php echo $this->getData('r8')?></th>
  <th><?php echo $this->getData('r9')?></th>
  </tr>
<tr>