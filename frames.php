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

defined('DEPRECATION') || define('DEPRECATION', false);
defined('DEBUG') || define('DEBUG', false);

require_once dirname(__FILE__) .'/application/bootstrap.php';

Wootook_Core_ErrorProfiler::unregister(true);
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="shortcut icon" href="favicon.ico">
    <title>Wootook:Legacies</title>
  </head>
  <frameset framespacing="0" border="0" cols="190,*" frameborder="0">
    <frame name="LeftMenu" src="leftmenu.php" marginwidth="0" marginheight="0">
    <frame name="Hauptframe" src="overview.php">
    <noframes>
      <body>
        <p>Votre navigateur ne gère pas les frames.</p>
      </body>
    </noframes>
  </frameset>
</html>
