<?php

// Si la constante n'est pas defini on bloque l'execution du fichier
if(!defined('PHPSIMUL_PAGES') || @PHPSIMUL_PAGES != 'PHPSIMULLL') 
{
die('Erreur 404 - Le fichier n\'a pas �t� trouv�');
}

/* PHPsimul : Cr�ez votre jeu de simulation en PHP
Copyright (�) - 2007 - CAPARROS S�bastien (Camaris)

Codeur officiel: Camaris & Max485
http://forum.epic-arena.fr

*/



class bb 
{
	##############################################################################################################
	// Fonction d�vlopp� par Max485 pour affichez le bbcode

	function bbcode($texte)
	{

		//on supprime les slashs qui se seraient ajout�s automatiquement
		$texte = stripslashes($texte) ;
		//on d�sactive le HTML
		$texte = htmlspecialchars($texte) ;
		//on g�n�re des retours chariots automatiques !!!
		$texte = nl2br($texte) ; 

		//on cr�e le bbcode qui sera appliqu� � notre texte !
		$bbcode=array(
			//mise en gras
			"!\[b\](.+)\[/b\]!isU",
			//soulignement
			"!\[u\](.+)\[/u\]!isU",
			//mise en italique
			"!\[i\](.+)\[/i\]!isU",
			//mise en couleur
			"!\[color=(.+)\](.+)\[/color\]!isU",
			//liste � puces 1
			"!\[ul\](.+)\[/ul\]!U",
			//liste � puces 2
			"!\[li\](.+)\[/li\]!U",
			// Lien nomm�
			"!\[url=(.+?)\](.+?)\[\/url\]!i",
			//afficher les images
			"!\[img\](.+)\[/img\]!i",
			// Lien
			"!\[url\](.+?)\[\/url\]!i",
			// Email nomm�
			"!\[email=(.+?)\](.+?)\[\/email\]!i",
			// Email
			"!\[email\](.+?)\[\/email\]!i",
			// Size H1
			"!\[size=1\](.+)\[/size\]!i",
			// Size H2
			"!\[size=2\](.+)\[/size\]!i",
			// Size H3
			"!\[size=3\](.+)\[/size\]!i",
			// Size H4
			"!\[size=4\](.+)\[/size\]!i",
			// Size H5
			"!\[size=5\](.+)\[/size\]!i",
			// Size Tres petit
			"!\[size=tres petit\](.+)\[/size\]!i",
			// Size Petit
			"!\[size=petit\](.+)\[/size\]!i",
			// Size Normal
			"!\[size=normal\](.+)\[/size\]!i",
			// Size Moyen
			"!\[size=moyen\](.+)\[/size\]!i",
			// Size Grand
			"!\[size=grand\](.+)\[/size\]!i",
			// Size 18
			"!\[size=18\](.+)\[/size\]!i",
			// Quote
			"!\[quote\](.+)\[/quote\]!i",
			//Quote nomm�
			"!\[quote=(.+)\](.+)\[/quote\]!i",
			// Code
			"!\[code\](.+)\[/code\]!i",



		);

		$html = array(
			//mise en gras
			"<b>$1</b>",
			//soulignement
			"<u>$1</u>",
			//mise en italique
			"<i>$1</i>",   
			//mise en couleur
			"<span style=\"color:$1\">$2</span>",
			//liste � puce 1
			"<ul>$1</ul>",
			//liste � puce 2
			"<li>$1</li>",
			// Lien nomm�
			"<a href=\"$1\">$2</a>",
			//afficher les images
			"<img src=\"$1\">",
			// Lien
			"<a href=\"$1\">$1</a>",
			// Email nomm�
			"<a href=\"mailto:$1\">$2</a>",
			// Email
			"<a href=\"mailto:$1\">$1</a>",
			//Size H1
			"<h1>$1</h1>",
			//Size H2
			"<h2>$1</h2>",
			//Size H3
			"<h3>$1</h3>",
			//Size H4
			"<h4>$1</h4>",
			//Size H5
			"<h5>$1</h5>",
			//Size Tres Petit
			"<h1>$1</h1>",
			//Size Petit
			"<h3>$1</h3>",
			//Size Normal
			"<h4>$1</h4>",
			//Size Moyen
			"<h6>$1</h6>",
			//Size Grand
			"<h7>$1</h7>",
			// Size 18
			"<h6>$1</h6>",
			// Quote
			"<br><u><b>Citation :</b></u><table cellpadding=\"2\" cellspacing=\"1\" border=\"0\" width=\"100%\" align=\"left\" bgcolor=\"red\"><tr><td bgcolor=\"#FFFFFF\"><font color=\"#000000\"><b>$1</b><br></font></td></tr></table><br><br>",
			// Quote nomm�
			"<br><u><b>$1 :</b></u><table cellpadding=\"2\" cellspacing=\"1\" border=\"0\" width=\"100%\" align=\"left\" bgcolor=\"red\"><tr><td bgcolor=\"#FFFFFF\"><font color=\"#000000\"><b>$2</b><br></font></td></tr></table><br><br>",
			// Code
			"<br><u><b>Code :</b></u><table cellpadding=\"2\" cellspacing=\"1\" border=\"0\" width=\"100%\" align=\"left\" bgcolor=\"red\"><tr><td bgcolor=\"#FFFFFF\"><font color=\"#000000\"><b>$1</b><br></font></td></tr></table><br><br>",

		);

		$texte = preg_replace($bbcode,$html,$texte);

		return $texte ;

	}
	##############################################################################################################
	function div_page($nb_s,$d,$id,$cat,$nb_ep)
	{
		if($nb_s > $nb_ep)
		{ 
			$nb_p  =  ceil($nb_s / $nb_ep);
			$p_suiv = $d+1;
			$p_prec = $d-1;

			if($d > 1)
			{
				echo '<a href="?cat='.$cat.'&amp;id='.$id.'&amp;d=1">&lt;&lt;&nbsp;</a>';
				echo '<a href="?cat='.$cat.'&amp;id='.$id.'&amp;d='.$p_prec.'">&lt;&nbsp;</a>';
			}

			echo '<span class="p">&nbsp;Page n� '.$d.' / '.$nb_p.'</span>'; 

			if($d < $nb_p)
			{
				echo '<a href="?cat='.$cat.'&amp;id='.$id.'&amp;d='.$p_suiv.'">&nbsp;&gt;</a>';
				echo '<a href="?cat='.$cat.'&amp;id='.$id.'&amp;d='.$nb_p.'">&nbsp;&gt;&gt;</a>';
				}
			echo ' <br /><br />';
		}
	}

