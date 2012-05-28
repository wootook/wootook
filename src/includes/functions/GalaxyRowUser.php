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

/**
 *
 * @deprecated
 * @param Wootook_Empire_Model_Planet $currentPlanet
 * @param Wootook_Player_Model_Entity $currentPlayer
 */
function GalaxyRowUser($currentPlanet, $currentPlayer) {
    global $user;

    if (is_array($currentPlayer)) {
        $currentPlayer = new Wootook_Player_Model_Entity($currentPlayer);
    }
    if (is_array($currentPlanet)) {
        $currentPlanet = new Wootook_Empire_Model_Planet($currentPlanet);
        $currentPlanet->setData('last_update', new Wootook_Core_DateTime($currentPlanet->getData('last_update')));
    }
    if (!$currentPlayer || !$currentPlayer->getId() || !$currentPlanet || $currentPlanet->isDestroyed()) {
        return '<td width="150"></td>';
    }

    $activeNoobProtection = Wootook::getGameConfig('game/noob-protection/active');
    $noobProtectionMultiplier = Wootook::getGameConfig('game/noob-protection/multiplier');
    $noobProtectionPointsLimit = Wootook::getGameConfig('game/noob-protection/points-cap');

    $readAdapter = Wootook_Core_Database_ConnectionManager::getSingleton()->getConnection('core_read');

    $statement = $readAdapter->select()
        ->column('total_points')
        ->column('total_rank')
        ->from(array('stats' => $readAdapter->getTable('statpoints')))
        ->where('id_owner', new Wootook_Core_Database_Sql_Placeholder_Variable('player_id'))
        ->where('stat_code', 1)
        ->where('stat_type', 1)
        ->prepare()
    ;

    $statement->execute(array('player_id' => $user->getId()));
    $playerPoints = $statement->fetchColumn(0);

    $statement->execute(array('player_id' => $currentPlayer->getId()));
    $currentPlayerPoints = $statement->fetchColumn(0);
    $currentPlayerRank = $statement->fetchColumn(1);

    $classes = array();
    if ($currentPlayer->isBanned()) {
        $classes['banned'] = 'B';
    }
    if ($currentPlayer->isVacation()) {
        $classes['vacation'] = 'V';
    }
    $pastDate = new Wootook_Core_DateTime();
    $pastDate->sub(8, Wootook_Core_DateTime::DAY);
    if ($pastDate->isLater($currentPlayer->getLastLoginDate())) {
        $classes['inactive'] = 'i';
    }

    $pastDate->sub(22, Wootook_Core_DateTime::DAY);
    if ($pastDate->isLater($currentPlayer->getLastLoginDate())) {
        $classes['long-inactive'] = 'I';
    }
    if ($currentPlayer->isAuthorized(LEVEL_ADMIN)) {
        $classes['admin'] = 'A';
    } else if ($currentPlayer->isAuthorized(LEVEL_OPERATOR)) {
        $classes['operator'] = 'O';
    } else if ($currentPlayer->isAuthorized(LEVEL_MODERATOR)) {
        $classes['moderator'] = 'M';
    }

    if ($activeNoobProtection) {
        if ($playerPoints <= $noobProtectionPointsLimit && ($playerPoints * $noobProtectionMultiplier) < $currentPlayerPoints) {
            $classes['strong'] = 'F';
        } else if ($currentPlayerPoints <= $noobProtectionPointsLimit && ($currentPlayerPoints * $noobProtectionMultiplier) < $playerPoints) {
            $classes['strong'] = 'F';
        }
    }

    $output  = '<td width="150">';
    if (count($classes) > 0) {
        $output .= '<span class="' . implode(' ', array_keys($classes)) . '">' . $currentPlayer->getUsername() . '</span>';
        foreach ($classes as $class => $identifier) {
            $output .= '<span class="' . $class . '">' . $identifier . '</span>';
        }
    } else {
        $output .= '<span class="active">' . $currentPlayer->getUsername() . '</span>';
    }

    if (true || $currentPlayer->getId() !== $user->getId()) {
        $translator = Wootook::getTranslator();
        $messageUrl = Wootook::getStaticUrl('messages.php', array('mode' => 'write', 'id' => $currentPlayer->getId()));
        $buddyUrl = Wootook::getStaticUrl('buddy.php', array('a' => '2', 'u' => $currentPlayer->getId()));
        $statsUrl = Wootook::getStaticUrl('stat.php', array('who' => 'player', 'start' => 100 * floor($currentPlayerRank / 100)));

        $output .=<<<HTML_EOF
<div class="details player">
    <p class="username">{$translator->translate('Player: %s', $currentPlayer->getUsername())}</p>
    <p class="stats">{$translator->translate('Rank: %d', $currentPlayerRank)}</p>
    <p class="send-message"><a href="{$messageUrl}">{$translator->translate('Send a message')}</a></p>
    <p class="buddy"><a href="{$buddyUrl}">{$translator->translate('Add to buddy list')}</a></p>
    <p class="stats"><a href="{$statsUrl}">{$translator->translate('Statistics')}</a></p>
</div>
HTML_EOF;
    }

    $output .= '</td>';
    return $output;
}
