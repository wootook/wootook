<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://wootook.org/
 *
 * Copyright (c) 2011-Present, Grégory PLANCHAT <g.planchat@gmail.com>
 * All rights reserved.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */

namespace Wootook\Core\Block;

class Messages
    extends Template
{
    protected $_storages = array();

    public function prepareMessages($namespace)
    {
        $this->_storages[] = $namespace;
    }

    public function renderGroupedHtml()
    {
        $messages = array();

        foreach ($this->_storages as $namespace) {
            $session = \Wootook::getSession($namespace);

            $messageList = $session->getMessages();
            if (!is_array($messageList)) {
                continue;
            }
            foreach ($messageList as $messageLevel => $messageList) {
                if (!isset($messages[$messageLevel])) {
                    $messages[$messageLevel] = $messageList;
                } else {
                    $messages[$messageLevel] += $messageList;
                }
            }
        }

        rsort($messages, SORT_NUMERIC);

        $output = '<div class="messages">';
        foreach ($messages as $messageLevel => $messageList) {
            $output .= "<ul class=\"{$messageLevel}\">";
            foreach ($messageList as $message) {
                $output .= "<li>{$message}</li>";
            }
            $output .= '</ul>';
        }
        $output .= '</div>';

        return $output;
    }
}
