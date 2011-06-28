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

require_once (ROOT_PATH . 'includes/deprecated.php');

include(ROOT_PATH . 'includes/functions/FlyingFleetHandler.'.PHPEXT);
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
include(ROOT_PATH . 'includes/functions/StoreGoodsToPlanet.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/CheckPlanetBuildingQueue.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/CheckPlanetUsedFields.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/CreateOneMoonRecord.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/CreateOnePlanetRecord.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/InsertJavaScriptChronoApplet.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/IsTechnologieAccessible.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/GetBuildingTime.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/GetBuildingTimeLevel.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GetRestPrice.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/GetElementPrice.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/GetBuildingPrice.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/IsElementBuyable.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/CheckCookies.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/ChekUser.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/InsertGalaxyScripts.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GalaxyCheckFunctions.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/ShowGalaxyRows.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GetPhalanxRange.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GetMissileRange.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GalaxyRowPos.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GalaxyRowPlanet.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GalaxyRowPlanetName.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GalaxyRowMoon.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GalaxyRowDebris.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GalaxyRowUser.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GalaxyRowAlly.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GalaxyRowActions.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/ShowGalaxySelector.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/ShowGalaxyMISelector.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/ShowGalaxyTitles.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GalaxyLegendPopup.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/ShowGalaxyFooter.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/GetMaxConstructibleElements.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/GetElementRessources.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/ElementBuildListBox.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/ElementBuildListQueue.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/FleetBuildingPage.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/DefensesBuildingPage.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/ResearchBuildingPage.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/BatimentBuildingPage.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/CheckLabSettingsInQueue.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/InsertBuildListScript.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/AddBuildingToQueue.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/ShowBuildingQueue.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/HandleTechnologieBuild.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/BuildingSavePlanetRecord.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/BuildingSaveUserRecord.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/RemoveBuildingFromQueue.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/CancelBuildingFromQueue.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/SetNextQueueElementOnTop.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/ShowTopNavigationBar.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/SetSelectedPlanet.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/MessageForm.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/BuildFlyingFleetTable.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/SendNewPassword.'.PHPEXT);
//include(ROOT_PATH . 'includes/functions/UpdatePlanetBatimentQueueList.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/IsOfficierAccessible.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/CheckInputStrings.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/MipCombatEngine.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/DeleteSelectedUser.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/SortUserPlanets.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/BuildFleetEventTable.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/ResetThisFuckingCheater.'.PHPEXT);
include(ROOT_PATH . 'includes/functions/IsVacationMode.'.PHPEXT);

