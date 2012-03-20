<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2009-Present, Wootook Support Team <http://wootook.org>
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
 * documentation for further information about customizing Wootook.
 *
 */

define('INSIDE' , true);
define('INSTALL' , false);
require_once dirname(__FILE__) .'/application/bootstrap.php';

includeLang('imperium');

$planet = $user->getPlanetCollection();

$parse['mount'] = count($planet) + 1;
// primera tabla, con las imagenes y coordenadas
$row  = gettemplate('imperium_row');
$row2 = gettemplate('imperium_row2');

foreach ($planet as $p) {
    /*
     * Update planet resources and constructions
     */
    Wootook::dispatchEvent('planet.update', array(
        'planet' => $p
        ));
    $p->save();

	// {file_images}
	$data['text'] = '<a href="overview.php?cp=' . $p['id'] . '&amp;re=0"><img src="' . $dpath . 'planeten/small/s_' . $p['image'] . '.jpg" border="0" height="71" width="75"></a>';
	$parse['file_images'] .= parsetemplate($row, $data);
	// {file_names}
	$data['text'] = $p['name'];
	$parse['file_names'] .= parsetemplate($row2, $data);
	// {file_coordinates}
	$data['text'] = "[<a href=\"galaxy.php?mode=3&galaxy={$p['galaxy']}&system={$p['system']}\">{$p['galaxy']}:{$p['system']}:{$p['planet']}</a>]";
	$parse['file_coordinates'] .= parsetemplate($row2, $data);
	// {file_fields}
	$data['text'] = $p['field_current'] . '/' . $p['field_max'];
	$parse['file_fields'] .= parsetemplate($row2, $data);
	// {file_metal}
	$data['text'] = '<a href="resources.php?cp=' . $p['id'] . '&amp;re=0&amp;planettype=' . $p['planet_type'] . '">'. pretty_number($p['metal']) .'</a> / '. pretty_number($p['metal_perhour']);
	$parse['file_metal'] .= parsetemplate($row2, $data);
	// {file_crystal}
	$data['text'] = '<a href="resources.php?cp=' . $p['id'] . '&amp;re=0&amp;planettype=' . $p['planet_type'] . '">'. pretty_number($p['crystal']) .'</a> / '. pretty_number($p['crystal_perhour']);
	$parse['file_crystal'] .= parsetemplate($row2, $data);
	// {file_deuterium}
	$data['text'] = '<a href="resources.php?cp=' . $p['id'] . '&amp;re=0&amp;planettype=' . $p['planet_type'] . '">'. pretty_number($p['deuterium']) .'</a> / '. pretty_number($p['deuterium_perhour']);
	$parse['file_deuterium'] .= parsetemplate($row2, $data);
	// {file_energy}
	$data['text'] = pretty_number($p['energy_max'] - $p['energy_used']) . ' / ' . pretty_number($p['energy_max']);
	$parse['file_energy'] .= parsetemplate($row2, $data);

	foreach ($resource as $i => $res) {
		if (in_array($i, $reslist['build']))
			$data['text'] = ($p[$resource[$i]]    == 0) ? '-' : '<a href="' . Wootook::getUrl('empire/buildings', array('cp' => $p['id'], 're' => 0, 'planettype' => $p['planet_type'])) . '">' . $p[$resource[$i]] . '</a>';
		elseif (in_array($i, $reslist['tech']))
			$data['text'] = ($p[$resource[$i]]    == 0) ? '-' : '<a href="' . Wootook::getUrl('empire/research-lab', array('cp' => $p['id'], 're' => 0, 'planettype' => $p['planet_type'])) . '">' . $p[$resource[$i]] . '</a>';
		elseif (in_array($i, $reslist['fleet']))
			$data['text'] = ($p[$resource[$i]]    == 0) ? '-' : '<a href="' . Wootook::getUrl('empire/shipyard', array('cp' => $p['id'], 're' => 0, 'planettype' => $p['planet_type'])) . '">' . $p[$resource[$i]] . '</a>';
		elseif (in_array($i, $reslist['defense']))
			$data['text'] = ($p[$resource[$i]]    == 0) ? '-' : '<a href="' . Wootook::getUrl('empire/defenses', array('cp' => $p['id'], 're' => 0, 'planettype' => $p['planet_type'])) . '">' . $p[$resource[$i]] . '</a>';

		$r[$i] .= parsetemplate($row2, $data);
	}
}

// {building_row}
foreach ($reslist['build'] as $a => $i) {
	$data['text'] = $lang['tech'][$i];
	$parse['building_row'] .= "<tr>" . parsetemplate($row2, $data) . $r[$i] . "</tr>";
}
// {technology_row}
foreach ($reslist['tech'] as $a => $i) {
	$data['text'] = $lang['tech'][$i];
	$parse['technology_row'] .= "<tr>" . parsetemplate($row2, $data) . $r[$i] . "</tr>";
}
// {fleet_row}
foreach ($reslist['fleet'] as $a => $i) {
	$data['text'] = $lang['tech'][$i];
	$parse['fleet_row'] .= "<tr>" . parsetemplate($row2, $data) . $r[$i] . "</tr>";
}
// {defense_row}
foreach ($reslist['defense'] as $a => $i) {
	$data['text'] = $lang['tech'][$i];
	$parse['defense_row'] .= "<tr>" . parsetemplate($row2, $data) . $r[$i] . "</tr>";
}

$page .= parsetemplate(gettemplate('imperium_table'), $parse);

display($page, $lang['Imperium'], false);

?>
