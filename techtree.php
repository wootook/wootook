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

define('INSIDE' , true);
define('INSTALL' , false);
require_once dirname(__FILE__) .'/common.php';
	$HeadTpl = gettemplate('techtree_head');
	$RowTpl  = gettemplate('techtree_row');
	foreach($lang['tech'] as $Element => $ElementName) {
		$parse            = array();
		$parse['tt_name'] = $ElementName;
		if (!isset($resource[$Element])) {
			$parse['Requirements']  = $lang['Requirements'];
			$page                  .= parsetemplate($HeadTpl, $parse);
		} else {
			if (isset($requirements[$Element])) {
				$parse['required_list'] = "";
				foreach($requirements[$Element] as $ResClass => $Level) {
					if       ( isset( $user[$resource[$ResClass]] ) &&
						 $user[$resource[$ResClass]] >= $Level) {
						$parse['required_list'] .= "<font color=\"#00ff00\">";
					} elseif ( isset($planetrow[$resource[$ResClass]] ) &&
						$planetrow[$resource[$ResClass]] >= $Level) {
						$parse['required_list'] .= "<font color=\"#00ff00\">";
					} else {
						$parse['required_list'] .= "<font color=\"#ff0000\">";
					}
					$parse['required_list'] .= $lang['tech'][$ResClass] ." (". $lang['level'] ." ". $Level .")";
					$parse['required_list'] .= "</font><br>";
				}
				$parse['tt_detail']      = "<a href=\"techdetails.php?techid=". $Element ."\">" .$lang['treeinfo'] ."</a>";
			} else {
				$parse['required_list'] = "";
				$parse['tt_detail']     = "";
			}
			$parse['tt_info']   = $Element;
			$page              .= parsetemplate($RowTpl, $parse);
		}
	}

	$parse['techtree_list'] = $page;
	$page                   = parsetemplate(gettemplate('techtree_body'), $parse);

	display($page, $lang['Tech']);

// -----------------------------------------------------------------------------------------------------------
// History version
// - 1.0 mise en conformité code avec skin XNova
// - 1.1 ajout lien pour les details des technos
// - 1.2 suppression du lien details ou il n'est pas necessaire
?>
