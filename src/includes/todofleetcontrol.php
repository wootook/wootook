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

require_once ROOT_PATH . 'includes/deprecated.php';

/**
 * @deprecated
 * {{{
 */
include(ROOT_PATH . 'includes/functions/MissionCaseAttack.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/MissionCaseStay.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/MissionCaseStayAlly.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/MissionCaseTransport.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/MissionCaseSpy.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/MissionCaseRecycling.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/MissionCaseDestruction.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/MissionCaseColonisation.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/MissionCaseExpedition.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/SendSimpleMessage.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/SpyTarget.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/RestoreFleetToPlanet.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/StoreGoodsToPlanet.'.PHPEXT);           // includes/functions/StoreGoodsToPlanet.php
include(ROOT_PATH . 'includes/functions/InsertJavaScriptChronoApplet.'.PHPEXT); // infos.php, includes/functions/BuildFleetEventTable.php
include(ROOT_PATH . 'includes/functions/InsertGalaxyScripts.'.PHPEXT);          // galaxy.php
include(ROOT_PATH . 'includes/functions/GalaxyCheckFunctions.'.PHPEXT);         // galaxy.php
include(ROOT_PATH . 'includes/functions/ShowGalaxyRows.'.PHPEXT);               // galaxy.php
include(ROOT_PATH . 'includes/functions/GetPhalanxRange.'.PHPEXT);              // <- TODO: delete
include(ROOT_PATH . 'includes/functions/GetMissileRange.'.PHPEXT);              // <- TODO: delete
include(ROOT_PATH . 'includes/functions/GalaxyRowPos.'.PHPEXT);                 // galaxy.php
include(ROOT_PATH . 'includes/functions/GalaxyRowPlanet.'.PHPEXT);              // galaxy.php
include(ROOT_PATH . 'includes/functions/GalaxyRowPlanetName.'.PHPEXT);          // galaxy.php
include(ROOT_PATH . 'includes/functions/GalaxyRowMoon.'.PHPEXT);                // galaxy.php
include(ROOT_PATH . 'includes/functions/GalaxyRowDebris.'.PHPEXT);              // galaxy.php
include(ROOT_PATH . 'includes/functions/GalaxyRowUser.'.PHPEXT);                // galaxy.php
include(ROOT_PATH . 'includes/functions/GalaxyRowAlly.'.PHPEXT);                // galaxy.php
include(ROOT_PATH . 'includes/functions/GalaxyRowActions.'.PHPEXT);             // galaxy.php
include(ROOT_PATH . 'includes/functions/ShowGalaxySelector.'.PHPEXT);           // galaxy.php
include(ROOT_PATH . 'includes/functions/ShowGalaxyMISelector.'.PHPEXT);         // galaxy.php
include(ROOT_PATH . 'includes/functions/ShowGalaxyTitles.'.PHPEXT);             // galaxy.php
include(ROOT_PATH . 'includes/functions/GalaxyLegendPopup.'.PHPEXT);            // galaxy.php
include(ROOT_PATH . 'includes/functions/ShowGalaxyFooter.'.PHPEXT);             // galaxy.php
include(ROOT_PATH . 'includes/functions/MessageForm.'.PHPEXT);                  // alliance.php
include(ROOT_PATH . 'includes/functions/MipCombatEngine.'.PHPEXT);              // mipattack.php
include(ROOT_PATH . 'includes/functions/BuildFleetEventTable.'.PHPEXT);         // phalanx.php
/**
 * }}}
 */
