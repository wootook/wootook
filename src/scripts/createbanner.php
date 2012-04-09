<?php
/**
 * Wootook Legacies
 *
 * @license http://wootook.org/license-legacies
 * @see http://wootook.org/
 *
 * Copyright (c) 2009-Present, Wootook Support Team
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *  - Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 *  - Neither the name of the team or any contributor may be used to endorse or
 * promote products derived from this software without specific prior written
 * permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing Wootook.
 *
 */
define('DISABLE_IDENTITY_CHECK', true);
require_once dirname(dirname(__FILE__)) . '/application/bootstrap.php';

define('FONT_FILE', dirname(__FILE__) . '/resources/fonts/visitor/visitor.ttf');
define('BACKGROUND_FILE', dirname(__FILE__) . '/resources/backgrounds/default.png');
define('TIMEZONE', 'Europe/Paris');

date_default_timezone_set(TIMEZONE);

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
} else {
    header('HTTP/1.1 412 Precondition Failed');
    die();
}

$textColor = array(
    Wootook_Core_Model_Image_Png::COLOR_RED   => 0xEF,
    Wootook_Core_Model_Image_Png::COLOR_GREEN => 0xEF,
    Wootook_Core_Model_Image_Png::COLOR_BLUE  => 0xEF
    );

$db = Wootook_Core_Database_ConnectionManager::getSingleton()->getConnection(Wootook_Core_Database_ConnectionManager::DEFAULT_CONNECTION_NAME);
$statement = $db->select()
    ->column(array('username' => 'username'), 'user')
    ->column(array('planet_name' => 'name'), 'planet')
    ->column(array('build_points' => 'build_points', 'fleet_points' => 'fleet_points', 'tech_points' => 'tech_points', 'total_points' => 'total_points'), 'stats')
    ->from(array('user' => $db->getTable('users')))
    ->joinLeft(array('stats' => $db->getTable('statpoints')), 'stats.id_owner=users.id')
    ->joinLeft(array('planet' => $db->getTable('planets')), 'planet.id_owner=users.id')
    ->where('user.id', $id)
    ->prepare()
;


$data = array_merge(
    array(
        'game_name' => Wootook::getGameConfig('game/general/name'),
        'date' => date('d M Y')
        ),
    $statement->fetch()
    );

$image = new Wootook_Core_Model_Image_Png();
$image
    ->setBackground(BACKGROUND_FILE)
    ->addText($data['game_name'], 24, FONT_FILE, 5, 37, 0, $textColor)
    ->addText($data['username'], 24, FONT_FILE, 250, 37, 0, $textColor)

    ->addText('Batiments:',
        14, FONT_FILE, 5, 50, 0, $textColor)
    ->addText(Math::render($data['build_points']), 14, FONT_FILE, 100, 50, 0, $textColor)
    ->addText('Flottes:', 14, FONT_FILE, 5, 70, 0, $textColor)
    ->addText(Math::render($data['fleet_points']), 14, FONT_FILE, 100, 70, 0, $textColor)

    ->addText('Technologies:', 14, FONT_FILE, 205, 50, 0, $textColor)
    ->addText(Math::render($data['tech_points']), 14, FONT_FILE, 320, 50, 0, $textColor)
    ->addText('Total:', 14, FONT_FILE, 205, 70, 0, $textColor)
    ->addText(Math::render($data['total_points']), 14, FONT_FILE, 320, 70, 0, $textColor)
;

foreach ($image->getHeaders() as $headerName => $headerValue) {
    header(sprintf('%s: %s', $headerName, $headerValue));
}

echo $image->render();
