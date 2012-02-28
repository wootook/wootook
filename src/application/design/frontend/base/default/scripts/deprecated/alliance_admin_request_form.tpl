<script src="scripts/cntchar.js" type="text/javascript"></script>
<br>
   <tr>
     <th colspan=2><?php echo $this->getData('Request_from')?></th>
   </tr>
   <tr>
     <th colspan=2><?php echo $this->getData('ally_request_text')?></th>
   </tr>
   <tr>
     <td class="c" colspan=2><?php echo $this->getData('Request_responde')?></td>
   </tr>
   <form action="alliance.php?mode=admin&edit=requests&show=<?php echo $this->getData('id')?>&sort=0" method="POST">
   <tr>
     <th><?php echo $this->getData('Motive_optional')?> (<span id="cntChars">0</span> / 500 <?php echo $this->getData('characters')?>)</th>
     <th><textarea name="text" cols=40 rows=10 onkeyup="javascript:cntchar(500)"><?php echo $this->getData('text_apply')?></textarea></th>
   </tr>
   <tr>
     <th>&#160;</th>
	 
     <th><input type="submit" name="action" value="Accepter">
	     <input type="submit" name="action" value="Refuser">
         </th>
   </tr>
   </form>
   <tr>
     <td colspan=2>&#160;</td>
   </tr>