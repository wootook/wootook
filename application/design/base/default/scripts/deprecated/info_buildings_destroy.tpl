<br>
<table width="519">
<tbody>
<tr>
	<td class="c" align="center">
    	<a href="<?php echo $this->getData('destroyurl')?>"><?php echo $this->getData('nfo_destroy')?>: <?php echo $this->getData('name')?> <?php echo $this->getData('nfo_level')?> <?php echo $this->getData('levelvalue')?> ?</a>
	</td>
</tr><tr>
	<th><?php echo $this->getData('nfo_needed')?> : <?php echo $this->getData('nfo_metal')?>:<b><?php echo $this->getData('metal')?></b> <?php echo $this->getData('nfo_crysta')?>:<b><?php echo $this->getData('crystal')?></b> <?php echo $this->getData('nfo_deuter')?>:<b><?php echo $this->getData('deuterium')?></b></th>
</tr><tr>
	<th><br><?php echo $this->getData('nfo_dest_durati')?>: <?php echo $this->getData('destroytime')?><br></th>
</tr>
</tbody>
</table>