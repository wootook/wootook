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

$this->setSetupConnection('core_setup');

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('core_website')} (
    `website_id`            TINYINT UNSIGNED    NOT NULL,
    `code`                  VARCHAR(64)         NOT NULL,
    `name`                  VARCHAR(255)        NOT NULL,
    `sort_order`            TINYINT UNSIGNED    NOT NULL,
    `default_group_id`      TINYINT UNSIGNED    NOT NULL,
    `is_default`            BOOL                NOT NULL,
    `is_staging`            BOOL                NOT NULL,
    `is_active`             BOOL                NOT NULL,
    PRIMARY KEY (`website_id`),
    UNIQUE (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('core_game_group')} (
    `group_id`              TINYINT UNSIGNED    NOT NULL,
    `website_id`            TINYINT UNSIGNED    NOT NULL,
    `code`                  VARCHAR(64)         NOT NULL,
    `name`                  VARCHAR(255)        NOT NULL,
    `sort_order`            TINYINT UNSIGNED    NOT NULL,
    `is_default`            BOOL                NOT NULL,
    PRIMARY KEY (`group_id`),
    INDEX (`website_id`),
    UNIQUE (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('core_game')} (
    `game_id`               SMALLINT UNSIGNED   NOT NULL,
    `group_id`              TINYINT UNSIGNED    NOT NULL,
    `website_id`            TINYINT UNSIGNED    NOT NULL,
    `code`                  VARCHAR(64)         NOT NULL,
    `name`                  VARCHAR(255)        NOT NULL,
    `sort_order`            TINYINT UNSIGNED    NOT NULL,
    `is_default`            BOOL                NOT NULL,
    `is_staging`            BOOL                NOT NULL,
    `is_active`             BOOL                NOT NULL,
    PRIMARY KEY (`group_id`),
    INDEX (`website_id`),
    UNIQUE (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
INSERT INTO {$this->getTableName('core_website')} (`website_id`, `code`, `name`, `sort_order`, `default_group_id`, `is_default`, `is_staging`, `is_active`)
VALUES
(0, "admin", "Administration", 0, 0, 0, 0, 1),
(1, "default", "Default Website", 1, 1, 1, 0, 1);
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
INSERT INTO {$this->getTableName('core_game_group')} (`group_id`, `website_id`, `code`, `name`, `sort_order`, `is_default`)
VALUES
(0, 0, "admin", "Administration", 0, 0),
(1, 1, "default", "Default Group", 1, 1);
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
INSERT INTO {$this->getTableName('core_game')} (`game_id`, `group_id`, `website_id`, `code`, `name`, `sort_order`, `is_default`, `is_staging`, `is_active`)
VALUES
(0, 0, 0, "admin", "Administration", 0, 0, 0, 1),
(1, 1, 1, "default", "Default Game", 1, 1, 0, 1);
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('core_config')} (
    `website_id`            TINYINT UNSIGNED    NOT NULL,
    `game_id`               TINYINT UNSIGNED    NOT NULL,
    `config_path`           VARCHAR(64)         NOT NULL,
    `config_value`          VARCHAR(255)        NOT NULL,
    PRIMARY KEY (`website_id`, `game_id`, `config_path`),
    INDEX (`website_id`),
    INDEX (`game_id`),
    INDEX (`config_path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
INSERT IGNORE INTO {$this->getTableName('core_config')} (`website_id`, `game_id`, `config_path`, `config_value`) VALUES
    (1, 1, 'resources/base-income/metal', '20'),
    (1, 1, 'resources/base-income/cristal', '10'),
    (1, 1, 'resources/base-income/deuterium', '0'),
    (1, 1, 'resources/base-income/energy', '0'),

    (1, 1, 'planet/initial/fields', '163'),

    (1, 1, 'resources/initial/metal', '500'),
    (1, 1, 'resources/initial/cristal', '500'),
    (1, 1, 'resources/initial/deuterium', '0'),
    (1, 1, 'resources/initial/energy', '0'),

    (1, 1, 'game/general/name', 'Wootook'),
    (1, 1, 'game/general/boards-url', 'http://wootook.org/board/'),
    (1, 1, 'game/general/extra-url-title', 'Wootook!'),
    (1, 1, 'game/general/extra-url', 'http://wootook.org/'),
    (1, 1, 'game/general/active', '1'),
    (1, 1, 'game/general/closing-message', 'Le jeu est clos pour le moment.'),
    (1, 1, 'game/general/locale', 'fr_FR'),

    (1, 1, 'game/resource/multiplier', '1000'),
    (1, 1, 'game/speed/general', '2500'),
    (1, 1, 'game/speed/fleet', '2500'),

    (1, 1, 'game/debris/metal-percent', '30'),
    (1, 1, 'game/debris/cristal-percent', '30'),
    (1, 1, 'game/debris/deuterium-percent', '0'),
    (1, 1, 'game/debris/energy-percent', '0'),

    (1, 1, 'game/debris/fleet', '1'),
    (1, 1, 'game/debris/defense', '0'),

    (1, 1, 'game/noob-protection/active', '0'),
    (1, 1, 'game/noob-protection/points-cap', '5000'),
    (1, 1, 'game/noob-protection/multiplier', '5'),

--    (0, 0, 'web/cookie/name', '__wtk'),
--    (0, 0, 'web/cookie/time', '2592000'),
--    (0, 0, 'web/cookie/domain', ''),
--    (0, 0, 'web/cookie/path', ''),

    (1, 1, 'web/cookie/name', '__wtk_1_1'),

--    (0, 0, 'web/session/time', '900'),
--    (0, 0, 'web/session/domain', '.wootook.org'),
--    (0, 0, 'web/session/path', '/'),

    (0, 0, 'engine/options/bbcode', '1'),
    (0, 0, 'engine/options/ga', '1'),
    (0, 0, 'engine/options/announces', '0'),
    (0, 0, 'engine/options/retailer', '0'),
    (0, 0, 'engine/options/notes', '0'),
    (0, 0, 'engine/options/chat', '0'),
    (0, 0, 'engine/options/banner', '0'),
    (0, 0, 'engine/options/vacation-min-time', '172800'),

    (0, 0, 'game/news/active', '0'),
    (0, 0, 'game/news/content', 'Bienvenue sur le nouveau serveur de jeu Wootook!'),

    (0, 0, 'engine/ban/duration', '86400'),

    (0, 0, 'engine/bot/active', '0'),
    (0, 0, 'engine/bot/name', 'Woot'),
    (0, 0, 'engine/bot/email', 'contact@wootook.org');
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('errors')} (
    `error_id`              BIGINT UNSIGNED     NOT NULL    AUTO_INCREMENT,
    `error_sender`          VARCHAR(32)         NOT NULL,
    `error_time`            TIMESTAMP           NOT NULL,
    `error_type`            VARCHAR(32)         NOT NULL    DEFAULT 'unknown',
    `error_text`            TEXT,
    PRIMARY KEY (`error_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);
