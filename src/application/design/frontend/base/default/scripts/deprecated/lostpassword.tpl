<center>
<br><br>
<h2><font size="5"><?php echo $this->getData('ResetPass')?></font><br><?php echo $this->getData('servername')?></h2>
<form action="?action=1" method="post">
<table width="400">
<tbody><tr>
	 <td colspan="2" class="c"><b><?php echo $this->getData('PassForm')?></b></td>
</tr><tr>
	<th colspan="2"><?php echo $this->getData('TextPass1')?> <?php echo $this->getData('servername')?> <?php echo $this->getData('TextPass2')?></th>
    </tr>
      <tr>
       <th><?php echo $this->getData('pseudo')?>:</th>
       <th><input name="pseudo" maxlength="30" size="20" value="" type="text">   </tr>
       <tr>
       <th><?php echo $this->getData('email')?>:</th>
       <th><input name="email" maxlength="50" size="20" value="" type="text">   </th>
      </tr>
           <tr>
         <th colspan="2"><input value="<?php echo $this->getData('ButtonSendPass')?>" type="submit"></th>
      </tr>
           <tr>
             <th colspan="2"><a href="login.php">Retour a l'accueil</a></th>
           </tr>
    </tbody></table>
       

    </form>

    </center>
