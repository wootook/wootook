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

$cacheFile = ROOT_PATH . '/cache/' . basename(__FILE__) . '.cache';
$timeDelay = 21600; // 21600s = 6h

if(!file_exists($cacheFile) || (time() - filemtime($cacheFile)) > $timeDelay)
{
    ob_start();

    includeLang('records');

    $recordTpl = gettemplate('records_body');
    $headerTpl = gettemplate('records_section_header');
    $tableRows = gettemplate('records_section_rows');
    $parse['rec_title'] = $lang['rec_title'];

    $bloc['section']    = $lang['rec_build'];
    $bloc['player']     = $lang['rec_playe'];
    $bloc['level']      = $lang['rec_level'];
    $parse['building']  = parsetemplate($headerTpl, $bloc);

    $bloc['section']    = $lang['rec_specb'];
    $bloc['player']     = $lang['rec_playe'];
    $bloc['level']      = $lang['rec_level'];
    $parse['buildspe']  = parsetemplate($headerTpl, $bloc);

    $bloc['section']    = $lang['rec_techn'];
    $bloc['player']     = $lang['rec_playe'];
    $bloc['level']      = $lang['rec_level'];
    $parse['research']  = parsetemplate($headerTpl, $bloc);

    $bloc['section']    = $lang['rec_fleet'];
    $bloc['player']     = $lang['rec_playe'];
    $bloc['level']      = $lang['rec_nbre'];
    $parse['fleet']     = parsetemplate($headerTpl, $bloc);

    $bloc['section']    = $lang['rec_defes'];
    $bloc['player']     = $lang['rec_playe'];
    $bloc['level']      = $lang['rec_nbre'];
    $parse['defenses']  = parsetemplate($headerTpl, $bloc);


    foreach($lang['tech'] as $element => $elementName)
    {
        if(!empty($elementName) && !empty($resource[$element]))
        {
            $data = array();
            if($element >= 0 && $element <  100 || $element >= 200 && $element < 600)
            {
                $record = doquery(sprintf(
                    'SELECT IF(COUNT(u.username)<=10,GROUP_CONCAT(DISTINCT u.username ORDER BY u.username DESC SEPARATOR ", "),"Plus de 10 joueurs ont ce record") AS players, p.%1$s AS level ' .
                    'FROM {{table}}users AS u ' .
                    'LEFT JOIN {{table}}planets AS p ON (u.id=p.id_owner) ' .
                    'WHERE p.%1$s=(SELECT MAX(p2.%1$s) FROM {{table}}planets AS p2) AND p.%1$s>0 ' .
                    'GROUP BY p.%1$s ORDER BY u.username ASC', $resource[$element]), '', true);
            }
            else if($element >= 100 && $element < 200)
            {
                $record = doquery(sprintf(
                    'SELECT IF(COUNT(u.username)<=10,GROUP_CONCAT(DISTINCT u.username ORDER BY u.username DESC SEPARATOR ", "),"Plus de 10 joueurs ont ce record") AS players, u.%1$s AS level ' .
                    'FROM {{table}}users AS u ' .
                    'WHERE u.%1$s=(SELECT MAX(u2.%1$s) FROM {{table}}users AS u2) AND u.%1$s>0 ' .
                    'GROUP BY u.%1$s ORDER BY u.username ASC', $resource[$element]), '', true);
            }
            else
            {
                continue;
            }

            $data['element'] = $elementName;
            $data['winner'] = !empty($record['players']) ? $record['players'] : '-';
            $data['count'] = intval($record['level']);

            if($element >= 0 && $element < 40 || $element == 44)
            {
                $parse['building'] .= parsetemplate($tableRows, $data);
            }
            else if($element >= 40 && $element < 100 && $element != 44)
            {
                $parse['buildspe'] .= parsetemplate($tableRows, $data);
            }
            else if($element >= 100 && $element < 200)
            {
                $parse['research'] .= parsetemplate($tableRows, $data);
            }
            else if($element >= 200 && $element < 400)
            {
                $data['count'] = number_format(intval($data['count']), 0, ',', '.');
                $parse['fleet'] .= parsetemplate($tableRows, $data);
            }
            else if($element >= 400 && $element < 600 && $element!=407 && $element!=408)
            {
                $data['count'] = number_format(intval($data['count']), 0, ',', '.');
                $parse['defenses'] .= parsetemplate($tableRows, $data);
            }
        }
    }

    $page = parsetemplate($recordTpl, $parse);
    display($page, $lang['rec_title']);

    $data = ob_get_contents();
    ob_end_flush();

    file_put_contents($cacheFile, $data);
}
else
{
    echo file_get_contents($cacheFile);
}