<br>
 <form action="search.php" method="post">

 <table width="519">
  <tr>
   <td class="c"><?php echo $this->getData('Search_in_all_game')?></td>
  </tr>
  <tr>
   <th>
    <select name="type">
     <option value="playername"<?php echo $this->getData('type_playername')?>><?php echo $this->getData('Player_name')?></option>
     <option value="planetname"<?php echo $this->getData('type_planetname')?>><?php echo $this->getData('Planet_name')?></option>
     <option value="allytag"<?php echo $this->getData('type_allytag')?>><?php echo $this->getData('Alliance_tag')?></option>
     <option value="allyname"<?php echo $this->getData('type_allyname')?>><?php echo $this->getData('Alliance_name')?></option>
    </select>
    &nbsp;&nbsp;
    <input type="text" name="searchtext" value="<?php echo $this->getData('searchtext')?>"/>
    &nbsp;&nbsp;

    <input type="submit" value="<?php echo $this->getData('Search')?>" />
   </th>
  </tr>
</table>
</form>
<?php echo $this->getData('search_results')?>
