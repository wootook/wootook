<h2><?php echo $this->__('Overview')?></h2>
<table width="600">
    <tr>
        <td class="c" colspan="2"><?php echo $this->__('General information')?></td>
    </tr>
    <tr>
        <td class="b"><?php echo $this->__('Your version:')?><span class="version"><?php echo $this->getData('version')?></span></td>
        <td class="b"><a class="downloads" href="http://wootook.org/downloads"><?php echo $this->__('Latest version avalaiable at Wootook.org')?></a></td>
    </tr>
</table>

<table width="600">
    <tr>
        <td class="c" colspan="13"><?php echo $this->__('Online players')?></td>
    </tr>
    <tr>
        <th><a href="?cmd=sort&type=id"><?php echo $this->getData('adm_ul_id')?></a></th>
        <th><a href="?cmd=sort&type=username"><?php echo $this->getData('adm_ul_name')?></a></th>
        <th><a href="?cmd=sort&type=user_lastip"><?php echo $this->getData('adm_ul_adip')?></a></th>
        <th><a href="?cmd=sort&type=ally_name"><?php echo $this->getData('adm_ov_ally')?></a></th>
        <th><?php echo $this->getData('adm_ov_point')?></th>
        <th><a href="?cmd=sort&type=onlinetime"><?php echo $this->getData('adm_ov_activ')?></a></th>
        <th><?php echo $this->getData('usr_email')?></th>
        <th><?php echo $this->getData('xp_raid')?></th>
        <th><?php echo $this->getData('xp_min')?></th>
        <th><?php echo $this->getData('lang_vacancy')?></th>
        <th><?php echo $this->getData('banned_lang')?></th>
        <th><?php echo $this->getData('usr_current_planet')?></th>
        <th><?php echo $this->getData('usr_current_page')?></th>
    </tr>
        <?php echo $this->getData('adm_ov_data_table')?>
    <tr>
        <th class="b" colspan="13"><?php echo $this->getData('adm_ov_count')?>: <?php echo $this->getData('adm_ov_data_count')?></th>
    </tr>
</table>
