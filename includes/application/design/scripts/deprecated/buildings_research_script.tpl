<div id="brp" class="z"></div>
<script   type="text/javascript">
v = new Date();
var brp = document.getElementById('brp');
function t(){
	n  = new Date();
	ss = <?php echo $this->getData('tech_time')?>;
	s  = ss - Math.round( (n.getTime() - v.getTime()) / 1000.);
	m  = 0;
	h  = 0;
	if ( s < 0 ) {
		brp.innerHTML = '<?php echo $this->getData('ready')?><br><a href=buildings.php?mode=research&cp=<?php echo $this->getData('tech_home')?>><?php echo $this->getData('continue')?></a>';
	} else {
		if ( s > 59 ) { m = Math.floor( s / 60 ); s = s - m * 60; }
		if ( m > 59 ) { h = Math.floor( m / 60 ); m = m - h * 60; }
		if ( s < 10 ) { s = "0" + s }
		if ( m < 10 ) { m = "0" + m }
		brp.innerHTML = h + ':' + m + ':' + s + '<br><a href=buildings.php?mode=research&cmd=cancel&tech=<?php echo $this->getData('tech_id')?>><?php echo $this->getData('cancel')?><br><?php echo $this->getData('tech_name')?></a>';
	}
	window.setTimeout("t();",999);
}
window.onload=t;
</script>