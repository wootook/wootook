<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see http://www.wootook.com/
 *
 * Copyright (c) 2009-Present, Wootook Support Team <http://www.xnova-ng.org>
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

/**
 * Deprecated method used to add a building to the planet building list.
 *
 * @deprecated
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 * @param Wootook_Empire_Model_User|array $currentUser
 * @param int $buildingId
 * @param bool $AddMode
 */
function AddBuildingToQueue($currentPlanet, $currentUser, $buildingId, $AddMode = true)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    $currentPlanet->appendBuildingQueue($buildingId, !$AddMode);

    return $currentPlanet->getBuildingQueue()->count();
}

/**
 * Deprecaed function used to save the planet record
 *
 * @deprecated
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 */
function BuildingSavePlanetRecord($currentPlanet)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    $currentPlanet->save();
}

/**
 * Deprecaed function used to save the user record
 *
 * @deprecated
 * @param Wootook_Empire_Model_User|array $currentUser
 */
function BuildingSaveUserRecord($currentUser)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentUser instanceof Wootook_Empire_Model_User) {
        trigger_error('$currentUser should be an instance of Wootook_Empire_Model_User', E_USER_WARNING);
        $currentUser = Wootook_Empire_Model_User::factory($currentUser['id']);
    }

    $currentUser->save();
}

/**
 * @deprecated
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 * @param Wootook_Empire_Model_User|array $currentUser
 */
function CheckPlanetBuildingQueue($currentPlanet, $currentUser)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    if (!$currentUser instanceof Wootook_Empire_Model_User) {
        trigger_error('$currentUser should be an instance of Wootook_Empire_Model_User', E_USER_WARNING);
        $currentUser = Wootook_Empire_Model_User::factory($currentUser['id']);
    }

    $currentPlanet->updateBuildingQueue();
}

/**
 *
 * @deprecated
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 */
function CheckPlanetUsedFields($currentPlanet)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    $currentPlanet->updateStorages()->save();
}

/**
 *
 * @deprecated
 * @param Wootook_Empire_Model_User|array $currentUser
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 * @param int $buildingId
 * @param bool $incremental
 * @param bool $destroy
 */
function GetBuildingPrice($currentUser, $currentPlanet, $buildingId, $incremental = true, $destroy = false)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    if (!$currentUser instanceof Wootook_Empire_Model_User) {
        trigger_error('$currentUser should be an instance of Wootook_Empire_Model_User', E_USER_WARNING);
        $currentUser = Wootook_Empire_Model_User::factory($currentUser['id']);
    }

    $types = Wootook_Empire_Model_Game_Types::getSingleton();

    if ($types->is($buildingId, Legacies_Empire::TYPE_BUILDING)) {
        $level = $currentPlanet->getElement($buildingId);

        if ($incremental) {
            if (!$destroy) {
                $level++;
            } else {
                $level--;
            }
        }

        $resources = $currentPlanet->getResourcesNeeded($buildingId, $level);
        return array(
            'metal'      => $resources[Legacies_Empire::RESOURCE_METAL],
            'crystal'    => $resources[Legacies_Empire::RESOURCE_CRISTAL],
            'deuterium'  => $resources[Legacies_Empire::RESOURCE_DEUTERIUM],
            'energy_max' => $resources[Legacies_Empire::RESOURCE_ENERGY]
            );
    } else if ($types->is($buildingId, Legacies_Empire::TYPE_RESEARCH)) {
        // TODO
    }

    return array(
        'metal'      => 0,
        'crystal'    => 0,
        'deuterium'  => 0,
        'energy_max' => 0
        );
}

/**
 *
 * @deprecated
 * @param Wootook_Empire_Model_User|array $currentUser
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 * @param int $buildingId
 */
