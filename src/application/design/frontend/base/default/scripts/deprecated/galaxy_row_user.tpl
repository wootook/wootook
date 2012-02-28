
    <a style="cursor: pointer;" onmouseover="
this.T_WIDTH=200;
this.T_OFFSETX=-20;
this.T_OFFSETY=-30;
this.T_STICKY=true;
this.T_TEMP=<?php echo $this->getData('T_TEMP')?>;
return escape('<table width=\'190\'><tr><td class=\'c\' colspan=\'2\'>Jugador <?php echo $this->getData('username')?></td></tr><tr><td><a href=\'messages.php?mode=write&id=<?php echo $this->getData('user_id')?>\'>Escribir mensaje</a></td></tr><tr><td><a href=\'buddy.php?a=2&u=<?php echo $this->getData('user_id')?>\'>Solicitud de compañeros</a></td></tr></table>');">
      <span class="noob"><?php echo $this->getData('username')?></span>
    </a>