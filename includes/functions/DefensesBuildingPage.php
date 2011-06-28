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
 * @param unknown_type $currentPlanet
 * @param unknown_type $currentUser
 */
function DefensesBuildingPage ( &$currentPlanet, $currentUser ) {
    global $lang, $resource, $dpath;
    trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    // S'il n'y a pas de Chantier
    if (!isset($currentPlanet[$resource[Legacies_Empire::ID_BUILDING_SHIPYARD]]) || $currentPlanet[$resource[Legacies_Empire::ID_BUILDING_SHIPYARD]] == 0) {
        message($lang['need_hangar'], $lang['tech'][Legacies_Empire::ID_BUILDING_SHIPYARD]);
        return;
    }

    $shipyard = Legacies_Empire_Model_Planet_Building_Shipyard::factory($currentPlanet, $currentUser);
    if (isset($_POST['fmenge']) && is_array($_POST['fmenge'])) {
        foreach ($_POST['fmenge'] as $shipId => $count) {
            $shipId = intval($shipId);
            if (in_array($shipId, $resource)) {
                continue;
            }
            $count = intval($count);

            $shipyard->appendQueue($shipId, $count);
        }
        $currentPlanet = $shipyard->save();
    }

    // -------------------------------------------------------------------------------------------------------
    // Construction de la page du Chantier (car si j'arrive ici ... c'est que j'ai tout ce qu'il faut pour ...
    $TabIndex  = 0;
    $PageTable = "";
    $types = include ROOT_PATH . 'includes/data/types.php';
    foreach ($types[Legacies_Empire::TYPE_DEFENSE] as $shipId) {
        if ($shipyard->checkAvailability($shipId)) {
            // Disponible à la construction

            // On regarde combien de temps il faut pour construire l'element
            $BuildOneElementTime = $shipyard->getBuildTime($shipId, 1);
            // Disponibilité actuelle
            $shipIdCount        = $currentPlanet[$resource[$shipId]];
            $shipIdNbre         = ($shipIdCount == 0) ? "" : " (".$lang['dispo'].": " . pretty_number($shipIdCount) . ")";

            // Construction des 3 cases de la ligne d'un element dans la page d'achat !
            // Début de ligne
            $PageTable .= "\n<tr>";

            // Imagette + Link vers la page d'info
            $PageTable .= "<th class=l>";
            $PageTable .= "<a href=infos.".PHPEXT."?gid=".$shipId.">";
            $PageTable .= "<img border=0 src=\"".$dpath."gebaeude/".$shipId.".gif\" align=top width=120 height=120></a>";
            $PageTable .= "</th>";

            // Description
            $PageTable .= "<td class=l>";
            $PageTable .= "<a href=infos.".PHPEXT."?gid=".$shipId.">".$shipIdName."</a> ".$shipIdNbre."<br>";
            $PageTable .= "".$lang['res']['descriptions'][$shipId]."<br>";
            // On affiche le 'prix' avec eventuellement ce qui manque en ressource
            $PageTable .= GetElementPrice($currentUser, $currentPlanet, $shipId, false);
            // On affiche le temps de construction (c'est toujours tellement plus joli)
            $PageTable .= ShowBuildTime($BuildOneElementTime);
            $PageTable .= "</td>";

            // Case nombre d'elements a construire
            $PageTable .= "<td class=k>";
            // Si ... Et Seulement si je peux construire je mets la p'tite zone de saisie
            $maxElements = $shipyard->getMaximumBuildableElementsCount($shipId);
            if (bccomp($maxElements, 0) > 0) {
                $TabIndex++;
                $PageTable .= "<input type=\"text\" id=\"fmenge:{$shipId}\" name=\"fmenge[".$shipId."]\" alt='".$lang['tech'][$shipId]."' size=5 maxlength=5 value=0 tabindex=".$TabIndex.">";

                if (MAX_FLEET_OR_DEFS_PER_ROW > 0 && $maxElements > MAX_FLEET_OR_DEFS_PER_ROW) {
                    $maxElements = MAX_FLEET_OR_DEFS_PER_ROW;
                }

                $PageTable .= '<br /><a onclick="document.getElementById(\'fmenge:'.$shipId.'\').value=\''.strval($maxElements).'\';" style="cursor:pointer;">Nombre max ('.number_format($maxElements, 0, ',', '.').')</a>';
            } else if (in_array($shipId, array(Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME, Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME))) {
                $PageTable .= '<span style="color:red">Limite de construction atteinte.</span>';
            } else if (in_array($shipId, array(Legacies_Empire::ID_DEFENSE_SMALL_SHIELD_DOME, Legacies_Empire::ID_DEFENSE_LARGE_SHIELD_DOME))) {
                $PageTable .= '<span style="color:red">Silo plein.</span>';
            }
            $PageTable .= '</td>';

            // Fin de ligne (les 3 cases sont construites !!
            $PageTable .= "</tr>";
        }
    }

    if (!empty($currentPlanet['b_hangar_id'])) {
        $data = array();
        foreach ($shipyard->getQueue() as $item) {
            $data[] = array_merge($item, array(
                'label' => $lang['tech'][$item['ship_id']],
                'speed' => $shipyard->getBuildTime($item['ship_id'], 1)
                ));
        }
        $parse = array(
            'data' => json_encode($data)
            );
        $BuildQueue = parsetemplate(gettemplate('buildings_script'), $parse);
    }

    $parse = $lang;
    // La page se trouve dans $PageTable;
    $parse['buildlist']    = $PageTable;
    // Et la liste de constructions en cours dans $BuildQueue;
    $parse['buildinglist'] = $BuildQueue;
    // fragmento de template
    $page .= parsetemplate(gettemplate('buildings_defense'), $parse);

    display($page, $lang['Defense']);

}
// Version History
// - 1.0 Modularisation
// - 1.1 Correction mise en place d'une limite max d'elements constructibles par ligne
// - 1.2 Correction limitation bouclier meme si en queue de fabrication
//
?>