function GetBuildingTime($currentUser, $currentPlanet, $buildingId)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    if (!$currentUser instanceof Wootook_Empire_Model_User) {
        trigger_error('$currentUser should be an instance of Wootook_Empire_Model_User', E_USER_WARNING);
        $currentUser = Wootook_Empire_Model_User::factory($currentUser['id']);
    }

    $types = Wootook_Empire_Model_Game_Types::getSingleton();

    if ($types->is($buildingId, Legacies_Empire::TYPE_BUILDING)) {
        return $currentPlanet->getBuildingTime($buildingId, 1);
    } else if ($types->is($buildingId, Legacies_Empire::TYPE_RESEARCH)) {
        return $currentPlanet->getResearchLab()->getBuildingTime($buildingId, 1);
    } else if ($types->is($buildingId, Legacies_Empire::TYPE_SHIP) || $types->is($buildingId, Legacies_Empire::TYPE_DEFENSE)) {
        return $currentPlanet->getShipyard()->getBuildingTime($buildingId, 1);
    }

    return 0;
}

/**
 *
 * @deprecated
 * @param Wootook_Empire_Model_User|array $currentUser
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 * @param int $Element
 * @param int|string $level
 */
function GetBuildingTimeLevel($currentUser, $currentPlanet, $buildingId, $level)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    if (!$currentUser instanceof Wootook_Empire_Model_User) {
        trigger_error('$currentUser should be an instance of Wootook_Empire_Model_User', E_USER_WARNING);
        $currentUser = Wootook_Empire_Model_User::factory($currentUser['id']);
    }

    $types = Wootook_Empire_Model_Game_Types::getSingleton();

    if ($types->is($buildingId, Legacies_Empire::TYPE_BUILDING)) {
        return $currentPlanet->getBuildingTime($buildingId, $level);
    } else if ($types->is($buildingId, Legacies_Empire::TYPE_RESEARCH)) {
        return $currentPlanet->getLaboratory()->getBuildingTime($buildingId, $level);
    } else if ($types->is($buildingId, Legacies_Empire::TYPE_SHIP) || $types->is($buildingId, Legacies_Empire::TYPE_DEFENSE)) {
        return $currentPlanet->getShipyard()->getBuildingTime($buildingId, $level);
    }

    return 0;
}

/**
 *
 * @deprecated
 * @param Wootook_Empire_Model_User|array $currentUser
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 * @param int $buildingId
 * @param unknown_type $userfactor
 */
function GetElementPrice($currentUser, $currentPlanet, $buildingId, $userfactor = true)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    if (!$currentUser instanceof Wootook_Empire_Model_User) {
        trigger_error('$currentUser should be an instance of Wootook_Empire_Model_User', E_USER_WARNING);
        $currentUser = Wootook_Empire_Model_User::factory($currentUser['id']);
    }

    global $lang;

    $prices = Wootook_Empire_Model_Game_Prices::getSingleton();
    $types = Wootook_Empire_Model_Game_Types::getSingleton();

    $array = array(
        Legacies_Empire::RESOURCE_METAL     => $lang["Metal"],
        Legacies_Empire::RESOURCE_CRISTAL   => $lang["Crystal"],
        Legacies_Empire::RESOURCE_DEUTERIUM => $lang["Deuterium"],
        Legacies_Empire::RESOURCE_ENERGY    => $lang["Energy"]
        );

    if ($types->is($buildingId, Legacies_Empire::TYPE_BUILDING)) {
        $cost = $currentPlanet->getResourcesNeeded($buildingId, $currentPlanet->getElement($buildingId) + 1);
    } else if ($types->is($buildingId, Legacies_Empire::TYPE_RESEARCH)) {
        // TODO
    } else if ($types->is($buildingId, Legacies_Empire::TYPE_SHIP) || $types->is($buildingId, Legacies_Empire::TYPE_DEFENSE)) {
        $cost = $currentPlanet->getShipyard()->getResourcesNeeded($buildingId, 1);
    } else {
        return null;
    }

    $output = $lang['Requires'] . ": ";
    foreach ($array as $resourceId => $resourceLabel) {
        if (Math::isPositive($prices[$buildingId][$resourceId])) {
            $output .= $resourceLabel . ": ";

            if (Math::comp($cost[$resourceId], $currentPlanet->getData($resourceId)) > 0) {
                $text .= '<b style="color:red;"><t title="-' . Math::render(Math::sub($cost[$resourceId], $currentPlanet->getData($resourceId))) . '">';
                $text .= '<span class="noresources">' . Math::render($cost[$resourceId]) . '</span></t></b>';
            } else {
                $text .= '<b style="color:lime;"> <span class="noresources">' . Math::render($cost[$resourceId]) . '</span></b>';
            }
        }
    }
    return $text;
}

