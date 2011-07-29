
<a href="fleet.php?galaxy=<?php echo $this->getData('g')?>&amp;system=<?php echo $this->getData('s')?>&amp;planet=<?php echo $this->getData('p')?>&amp;planettype=2&amp;target_mission=8"
style="cursor: pointer;"
onmouseover="
this.T_WIDTH=250;
this.T_OFFSETX=-110;
this.T_OFFSETY=-110;
this.T_STICKY=true;
this.T_TEMP=<?php echo $this->getData('T_TEMP')?>;
return escape('<table width=\'240\'><tr><td class=\'c\' colspan=\'2\'>Escombros [<?php echo $this->getData('g')?>:<?php echo $this->getData('s')?>:<?php echo $this->getData('p')?>]</td></tr><tr><th width=\'80\'><img src=\'<?php echo $this->getData('dpath')?>planeten/debris.jpg\' height=\'75\' width=\'75\' alt=\'T\'/></th><th><table><tr><td class=\'c\' colspan=\'2\'>Recursos:</td></tr><tr><th>Metal:</th><th><?php echo $this->getData('debris_metal')?></th></tr><tr><th>Cristal:</th><th><?php echo $this->getData('debris_crystal')?></th></tr><tr><td class=\'c\' colspan=\'2\'>Acciones:</tr><tr><th colspan=\'2\' style=\'text-align: left\'><font color=\'#808080\'>Recolectar</font></tr></table></th></tr></table>');">

<img src="<?php echo $this->getData('dpath')?>planeten/debris.jpg" alt="Escombros" height="16" width="16"></a>
</th>