<script src="scripts/cnt.js" type="text/javascript"></script>
<div id="btc" class="z"></div><script language="JavaScript">
	pp = '<?php echo $this->getData('time')?>';
	pk = '<?php echo $this->getData('building_id')?>';
	pl = '<?php echo $this->getData('id')?>';
	ps = 'buildings.php';
	t();
</script>
<script type="text/javascript">
v  = new Date();
p  = 0;
g  = <?php echo $this->getData('b_building_queue')?>;
s  = 0;
hs = 0;
of = 1;
c  = new Array(<?php echo $this->getData('c')?>'');
b  = new Array(<?php echo $this->getData('b')?>'');
a  = new Array(<?php echo $this->getData('a')?>'');
aa = '<?php echo $this->getData('completed')?>';

function t() {
	if ( hs == 0 ) {
		xd();
		hs = 1;
	}
	n = new Date();
	s = c[p]-g-Math.round((n.getTime()-v.getTime())/1000.);
	s = Math.round(s);
	m = 0;
	h = 0;
	if ( s < 0 ) {
		a[p]--;
		xd();
		if ( a[p] <= 0 ) {
			p++;
			xd();
		}
		g = 0;
		v = new Date();
		s = 0;
	}
	if ( s > 59 ) {
		m = Math.floor( s / 60 );
		s = s - m * 60;
	}
	if ( m > 59 ) {
		h = Math.floor( m / 60 );
		m = m - h * 60;
	}
	if ( s < 10 ) {
		s = "0" + s;
	}
	if ( m < 10 ) {
		m = "0" + m;
	}
	if (p > b.length - 2) {
		document.getElementById("bx").innerHTML=aa ;
	} else {
		document.getElementById("bx").innerHTML= b[p] + " " + h + ":" + m + ":" + s;
	}
	window.setTimeout("t();", 200);
}

function xd() {
	while (document.Atr.auftr.length > 0) {
		document.Atr.auftr.options[document.Atr.auftr.length-1] = null;
	}
	if ( p > b.length - 2 ) {
		document.Atr.auftr.options[document.Atr.auftr.length] = new Option(aa);
	}
	for (iv = p; iv <= b.length - 2; iv++) {
		if ( a[iv] < 2 ) {
			ae = " ";
		} else {
			ae = " ";
		}
		if ( iv == p ) {
			act = " (<?php echo $this->getData('in_working')?>)";
		} else {
			act = "";
		}
		document.Atr.auftr.options[document.Atr.auftr.length] = new Option( a[iv] + ae + " \"" + b[iv] + "\"" + act, iv + of );
	}
}

window.onload = t;
</script>

<center>
<br>
<form name="Atr" method="get" action="buildings.php">
	<input type="hidden" name="mode" value="fleet">
	<table width="530">
	<tr>
		<td class="c" ><?php echo $this->getData('work_todo')?></td>
	</tr><tr>
		<th ><select name="auftr" size="10"></select></th>
	</tr><tr>
		<td class="c" ></td>
	</tr>
	</table>
</form>
<?php echo $this->getData('total_left_time')?>

<?php echo $this->getData('pretty_time_b_hangar')?>
<br>
</center>