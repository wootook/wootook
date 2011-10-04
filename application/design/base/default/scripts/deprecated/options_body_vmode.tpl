    <center>
    <br><br>
    <table width="519">
    <tbody>
    <tr>
       <td class="c" colspan="2"><?php echo $this->getData('Vaccation_mode')?>  <?php echo $this->getData('vacation_until')?></td>
    </tr><tr>
       <th><a title="<?php echo $this->getData('vacations_tip')?>"><?php echo $this->getData('exit_vacations')?></a></th>
       <form action="options.php?mode=exit" method="post">
       <th><input type="checkbox" name="exit_modus"<?php echo $this->getData('opt_modev_exit')?>/></th>
    </tr><tr>
       <th colspan="2"><input type="submit" value="<?php echo $this->getData('save_settings')?>" ></th>
    </tr>
    </tbody>
    </table>
    </form>
    </center>
