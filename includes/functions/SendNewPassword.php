<?php
/**
 * This file is part of XNova:Legacies
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.xnova-ng.org/
 *
 * Copyright (c) 2009-Present, XNova Support Team <http://www.xnova-ng.org>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing XNova.
 *
 */

/**
 *
 * @deprecated
 * @param unknown_type $mail
 */
  function sendnewpassword($mail){
    trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

  	$ExistMail = doquery("SELECT `email` FROM {{table}} WHERE `email` = '". $mail ."' LIMIT 1;", 'users', true);

    if (empty($ExistMail['email']))	{
	   message('L\'adresse n\'existe pas !','Erreur');
	}

	else{
	//Caractere qui seront contenus dans le nouveau mot de passe
    $Caracters="aazertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890";

    $Count=strlen($Caracters);

    $NewPass="";
    $Taille=6;


    srand((double)microtime()*1000000);

     for($i=0;$i<$Taille;$i++){

      $CaracterBoucle=rand(0,$Count-1);

      $NewPass=$NewPass.substr($Caracters,$CaracterBoucle,1);
      }

    //Et un nouveau mot de passe tout chaud ^^

    //On va maintenant l'envoyer au destinataire
    $Title = "XNova : Nouveau mot de passe";
    $Body = "Voici votre nouveau mot de passe : ";
    $Body .= $NewPass;

    mail($mail,$Title,$Body);

    //Email envoy�, maintenant place au changement dans la BDD

    $NewPassSql = md5($NewPass);

    $QryPassChange = "UPDATE {{table}} SET ";
    $QryPassChange .= "`password` ='". $NewPassSql ."' ";
    $QryPassChange .= "WHERE `email`='". $mail ."' LIMIT 1;";

    doquery( $QryPassChange, 'users');


    }



	}



?>