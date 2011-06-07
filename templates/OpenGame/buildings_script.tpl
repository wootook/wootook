<div>Production Actuelle:
  <select id="buildList" size="10"></select>
</div>
<script type="text/javascript">
//<![CDATA[
var date = new Date();
var data = {data};
var select = document.getElementById('buildList');

setInterval(function(){
  var now = new Date();
  var datediff = (now.getTime() - date.getTime()) / 1000;

  var option = null;
  var index = 0;
  if (data.length == 0) {
    select.parentNode.style.display = 'none';
    return;
  }
  select.length = 0;
  for (i in data) {
    if (datediff > 0) {
      data[i]['actual_qty'] = data[i]['qty'] - Math.floor(datediff / data[i]['speed']);
    } else {
      data[i]['actual_qty'] = data[i]['qty'];
    }
    if (data[i]['actual_qty'] <= 0) {
      datediff = datediff - (data[i]['speed'] * data[i]['qty']);
      delete data[i];
      continue;
    } else {
      datediff = 0;
    }
    option = new Option(data[i]['label'] + ' (' + data[i]['actual_qty'] + ')', data[i]['speed'], false, true);
    select.options[index++] = option;
  }
  }, 1000);
//]]>
</script>