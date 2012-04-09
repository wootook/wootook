<?php
/*

Galacticnova : An open-source web-based game !
~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~

License: AGPL
Author: Vincent Debarre <vinilox@vinilox.eu>
Website: http://project.galacticnova.vinilox.eu/
Last revision: 13/07/11

*/

define('INSIDE' , true);
define('INSTALL' , false);
require_once dirname(__FILE__) .'/application/bootstrap.php';
includeLang('fleet');

$BoxTitle   = $lang['fl_error'];
$TxtColor   = "red";
$BoxMessage = $lang['fl_notback'];
if (isset($_POST['fleetid']) && is_numeric($_POST['fleetid'])) {
    $fleetid  = intval($_POST['fleetid']);

    $readAdapter = Wootook_Core_Database_ConnectionManager::getSingleton()->getConnection('core_write');
    $FleetRow = $readAdapter->select()
        ->from(array('fleet' => $readAdapter->getTable('fleets')))
        ->where('fleet_id', $fleetid)
        ->prepare()
        ->fetch()
    ;
    $i = 0;

    if ($FleetRow['fleet_owner'] == $user['id']) {
        if ($FleetRow['fleet_mess'] == 0 || $FleetRow['fleet_mess'] == 2) {
            if ($FleetRow['fleet_end_stay'] != 0) {
                // Faut calculer le temps reel de retour
                if ($FleetRow['fleet_start_time'] > time()) {
                    // On a pas encore entamé le stationnement
                    // Il faut calculer la parcelle de temps ecoulée depuis le lancement de la flotte
                    $CurrentFlyingTime = time() - $FleetRow['start_time'];
                } else {
                    // On est deja en stationnement
                    // Il faut donc directement calculer la durée d'un vol aller ou retour
                    $CurrentFlyingTime = $FleetRow['fleet_start_time'] - $FleetRow['start_time'];
                }
            } else {
                // C'est quoi le stationnement ??
                // On calcule sagement la parcelle de temps ecoulée depuis le depart
                $CurrentFlyingTime = time() - $FleetRow['start_time'];
            }
            $ReturnFlyingTime  = $CurrentFlyingTime + time();

            $writeAdapter = Wootook_Core_Database_ConnectionManager::getSingleton()->getConnection('core_write');
            $writeAdapter->update()
                ->into($writeAdapter->getTable('fleets'))
                ->set('fleet_start_time', time())
                ->set('fleet_end_stay', 0)
                ->set('fleet_end_time', $ReturnFlyingTime)
                ->set('fleet_target_owner', $user->getId())
                ->set('fleet_mess', 1)
                ->where('fleet_id', $fleetid)
                ->execute()
            ;

            $BoxTitle   = $lang['fl_sback'];
            $TxtColor   = "lime";
            $BoxMessage = $lang['fl_isback'];
        } elseif ($FleetRow['fleet_mess'] == 1) {
            $BoxMessage = $lang['fl_notback'];
        }
    } else {
        $BoxMessage = $lang['fl_onlyyours'];
    }
}

message ($BoxMessage, $BoxTitle, "fleet.". PHPEXT, 2);