/**
 *
 * @deprecated
 * @param int $elementId
 * @param int|string $count
 */
function GetElementRessources($elementId, $count)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    $currentUser = Wootook_Empire_Model_User::getSingleton();
    $currentPlanet = $currentUser->getCurrentPlanet();

    return $currentPlanet->getShipyard()->getResourcesNeeded($buildingId, $count);
}

/**
 *
 * @deprecated
 * @param Wootook_Empire_Model_User|array $currentUser
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 * @param int $productionTime
 *
 * @see Lagecies_Empire_Model_Planet::updateBuildingQueue()
 */
function HandleElementBuildingQueue($currentUser, $currentPlanet, $productionTime)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    return array();
}

/**
 *
 * @deprecated
 * @param bool $isUserChecked
 *
 * @see Wootook_Empire_Model_User::getSingleton()
 */
function CheckCookies($isUserChecked)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    $user = Wootook_Empire_Model_User::getSingleton();

    if ($user instanceof Wootook_Empire_Model_User) {
        return array(
            'state' => true,
            'record' => $user
            );
    } else {
        return array(
            'state' => false,
            'record' => array()
            );
    }
}

/**
 *
 * @deprecated
 * @param bool $isUserChecked
 *
 * @see Wootook_Empire_Model_User::getSingleton()
 */
function CheckTheUser($isUserChecked)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    $result = CheckCookies($isUserChecked);

    if ($result['record'] instanceof Wootook_Empire_Model_User && $result['record']['bana']) {
        die(parsetemplate(gettemplate('usr_banned'), $lang));
    }

    return $result;
}

/**
 *
 * @deprecated
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 * @param Wootook_Empire_Model_User|array $currentUser
 *
 * @see Wootook_Empire_Model_Planet::updateBuildingQueue()
 */
function UpdatePlanetBatimentQueueList($currentPlanet, $currentUser)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    $currentPlanet->updateBuildingQueue(Beyond::now());

    return true;
}

/**
 *
 * @deprecated
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 */
function CalculateMaxPlanetFields($currentPlanet)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    return max(1, $currentPlanet['field_max'] + ($currentPlanet->getElement(Legacies_Empire::ID_BUILDING_TERRAFORMER) * 5));
}


/**
 *
 * @deprecated
 * @param Wootook_Empire_Model_User|array $currentUser
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 */
function AbandonColony($currentUser, $currentPlanet)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    try {
        $currentPlanet->destroy();
    } catch (Wootook_Core_Model_Exception $e) {
        Wootook_Core_Model_Session::factory('empire')->addCritical($e->getMessage());
    } catch (Wootook_Empire_Model_Planet_Exception $e) {
        Wootook_Core_Model_Session::factory('empire')->addError($e->getMessage());
    }
}

/**
 *
 * @deprecated
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 */
function CheckFleets($currentPlanet)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    return (bool) ($currentPlanet->getFleetCollection()->count() > 0);
}

/**
 *
 * @deprecated
 * @param Wootook_Empire_Model_User|array $currentUser
 * @param Wootook_Empire_Model_Planet|array $currentPlanet
 */
function CancelBuildingFromQueue($currentPlanet, $currentUser)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    if (!$currentPlanet instanceof Wootook_Empire_Model_Planet) {
        trigger_error('$currentPlanet should be an instance of Wootook_Empire_Model_Planet', E_USER_WARNING);
        $currentPlanet = Wootook_Empire_Model_Planet::factory($currentPlanet['id']);
    }

    if ($currentPlanet->getBuildingQueue()->count() <= 0) {
        return false;
    }

    $currentPlanet->dequeueBuilding();

    return true;
}

