<?php
/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.txt
 * @see http://www.wootook.com/
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/aks')} (
    `id`                    BIGINT UNSIGNED     NOT NULL    AUTO_INCREMENT,
    `name`                  VARCHAR(50)         NULL,
    `teilnehmer`            TEXT                NULL,
    `flotten`               TEXT                NULL,
    `ankunft`               INT UNSIGNED        NULL,
    `galaxy`                TINYINT UNSIGNED    NULL,
    `system`                SMALLINT UNSIGNED   NULL,
    `planet`                TINYINT UNSIGNED    NULL,
    `eingeladen`            INT UNSIGNED        NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/alliance')} (
    `id`                    BIGINT UNSIGNED     NOT NULL    AUTO_INCREMENT,
    `ally_name`             VARCHAR(32)         NOT NULL,
    `ally_tag`              VARCHAR(8)          NOT NULL,
    `ally_owner`            BIGINT UNSIGNED     NOT NULL    DEFAULT 0,
    `ally_register_time`    TIMESTAMP           NOT NULL,
    `ally_description`      TEXT                NULL,
    `ally_web`              VARCHAR(255)        NULL,
    `ally_text`             TEXT                NULL,
    `ally_image`            VARCHAR(255)        NULL,
    `ally_request`          TEXT                NULL,
    `ally_request_waiting`  TEXT                NULL,
    `ally_request_notallow` BOOL                NOT NULL    DEFAULT FALSE,
    `ally_owner_range`      VARCHAR(32)         NULL,
    `ally_ranks`            TEXT                NULL,
    `ally_members`          SMALLINT UNSIGNED   NOT NULL    DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/annonce')} (
    `id`                    SMALLINT UNSIGNED   NOT NULL    AUTO_INCREMENT,
    `user`                  TEXT                NOT NULL,
    `galaxie`               TINYINT UNSIGNED    NOT NULL,
    `systeme`               SMALLINT UNSIGNED   NOT NULL,
    `metala`                DECIMAL(65,0)       NOT NULL,
    `cristala`              DECIMAL(65,0)       NOT NULL,
    `deuta`                 DECIMAL(65,0)       NOT NULL,
    `metals`                DECIMAL(65,0)       NOT NULL,
    `cristals`              DECIMAL(65,0)       NOT NULL,
    `deuts`                 DECIMAL(65,0)       NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/banned')} (
    `id`                    BIGINT UNSIGNED     NOT NULL    AUTO_INCREMENT,
    `who`                   BIGINT UNSIGNED     NOT NULL,
    `theme`                 TEXT                NOT NULL,
    `who2`                  BIGINT UNSIGNED     NOT NULL,
    `time`                  TIMESTAMP           NOT NULL,
    `longer`                INT UNSIGNED        NOT NULL    DEFAULT 3600,
    `author`                BIGINT UNSIGNED     NOT NULL,
    `email`                 VARCHAR(100)        NOT NULL,
    KEY `ID` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/buddy')} (
    `id`                    BIGINT UNSIGNED     NOT NULL    AUTO_INCREMENT,
    `sender`                BIGINT UNSIGNED     NOT NULL,
    `owner`                 BIGINT UNSIGNED     NOT NULL,
    `active`                BOOL                NOT NULL    DEFAULT TRUE,
    `text`                  TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/chat')} (
    `messageid`             BIGINT UNSIGNED     NOT NULL    AUTO_INCREMENT,
    `user`                  VARCHAR(255)        NOT NULL,
    `message`               TEXT                NOT NULL,
    `timestamp`             TIMESTAMP           NOT NULL,
    PRIMARY KEY (`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/config')} (
    `config_name`           VARCHAR(64)         NOT NULL,
    `config_value`          TEXT                NOT NULL,
    UNIQUE KEY (`config_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
INSERT INTO {$this->getTableName('legacies/config')} (`config_name`, `config_value`) VALUES
    ('game_speed', '2500'),
    ('fleet_speed', '2500'),
    ('resource_multiplier', '1000'),
    ('Fleet_Cdr', '30'),
    ('Defs_Cdr', '30'),
    ('initial_fields', '5000'),
    ('COOKIE_NAME', 'xnova-legacies'),
    ('game_name', 'Wootook:Legacies'),
    ('game_disable', '1'),
    ('close_reason', 'Le jeu est clos pour le moment!'),
    ('metal_basic_income', '20'),
    ('cristal_basic_income', '10'),
    ('deuterium_basic_income', '0'),
    ('energy_basic_income', '0'),
    ('BuildLabWhileRun', '0'),
    ('LastSettedGalaxyPos', '1'),
    ('LastSettedSystemPos', '1'),
    ('LastSettedPlanetPos', '1'),
    ('urlaubs_modus_erz', '1'),
    ('noobprotection', '1'),
    ('noobprotectiontime', '5000'),
    ('noobprotectionmulti', '5'),
    ('forum_url', 'http://board.xnova-ng.org/'),
    ('OverviewNewsFrame', '1'),
    ('OverviewNewsTEXT', 'Bienvenue sur le nouveau serveur Wootook Legacies'),
    ('OverviewExternChat', '0'),
    ('OverviewExternChatCmd', ''),
    ('OverviewBanner', '0'),
    ('OverviewClickBanner', ''),
    ('ExtCopyFrame', '0'),
    ('ExtCopyOwner', ''),
    ('ExtCopyFunct', ''),
    ('ForumBannerFrame', '0'),
    ('stat_settings', '1000'),
    ('link_enable', '0'),
    ('link_name', ''),
    ('link_url', ''),
    ('enable_announces', '1'),
    ('enable_marchand', '1'),
    ('enable_notes', '1'),
    ('bot_name', 'XNoviana Reali'),
    ('bot_adress', 'xnova@xnova.fr'),
    ('banner_source_post', '../images/bann.png'),
    ('ban_duration', '30'),
    ('enable_bot', '0'),
    ('enable_bbcode', '1'),
    ('debug', '0');
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/declared')} (
    `declarator`            TEXT                NOT NULL,
    `declared_1`            TEXT                NOT NULL,
    `declared_2`            TEXT                NOT NULL,
    `declared_3`            TEXT                NOT NULL,
    `reason`                TEXT                NOT NULL,
    `declarator_name`       TEXT                NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/errors')} (
    `error_id`              BIGINT UNSIGNED     NOT NULL    AUTO_INCREMENT,
    `error_sender`          VARCHAR(32)         NOT NULL,
    `error_time`            TIMESTAMP           NOT NULL,
    `error_type`            VARCHAR(32)         NOT NULL    DEFAULT 'unknown',
    `error_text`            TEXT,
    PRIMARY KEY (`error_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/fleets')} (
    `fleet_id`                  BIGINT UNSIGNED     NOT NULL    AUTO_INCREMENT,
    `fleet_owner`               BIGINT UNSIGNED     NOT NULL,
    `fleet_mission`             TINYINT UNSIGNED    NOT NULL,
    `fleet_amount`              DECIMAL(65,0)       NOT NULL,
    `fleet_array`               TEXT                NULL,
    `fleet_start_time`          TIMESTAMP           NOT NULL,
    `fleet_start_galaxy`        TINYINT UNSIGNED    NOT NULL,
    `fleet_start_system`        SMALLINT UNSIGNED   NOT NULL,
    `fleet_start_planet`        TINYINT UNSIGNED    NOT NULL,
    `fleet_start_type`          TINYINT UNSIGNED    NOT NULL,
    `fleet_end_time`            TIMESTAMP           NOT NULL,
    `fleet_end_stay`            TIMESTAMP           NOT NULL,
    `fleet_end_galaxy`          TINYINT UNSIGNED    NOT NULL,
    `fleet_end_system`          SMALLINT UNSIGNED   NOT NULL,
    `fleet_end_planet`          TINYINT UNSIGNED    NOT NULL,
    `fleet_end_type`            TINYINT UNSIGNED    NOT NULL,
    `fleet_taget_owner`         BIGINT UNSIGNED     NOT NULL,
    `fleet_resource_metal`      DECIMAL(65,0)       NOT NULL    DEFAULT 0,
    `fleet_resource_crystal`    DECIMAL(65,0)       NOT NULL    DEFAULT 0,
    `fleet_resource_deuterium`  DECIMAL(65,0)       NOT NULL    DEFAULT 0,
    `fleet_target_owner`        BIGINT UNSIGNED     NOT NULL,
    `fleet_group`               BIGINT UNSIGNED     NOT NULL,
    `fleet_mess`                BIGINT UNSIGNED     NOT NULL,
    `start_time` INT,
    PRIMARY KEY (`fleet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/galaxy')} (
    `galaxy`                TINYINT UNSIGNED    NOT NULL,
    `system`                SMALLINT UNSIGNED   NOT NULL,
    `planet`                TINYINT UNSIGNED    NOT NULL,
    `id_planet`             BIGINT UNSIGNED     NOT NULL,
    `metal`                 DECIMAL(65,0)       NOT NULL    DEFAULT 0,
    `crystal`               DECIMAL(65,0)       NOT NULL    DEFAULT 0,
    `id_luna`               BIGINT UNSIGNED     NULL,
    `luna`                  BOOL                NOT NULL    DEFAULT FALSE,
    PRIMARY KEY (`galaxy`, `system`, `planet`),
    KEY `galaxy` (`galaxy`),
    KEY `system` (`system`),
KEY `planet` (`planet`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/iraks')} (
    `id`                    BIGINT UNSIGNED         NOT NULL    AUTO_INCREMENT,
    `zeit`                  TIMESTAMP               NOT NULL,
    `galaxy`                TINYINT UNSIGNED        NOT NULL,
    `system`                SMALLINT UNSIGNED       NOT NULL,
    `planet`                TINYINT UNSIGNED        NOT NULL,
    `galaxy_angreifer`      TINYINT UNSIGNED        NOT NULL,
    `system_angreifer`      SMALLINT UNSIGNED       NOT NULL,
    `planet_angreifer`      TINYINT UNSIGNED        NOT NULL,
    `owner`                 BIGINT UNSIGNED         NOT NULL,
    `zielid`                BIGINT UNSIGNED         NOT NULL,
    `anzahl`                SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `primaer`               SMALLINT UNSIGNED,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/lunas')} (
    `id`                    BIGINT UNSIGNED         NOT NULL    AUTO_INCREMENT,
    `id_luna`               BIGINT UNSIGNED         NOT NULL,
    `name`                  VARCHAR(100)            NOT NULL    DEFAULT 'Lune',
    `image`                 VARCHAR(50)             NOT NULL    DEFAULT 'mond',
    `destruyed`             BOOL                    NOT NULL    DEFAULT FALSE,
    `id_owner`              BIGINT UNSIGNED         NOT NULL,
    `galaxy`                TINYINT UNSIGNED        NOT NULL,
    `system`                SMALLINT UNSIGNED       NOT NULL,
    `lunapos`               TINYINT UNSIGNED        NOT NULL,
    `temp_min`              TINYINT                 NOT NULL    DEFAULT 0,
    `temp_max`              TINYINT                 NOT NULL    DEFAULT 0,
    `diameter`              INT UNSIGNED            NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/messages')} (
    `message_id`            BIGINT UNSIGNED         NOT NULL    AUTO_INCREMENT,
    `message_owner`         BIGINT UNSIGNED         NOT NULL,
    `message_sender`        BIGINT UNSIGNED         NOT NULL,
    `message_time`          TIMESTAMP               NOT NULL,
    `message_type`          TINYINT UNSIGNED        NOT NULL,
    `message_from`          VARCHAR(50),
    `message_subject`       VARCHAR(150),
    `message_text`          TEXT,
    PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/multi')} (
    `id`                    BIGINT UNSIGNED         NOT NULL    AUTO_INCREMENT,
    `player`                BIGINT UNSIGNED         NOT NULL,
    `sharer`                BIGINT UNSIGNED         NOT NULL,
    `reason`                TEXT                    NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/notes')} (
    `id`                    BIGINT UNSIGNED         NOT NULL    AUTO_INCREMENT,
    `owner`                 BIGINT UNSIGNED         NOT NULL,
    `time`                  TIMESTAMP               NOT NULL,
    `priority`              TINYINT UNSIGNED        NOT NULL,
    `title`                 VARCHAR(32)             NOT NULL,
    `TEXT`                  TEXT                    NOT NULL    DEFAULT '',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/planets')} (
    `id`                            BIGINT UNSIGNED         NOT NULL    AUTO_INCREMENT,
    `name`                          VARCHAR(100)            NOT NULL,
    `id_owner`                      BIGINT UNSIGNED         NOT NULL,
    `id_level`                      TINYINT UNSIGNED        NOT NULL,
    `galaxy`                        TINYINT UNSIGNED        NOT NULL,
    `system`                        SMALLINT UNSIGNED       NOT NULL,
    `planet`                        TINYINT UNSIGNED        NOT NULL,
    `last_update`                   TIMESTAMP               NOT NULL,
    `planet_type`                   TINYINT UNSIGNED        NOT NULL,
    `destruyed`                     BOOL                    NOT NULL    DEFAULT FALSE,
    `b_building`                    SMALLINT UNSIGNED       NOT NULL,
    `b_building_id`                 TEXT                    NOT NULL,
    `b_tech`                        SMALLINT UNSIGNED       NOT NULL,
    `b_tech_id`                     SMALLINT UNSIGNED       NOT NULL,
    `b_hangar`                      SMALLINT UNSIGNED       NOT NULL,
    `b_hangar_id`                   TEXT                    NOT NULL,
    `b_hangar_plus`                 SMALLINT UNSIGNED       NOT NULL,
    `image`                         VARCHAR(50)             NOT NULL    DEFAULT 'normaltempplanet01',
    `diameter`                      INT UNSIGNED            NOT NULL    DEFAULT 12800,
    `points`                        DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `ranks`                         BIGINT UNSIGNED         NOT NULL,
    `field_current`                 INT UNSIGNED            NOT NULL    DEFAULT 163,
    `field_max`                     INT UNSIGNED            NOT NULL    DEFAULT 163,
    `temp_min`                      INT                     NOT NULL    DEFAULT 0,
    `temp_max`                      INT                     NOT NULL    DEFAULT 0,
    `metal`                         DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `metal_perhour`                 DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `metal_max`                     DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `cristal`                       DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `cristal_perhour`               DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `cristal_max`                   DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `deuterium`                     DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `deuterium_perhour`             DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `deuterium_max`                 DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `energy_used`                   DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `energy_max`                    DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `metal_mine`                    SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `cristal_mine`                  SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `deuterium_sintetizer`          SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `solar_plant`                   SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `fusion_plant`                  SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `robot_factory`                 SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `nano_factory`                  SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `hangar`                        SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `metal_store`                   SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `cristal_store`                 SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `deuterium_store`               SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `laboratory`                    SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `terraformer`                   SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `ally_deposit`                  SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `silo`                          SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `mondbasis`                     SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `phalanx`                       SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `sprungtor`                     SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `small_ship_cargo`              DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `big_ship_cargo`                DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `light_hunter`                  DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `heavy_hunter`                  DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `crusher`                       DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `battle_ship`                   DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `colonizer`                     DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `recycler`                      DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `spy_sonde`                     DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `bomber_ship`                   DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `solar_satelit`                 DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `destructor`                    DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `dearth_star`                   DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `battleship`                    DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `misil_launcher`                DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `small_laser`                   DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `big_laser`                     DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `gauss_canyon`                  DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `ionic_canyon`                  DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `buster_canyon`                 DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `small_protection_shield`       ENUM('0','1')           NOT NULL    DEFAULT 0,
    `big_protection_shield`         ENUM('0','1')           NOT NULL    DEFAULT 0,
    `interceptor_misil`             SMALLINT                NOT NULL    DEFAULT 0,
    `interplanetary_misil`          SMALLINT                NOT NULL    DEFAULT 0,
    `metal_mine_porcent`            TINYINT                 NOT NULL    DEFAULT 10,
    `cristal_mine_porcent`          TINYINT                 NOT NULL    DEFAULT 10,
    `deuterium_sintetizer_porcent`  TINYINT                 NOT NULL    DEFAULT 10,
    `solar_plant_porcent`           TINYINT                 NOT NULL    DEFAULT 10,
    `fusion_plant_porcent`          TINYINT                 NOT NULL    DEFAULT 10,
    `solar_satelit_porcent`         TINYINT                 NOT NULL    DEFAULT 10,
    `last_jump_time`                TIMESTAMP               NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/rw')} (
    `id_owner1`             BIGINT UNSIGNED         NOT NULL,
    `id_owner2`             BIGINT UNSIGNED         NOT NULL,
    `rid`                   VARCHAR(72)             NOT NULL,
    `raport`                LONGTEXT                NOT NULL,
    `a_zestrzelona`         TINYINT UNSIGNED        NOT NULL,
    `time`                  TIMESTAMP               NOT NULL,
    KEY (`rid`),
    UNIQUE KEY `id_owner1` (`id_owner1`,`rid`),
    UNIQUE KEY `id_owner2` (`id_owner2`,`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/statpoints')} (
    `id_owner`              BIGINT UNSIGNED         NOT NULL,
    `id_ally`               BIGINT UNSIGNED         NOT NULL,
    `stat_type`             TINYINT UNSIGNED        NOT NULL,
    `stat_code`             TINYINT UNSIGNED        NOT NULL,
    `tech_rank`             BIGINT UNSIGNED         NOT NULL,
    `tech_old_rank`         BIGINT UNSIGNED         NOT NULL,
    `tech_points`           DECIMAL(65,0)           NOT NULL,
    `tech_count`            BIGINT UNSIGNED         NOT NULL,
    `build_rank`            BIGINT UNSIGNED         NOT NULL,
    `build_old_rank`        BIGINT UNSIGNED         NOT NULL,
    `build_points`          DECIMAL(65,0)           NOT NULL,
    `build_count`           BIGINT UNSIGNED         NOT NULL,
    `defs_rank`             BIGINT UNSIGNED         NOT NULL,
    `defs_old_rank`         BIGINT UNSIGNED         NOT NULL,
    `defs_points`           DECIMAL(65,0)           NOT NULL,
    `defs_count`            BIGINT UNSIGNED         NOT NULL,
    `fleet_rank`            BIGINT UNSIGNED         NOT NULL,
    `fleet_old_rank`        BIGINT UNSIGNED         NOT NULL,
    `fleet_points`          DECIMAL(65,0)           NOT NULL,
    `fleet_count`           BIGINT UNSIGNED         NOT NULL,
    `total_rank`            BIGINT UNSIGNED         NOT NULL,
    `total_old_rank`        BIGINT UNSIGNED         NOT NULL,
    `total_points`          DECIMAL(65,0)           NOT NULL,
    `total_count`           BIGINT UNSIGNED         NOT NULL,
    `stat_date`             TIMESTAMP               NOT NULL,
    KEY (`tech_points`),
    KEY (`build_points`),
    KEY (`defs_points`),
    KEY (`fleet_points`),
    KEY (`total_points`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('legacies/users')} (
    `id`                        BIGINT UNSIGNED         NOT NULL    AUTO_INCREMENT,
    `username`                  VARCHAR(100)            NOT NULL,                   -- FIXME
    `password`                  VARCHAR(64)             NOT NULL,                   -- FIXME
    `email`                     VARCHAR(200)            NOT NULL,                   -- FIXME
    `email_2`                   VARCHAR(200)            NOT NULL,                   -- FIXME
    `lang`                      VARCHAR(3)              NOT NULL    DEFAULT 'fr',
    `authlevel`                 TINYINT UNSIGNED        NOT NULL    DEFAULT 0,      -- FIXME
    `sex`                       ENUM('M','F')           NULL        DEFAULT NULL,
    `avatar`                    VARCHAR(255)            NULL        DEFAULT NULL,
    `sign`                      TEXT                    NULL,
    `id_planet`                 BIGINT UNSIGNED         NOT NULL,                   -- FIXME
    `galaxy`                    TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `system`                    SMALLINT UNSIGNED       NOT NULL,                   -- FIXME
    `planet`                    TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `current_planet`            BIGINT UNSIGNED         NOT NULL,                   -- FIXME
    `user_lastip`               VARCHAR(16)             NOT NULL,                   -- FIXME
    `ip_at_reg`                 VARCHAR(16)             NOT NULL,                   -- FIXME
    `user_agent`                TEXT                    NOT NULL,                   -- FIXME
    `current_page`              TEXT                    NOT NULL,                   -- FIXME
    `register_time`             TIMESTAMP               NOT NULL,                   -- FIXME
    `onlinetime`                TIMESTAMP               NOT NULL,                   -- FIXME
    `dpath`                     VARCHAR(255)            NOT NULL,                   -- FIXME
    `design`                    TINYINT                 NOT NULL    DEFAULT 1,      -- FIXME
    `noipcheck`                 BOOL                    NOT NULL    DEFAULT TRUE,   -- FIXME
    `planet_sort`               TINYINT                 NOT NULL    DEFAULT 0,      -- FIXME
    `planet_sort_order`         TINYINT                 NOT NULL    DEFAULT 0,      -- FIXME
    `spio_anz`                  TINYINT UNSIGNED        NOT NULL    DEFAULT 1,      -- FIXME
    `settings_tooltiptime`      TINYINT UNSIGNED        NOT NULL    DEFAULT 5,      -- FIXME
    `settings_fleetactions`     TINYINT UNSIGNED        NOT NULL    DEFAULT 0,      -- FIXME
    `settings_allylogo`         TINYINT UNSIGNED        NOT NULL    DEFAULT 0,      -- FIXME
    `settings_esp`              TINYINT UNSIGNED        NOT NULL    DEFAULT 1,      -- FIXME
    `settings_wri`              TINYINT UNSIGNED        NOT NULL    DEFAULT 1,      -- FIXME
    `settings_bud`              TINYINT UNSIGNED        NOT NULL    DEFAULT 1,      -- FIXME
    `settings_mis`              TINYINT UNSIGNED        NOT NULL    DEFAULT 1,      -- FIXME
    `settings_rep`              TINYINT UNSIGNED        NOT NULL    DEFAULT 0,      -- FIXME
    `urlaubs_modus`             TINYINT UNSIGNED        NOT NULL    DEFAULT 0,      -- FIXME
    `urlaubs_until`             TIMESTAMP               NOT NULL,                   -- FIXME
    `db_deaktjava`              BOOL                    NOT NULL    DEFAULT FALSE,  -- FIXME
    `new_message`               TINYINT UNSIGNED        NOT NULL    DEFAULT 0,      -- FIXME
    `fleet_shortcut`            TEXT                    NULL,
    `b_tech_planet`             INT                     NOT NULL,                   -- FIXME
    `spy_tech`                  TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `computer_tech`             TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `military_tech`             TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `defence_tech`              TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `shield_tech`               TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `energy_tech`               TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `hyperspace_tech`           TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `combustion_tech`           TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `impulse_motor_tech`        TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `hyperspace_motor_tech`     TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `laser_tech`                TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `ionic_tech`                TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `buster_tech`               TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `intergalactic_tech`        TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `expedition_tech`           TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `graviton_tech`             TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `ally_id`                   BIGINT UNSIGNED         NOT NULL,
    `ally_name`                 VARCHAR(32)             NULL,                       -- FIXME
    `ally_request`              BOOL                    NOT NULL    DEFAULT FALSE,  -- FIXME
    `ally_request_text`         TEXT                    NULL,                       -- FIXME
    `ally_register_time`        TIMESTAMP               NOT NULL,                   -- FIXME
    `ally_rank_id`              BIGINT UNSIGNED         NOT NULL,                   -- FIXME
    `current_luna`              INT                     NOT NULL,                   -- FIXME
    `kolorminus`                VARCHAR(11)             NOT NULL DEFAULT 'red',
    `kolorplus`                 VARCHAR(11)             NOT NULL DEFAULT '#00FF00',
    `kolorpoziom`               VARCHAR(11)             NOT NULL DEFAULT 'yellow',
    `rpg_geologue`              TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_amiral`                TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_ingenieur`             TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_technocrate`           TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_espion`                TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_constructeur`          TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_scientifique`          TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_commandant`            TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_points`                TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_stockeur`              TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_defenseur`             TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_destructeur`           TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_general`               TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_bunker`                TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_raideur`               TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `rpg_empereur`              TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `lvl_minier`                BIGINT UNSIGNED         NOT NULL,                   -- FIXME
    `lvl_raid`                  BIGINT UNSIGNED         NOT NULL,                   -- FIXME
    `xpraid`                    BIGINT UNSIGNED         NOT NULL,                   -- FIXME
    `xpminier`                  BIGINT UNSIGNED         NOT NULL,                   -- FIXME
    `raids`                     BIGINT UNSIGNED         NOT NULL,                   -- FIXME
    `p_infligees`               DECIMAL(65,0)           NOT NULL,                   -- FIXME
    `mnl_alliance`              TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `mnl_joueur`                TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `mnl_attaque`               TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `mnl_spy`                   TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `mnl_exploit`               TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `mnl_transport`             TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `mnl_expedition`            TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `mnl_general`               TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `mnl_buildlist`             TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `bana`                      BOOL                    NOT NULL    DEFAULT FALSE,  -- FIXME
    `multi_validated`           BOOL                    NOT NULL    DEFAULT FALSE,  -- FIXME
    `banaday`                   TIMESTAMP               NULL        DEFAULT NULL,   -- FIXME
    `raids1`                    BIGINT UNSIGNED         NOT NULL,                   -- FIXME
    `raidswin`                  BIGINT UNSIGNED         NOT NULL,                   -- FIXME
    `raidsloose`                BIGINT UNSIGNED         NOT NULL,                   -- FIXME
    PRIMARY KEY (`id`),
    UNIQUE KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

