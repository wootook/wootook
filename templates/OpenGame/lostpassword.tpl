<center>
<br><br>
<h2><font size="5">{ResetPass}</font><br>{servername}</h2>
<form action="?action=1" method="post">
<table width="400">
<tbody><tr>
	 <td colspan="2" class="c"><b>{PassForm}</b></td>
</tr><tr>
	<th colspan="2">{TextPass1} {servername} {TextPass2}</th>
    </tr>
      <tr>
       <th>{pseudo}:</th>
       <th><input name="pseudo" maxlength="30" size="20" value="" type="text">   </tr>
       <tr>
       <th>{email}:</th>
       <th><input name="email" maxlength="50" size="20" value="" type="text">   </th>
      </tr>
           <tr>
         <th colspan="2"><input value="{ButtonSendPass}" type="submit"></th>
      </tr>
           <tr>
             <th colspan="2"><a href="login.php">Retour a l'accueil</a></th>
           </tr>
    </tbody></table>
       

    </form>

    </center>