class Deprecated
{
    /**
     * @var Wootook_Core_Layout $layout
     */
    public static $layout = null;

    /**
     * @return Wootook_Core_Layout
     */
    public static function getLayout($adminPage = false)
    {
        if (self::$layout === null) {
            self::$layout = new Wootook_Core_Layout();

            if (defined('IN_INSTALL')) {
                self::$layout->setPackage('install');
                self::$layout->setTheme('default');
            }
        }

        return self::$layout;
    }
}

/**
 *
 * @deprecated
 * @param string $page The page content
 * @param string $title The page title
 * @param bool $topnav Wether we show the top navigation or not
 * @param null $metatags Extra meta tags
 * @param bool $adminPage unused
 */
function display($page, $title = '', $topnav = true, $metatags = '', $adminPage = false)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    // TODO: implement extra meta tags
    $layout = Deprecated::getLayout($adminPage);
    if ($adminPage === false) {
        $layout->load('empire');
    } else {
        $layout->load('admin');
    }

    $content = $layout->getBlock('content');

    if ($topnav) {
        $topnav = $layout->createBlock('empire/topnav', 'topnav');
        $topnav->setTemplate('empire/topnav.phtml');
        $content->topnav = $topnav;
    }

    $pageContent = $layout->createBlock('core/text', 'content');
    $pageContent->setContent($page);
    $content->page = $pageContent;

    echo $layout->render();
    exit(0);
}

/**
 *
 * @deprecated
 * @param string $template The page template
 * @param array $array The view data
 */
function parsetemplate($template, $array)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    static $baseUrl = null;
    if ($baseUrl === null) {
        $user = Wootook_Empire_Model_User::getSingleton();
        if ($user !== null && $user->getId() && ($baseUrl = $user->getSkinPath()) == '') {
            $baseUrl = DEFAULT_SKINPATH;
        }
    }
    $array['dpath'] = $baseUrl;

    $layout = Deprecated::getLayout();
    $view = $layout->createBlock('core/deprecated', uniqid(), $array);
    $view->setTemplate('deprecated/' .$template . '.tpl');

    return $view->render();
}

/**
 *
 * @deprecated
 * @param string $filename
 * @return string
 */
function ReadFromFile($filename)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    return file_get_contents($filename);
}

/**
 *
 * @deprecated
 * @param string $filename
 * @param string $content
 * @return void
 */
function saveToFile($filename, $content)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    file_put_contents($filename, $content);
}

/**
 *
 * @deprecated
 * @param string $templateName
 * @return string
 */
function getTemplate($templateName)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    return $templateName;
}


/**
 *
 * Enter description here ...
 * @deprecated
 * @param string $query
 * @param string $table
 * @param bool $fetch
 */
function doquery($query, $table, $fetch = false)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    /**
     * @var Wootook_Database $database
     */
    $database = Wootook_Database::getSingleton();

    $sql = str_replace("{{table}}", $database->getTable($table), $query);

    /**
     * @var PDOStatement $statement
     */
    try {
        $statement = $database->prepare($sql);

        $statement->execute(array());
    } catch (PDOException $e) {
        trigger_error($e->getMessage() . PHP_EOL . "<br /><pre></code>$sql<code></pre><br />" . PHP_EOL, E_USER_WARNING);
    }

    if ($fetch) {
        return $statement->fetch(PDO::FETCH_BOTH);
    } else {
        return $statement;
    }
}

/**
 *
 * @deprecated
 * @param unknown_type $UserID
 */
function ResetThisFuckingCheater($userId)
{
    defined('DEPRECATION') || trigger_error(sprintf('%s is deprecated', __FUNCTION__), E_USER_DEPRECATED);

    $user = Wootook_Empire_Model_User::factory($userId);
    $username = $user->getData('username');
    $email = $user->getData('email');
    $password = $user->getData('password');
    $user->delete();

    $user = Wootook_Empire_Model_User::register($TheUser['username'], $TheUser['email'], 'password');
    $user->setData('password', $password);
    $user->save();
    return;
}