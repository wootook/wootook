
<a style="cursor: pointer;" onmouseover="
this.T_WIDTH=250;
this.T_OFFSETX=-110;
this.T_OFFSETY=-30;
this.T_STICKY=true;
this.T_TEMP=<?php echo $this->getData('T_TEMP')?>;
this.T_STATIC=true;
return escape('<table width=\'240\'><tr><td class=\'c\' colspan=\'2\'>Planeta <?php echo $this->getData('planet_name')?> [<?php echo $this->getData('g')?>:<?php echo $this->getData('s')?>:<?php echo $this->getData('i')?>]</td></tr><tr><th width=\'80\'><img src=\'<?php echo $this->getData('dpath')?>planeten/small/s_<?php echo $this->getData('image')?>.jpg\' height=\'75\' width=\'75\'/></th><th style=\'text-align: left\'><a href=\'#\' onclick=\'doit(6, <?php echo $this->getData('g')?>, <?php echo $this->getData('s')?>, <?php echo $this->getData('p')?>, 1, 1)\'>Espiar</a><br /><br /><a href=\'fleet.php?g=<?php echo $this->getData('g')?>&s=<?php echo $this->getData('s')?>&p=<?php echo $this->getData('p')?>&t=1&m=1\'>Atakuj</a><br /><a href=\'fleet.php?g=<?php echo $this->getData('g')?>&s=<?php echo $this->getData('s')?>&p=<?php echo $this->getData('p')?>&t=1&m=3\'>Transportuj</a></th></tr></table>');">
<img src="<?php echo $this->getData('dpath')?>planeten/small/s_<?php echo $this->getData('image')?>.jpg" height="30" width="30"></a>