	function div_page_l($nb_s,$d,$id,$p,$s)
	{

		if(strlen($s) > 1)
		{
			$s_lien = "&amp;s=".$s;
		}
		else
		{
			$s_lien = "";
		}

		if($nb_s > 10)
		{  
			echo' <br />';
			$nb_p  =  ceil($nb_s / 10);
			$p_suiv = $p+1;
			$p_prec = $p-1;

			if($p > 1)
			{
				echo '<a href="?cat=4&amp;id='.$id.'&amp;d='.$d.'&amp;p=1'.$s_lien.'">&lt;&lt;&nbsp;</a>';
				echo '<a href="?cat=4&amp;id='.$id.'&amp;d='.$d.'&amp;p='.$p_prec.$s_lien.'">&lt;&nbsp;</a>';
			}

			echo '<span class="p">&nbsp;Page n� '.$p.' / '.$nb_p.'</span>'; 

			if($p < $nb_p)
			{
				echo '<a href="?cat=4&amp;id='.$id.'&amp;d='.$d.'&amp;p='.$p_suiv.$s_lien.'">&nbsp;&gt;</a>';
				echo '<a href="?cat=4&amp;id='.$id.'&amp;d='.$d.'&amp;p='.$nb_p.$s_lien.'">&nbsp;&gt;&gt;</a>';
			}
			echo ' <br />';


		}

	}

	##############################################################################################################
	function smileys($chaine)
	{
		global $userrow;

		$chaine = str_replace(":D", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/sourire.gif' />", $chaine); 
		$chaine = str_replace(";)", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/clin.gif' />", $chaine); 
		$chaine = str_replace(":(", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/triste.gif' />", $chaine); 
		$chaine = str_replace(":surpris:", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/yeuxrond.gif' />", $chaine);
		$chaine = str_replace(":o", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/etonne.gif' />", $chaine); 
		$chaine = str_replace(":confus:", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/confus.gif' />", $chaine); 
		$chaine = str_replace(":lol:", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/lol.gif' />", $chaine); 
		$chaine = str_replace(":fire:", "&nbsp;<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/flame.gif' />", $chaine); 
		$chaine = str_replace(":splif:", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/petard.gif' />", $chaine);  
		$chaine = str_replace(":bigsmile:", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/green.gif' />", $chaine);  
		$chaine = str_replace(":x", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/mod.gif' />", $chaine);  
		$chaine = str_replace(":roll:", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/rolleyes.gif' />", $chaine);  
		$chaine = str_replace(":bigcry:", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/crying.gif' />", $chaine);  
		$chaine = str_replace(":colere:", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/colere.gif' />", $chaine);  
		$chaine = str_replace(":P", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/razz.gif' />", $chaine);
		$chaine = str_replace("8)", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/lunettes.gif' />", $chaine);
		$chaine = str_replace(":)", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/sourire.gif' />", $chaine);
		$chaine = str_replace("^^oops:", "<img alt='' style='border:0px;' src='templates/".$userrow["template"]."/images/forum/smileys/redface.gif.gif' />", $chaine);

		return($chaine);

	}



}

?>