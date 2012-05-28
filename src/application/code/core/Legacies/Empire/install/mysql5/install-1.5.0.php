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
CREATE TABLE IF NOT EXISTS {$this->getTableName('aks')} (
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('alliance')} (
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('annonce')} (
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('banned')} (
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('buddy')} (
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('chat')} (
    `messageid`             BIGINT UNSIGNED     NOT NULL    AUTO_INCREMENT,
    `user`                  VARCHAR(255)        NOT NULL,
    `message`               TEXT                NOT NULL,
    `timestamp`             TIMESTAMP           NOT NULL,
    PRIMARY KEY (`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('declared')} (
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('fleets')} (
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
    `fleet_target_owner`        BIGINT UNSIGNED     NOT NULL,
    `fleet_resource_metal`      DECIMAL(65,0)       NOT NULL    DEFAULT 0,
    `fleet_resource_crystal`    DECIMAL(65,0)       NOT NULL    DEFAULT 0,
    `fleet_resource_deuterium`  DECIMAL(65,0)       NOT NULL    DEFAULT 0,
    `fleet_group`               BIGINT UNSIGNED     NOT NULL,
    `fleet_mess`                BIGINT UNSIGNED     NOT NULL,
    `start_time`                TIMESTAMP           NOT NULL,
    PRIMARY KEY (`fleet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('galaxy')} (
    `galaxy`                SMALLINT UNSIGNED   NOT NULL,
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('iraks')} (
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('lunas')} (
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('messages')} (
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('multi')} (
    `id`                    BIGINT UNSIGNED         NOT NULL    AUTO_INCREMENT,
    `player`                BIGINT UNSIGNED         NOT NULL,
    `sharer`                BIGINT UNSIGNED         NOT NULL,
    `reason`                TEXT                    NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
CREATE TABLE IF NOT EXISTS {$this->getTableName('notes')} (
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('planets')} (
    `id`                            BIGINT UNSIGNED         NOT NULL    AUTO_INCREMENT,
    `name`                          VARCHAR(100)            NOT NULL,
    `id_owner`                      BIGINT UNSIGNED         NOT NULL,
    `id_level`                      TINYINT UNSIGNED        NOT NULL,
    `galaxy`                        TINYINT UNSIGNED        NOT NULL,
    `system`                        SMALLINT UNSIGNED       NOT NULL,
    `planet`                        TINYINT UNSIGNED        NOT NULL,
    `last_update`                   DATETIME                NOT NULL,
    `planet_type`                   TINYINT UNSIGNED        NOT NULL,
    `destruyed`                     INT UNSIGNED            NOT NULL    DEFAULT FALSE,
    `b_building`                    DATETIME                NOT NULL,
    `b_building_id`                 TEXT                    NOT NULL,
    `b_tech`                        DATETIME                NOT NULL,
    `b_tech_id`                     TEXT                    NOT NULL,
    `b_hangar`                      DATETIME                NOT NULL,
    `b_hangar_id`                   TEXT                    NOT NULL,
    `image`                         VARCHAR(50)             NOT NULL    DEFAULT 'normaltempplanet01',
    `diameter`                      INT UNSIGNED            NOT NULL    DEFAULT 12800,
    `points`                        DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `ranks`                         BIGINT UNSIGNED         NOT NULL,
    `field_current`                 SMALLINT UNSIGNED       NOT NULL    DEFAULT 0,
    `field_max`                     SMALLINT UNSIGNED       NOT NULL    DEFAULT 163,
    `temp_min`                      SMALLINT                NOT NULL    DEFAULT 0,
    `temp_max`                      SMALLINT                NOT NULL    DEFAULT 0,
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
    `supernova`                     DECIMAL(65,0)           NOT NULL    DEFAULT 0,
    `ore_miner`                     DECIMAL(65,0)           NOT NULL    DEFAULT 0,
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('rw')} (
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('statpoints')} (
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
CREATE TABLE IF NOT EXISTS {$this->getTableName('users')} (
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
    `ore_mining_tech`           TINYINT UNSIGNED        NOT NULL,                   -- FIXME
    `ally_id`                   BIGINT UNSIGNED         NOT NULL,
    `ally_name`                 VARCHAR(32)             NULL,                       -- FIXME
    `ally_request`              BOOL                    NOT NULL    DEFAULT FALSE,  -- FIXME
    `ally_request_text`         TEXT                    NULL,                       -- FIXME
    `ally_register_time`        TIMESTAMP               NOT NULL,                   -- FIXME
    `ally_rank_id`              BIGINT UNSIGNED         NOT NULL,                   -- FIXME
    `current_luna`              INT                     NOT NULL,                   -- FIXME
    `kolorminus`                VARCHAR(11)             NOT NULL    DEFAULT 'red',
    `kolorplus`                 VARCHAR(11)             NOT NULL    DEFAULT '#00FF00',
    `kolorpoziom`               VARCHAR(11)             NOT NULL    DEFAULT 'yellow',
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

/*
 * Galaxy positions generation
 */
$galaxyCount = Wootook::getConfig('engine/universe/galaxies');
$systemCount = Wootook::getConfig('engine/universe/systems');

$sql = <<<SQL_EOF
INSERT INTO {$this->getTableName('galaxy')}
    (`galaxy`, `system`, `planet`, `id_planet`, `metal`, `crystal`, `id_luna`, `luna`)
  SELECT
     _increment.galaxy AS `galaxy`,
     _increment.system AS `system`,
     0 AS `planet`,
     0 AS `id_planet`,
     0 AS `metal`,
     0 AS `crystal`,
     0 AS `id_luna`,
     0 AS `luna`
  FROM (
    SELECT
        (1 + _galaxy_10e0.galaxy + _galaxy_10e1.galaxy + _galaxy_10e2.galaxy + _galaxy_10e3.galaxy + _galaxy_10e4.galaxy) AS galaxy,
        (1 + _system_10e0.system + _system_10e1.system + _system_10e2.system + _system_10e3.system + _system_10e4.system) AS system
    FROM (
        SELECT 0 AS system
        UNION ALL
        SELECT 1 AS system
        UNION ALL
        SELECT 2 AS system
        UNION ALL
        SELECT 3 AS system
        UNION ALL
        SELECT 4 AS system
        UNION ALL
        SELECT 5 AS system
        UNION ALL
        SELECT 6 AS system
        UNION ALL
        SELECT 7 AS system
        UNION ALL
        SELECT 8 AS system
        UNION ALL
        SELECT 9 AS system
      ) AS _system_10e0
SQL_EOF;

if ($systemCount > 10) {
    $sql .= <<<SQL_EOF
    CROSS JOIN (
        SELECT 0  AS system
        UNION ALL
        SELECT 10 AS system
        UNION ALL
        SELECT 20 AS system
        UNION ALL
        SELECT 30 AS system
        UNION ALL
        SELECT 40 AS system
        UNION ALL
        SELECT 50 AS system
        UNION ALL
        SELECT 60 AS system
        UNION ALL
        SELECT 70 AS system
        UNION ALL
        SELECT 80 AS system
        UNION ALL
        SELECT 90 AS system
      ) AS _system_10e1
SQL_EOF;
} else {
    $sql .= <<<SQL_EOF
    CROSS JOIN (SELECT 0 AS system) AS _system_10e1
SQL_EOF;
}

if ($systemCount > 100) {
$sql .= <<<SQL_EOF
    CROSS JOIN (
        SELECT 0   AS system
        UNION ALL
        SELECT 100 AS system
        UNION ALL
        SELECT 200 AS system
        UNION ALL
        SELECT 300 AS system
        UNION ALL
        SELECT 400 AS system
        UNION ALL
        SELECT 500 AS system
        UNION ALL
        SELECT 600 AS system
        UNION ALL
        SELECT 700 AS system
        UNION ALL
        SELECT 800 AS system
        UNION ALL
        SELECT 900 AS system
      ) AS _system_10e2
SQL_EOF;
} else {
    $sql .= <<<SQL_EOF
    CROSS JOIN (SELECT 0 AS system) AS _system_10e2
SQL_EOF;
}

if ($systemCount > 1000) {
    $sql .= <<<SQL_EOF
    CROSS JOIN (
        SELECT 0    AS system
        UNION ALL
        SELECT 1000 AS system
        UNION ALL
        SELECT 2000 AS system
        UNION ALL
        SELECT 3000 AS system
        UNION ALL
        SELECT 4000 AS system
        UNION ALL
        SELECT 5000 AS system
        UNION ALL
        SELECT 6000 AS system
        UNION ALL
        SELECT 7000 AS system
        UNION ALL
        SELECT 8000 AS system
        UNION ALL
        SELECT 9000 AS system
      ) AS _system_10e3
SQL_EOF;
} else {
    $sql .= <<<SQL_EOF
    CROSS JOIN (SELECT 0 AS system) AS _system_10e3
SQL_EOF;
}

if ($systemCount > 10000) {
    $sql .= <<<SQL_EOF
    CROSS JOIN (
        SELECT 0     AS system
        UNION ALL
        SELECT 10000 AS system
        UNION ALL
        SELECT 20000 AS system
        UNION ALL
        SELECT 30000 AS system
        UNION ALL
        SELECT 40000 AS system
        UNION ALL
        SELECT 50000 AS system
        UNION ALL
        SELECT 60000 AS system
        UNION ALL
        SELECT 70000 AS system
        UNION ALL
        SELECT 80000 AS system
        UNION ALL
        SELECT 90000 AS system
      ) AS _system_10e4
SQL_EOF;
} else {
    $sql .= <<<SQL_EOF
    CROSS JOIN (SELECT 0 AS system) AS _system_10e4
SQL_EOF;
}

$sql .= <<<SQL_EOF
    CROSS JOIN (
        SELECT 0 AS galaxy
        UNION ALL
        SELECT 1 AS galaxy
        UNION ALL
        SELECT 2 AS galaxy
        UNION ALL
        SELECT 3 AS galaxy
        UNION ALL
        SELECT 4 AS galaxy
        UNION ALL
        SELECT 5 AS galaxy
        UNION ALL
        SELECT 6 AS galaxy
        UNION ALL
        SELECT 7 AS galaxy
        UNION ALL
        SELECT 8 AS galaxy
        UNION ALL
        SELECT 9 AS galaxy
      ) AS _galaxy_10e0
SQL_EOF;

if ($systemCount > 10) {
    $sql .= <<<SQL_EOF
    CROSS JOIN (
        SELECT 0  AS galaxy
        UNION ALL
        SELECT 10 AS galaxy
        UNION ALL
        SELECT 20 AS galaxy
        UNION ALL
        SELECT 30 AS galaxy
        UNION ALL
        SELECT 40 AS galaxy
        UNION ALL
        SELECT 50 AS galaxy
        UNION ALL
        SELECT 60 AS galaxy
        UNION ALL
        SELECT 70 AS galaxy
        UNION ALL
        SELECT 80 AS galaxy
        UNION ALL
        SELECT 90 AS galaxy
      ) AS _galaxy_10e1
SQL_EOF;
} else {
    $sql .= <<<SQL_EOF
    CROSS JOIN (SELECT 0 AS galaxy) AS _galaxy_10e1
SQL_EOF;
}

if ($systemCount > 100) {
    $sql .= <<<SQL_EOF
    CROSS JOIN (
        SELECT 0   AS galaxy
        UNION ALL
        SELECT 100 AS galaxy
        UNION ALL
        SELECT 200 AS galaxy
        UNION ALL
        SELECT 300 AS galaxy
        UNION ALL
        SELECT 400 AS galaxy
        UNION ALL
        SELECT 500 AS galaxy
        UNION ALL
        SELECT 600 AS galaxy
        UNION ALL
        SELECT 700 AS galaxy
        UNION ALL
        SELECT 800 AS galaxy
        UNION ALL
        SELECT 900 AS galaxy
      ) AS _galaxy_10e2
SQL_EOF;
} else {
    $sql .= <<<SQL_EOF
    CROSS JOIN (SELECT 0 AS galaxy) AS _galaxy_10e2
SQL_EOF;
}

if ($systemCount > 1000) {
    $sql .= <<<SQL_EOF
    CROSS JOIN (
        SELECT 0    AS galaxy
        UNION ALL
        SELECT 1000 AS galaxy
        UNION ALL
        SELECT 2000 AS galaxy
        UNION ALL
        SELECT 3000 AS galaxy
        UNION ALL
        SELECT 4000 AS galaxy
        UNION ALL
        SELECT 5000 AS galaxy
        UNION ALL
        SELECT 6000 AS galaxy
        UNION ALL
        SELECT 7000 AS galaxy
        UNION ALL
        SELECT 8000 AS galaxy
        UNION ALL
        SELECT 9000 AS galaxy
      ) AS _galaxy_10e3
SQL_EOF;
} else {
    $sql .= <<<SQL_EOF
    CROSS JOIN (SELECT 0 AS galaxy) AS _galaxy_10e3
SQL_EOF;
}

if ($systemCount > 10000) {
$sql .= <<<SQL_EOF
    CROSS JOIN (
        SELECT 0     AS galaxy
        UNION ALL
        SELECT 10000 AS galaxy
        UNION ALL
        SELECT 20000 AS galaxy
        UNION ALL
        SELECT 30000 AS galaxy
        UNION ALL
        SELECT 40000 AS galaxy
        UNION ALL
        SELECT 50000 AS galaxy
        UNION ALL
        SELECT 60000 AS galaxy
        UNION ALL
        SELECT 70000 AS galaxy
        UNION ALL
        SELECT 80000 AS galaxy
        UNION ALL
        SELECT 90000 AS galaxy
      ) AS _galaxy_10e4
SQL_EOF;
} else {
    $sql .= <<<SQL_EOF
    CROSS JOIN (SELECT 0 AS galaxy) AS _galaxy_10e4
SQL_EOF;
}

$sql .= <<<SQL_EOF
    ) _increment

  WHERE _increment.galaxy<={$galaxyCount}
    AND _increment.system<={$systemCount}
SQL_EOF;

$this->query($sql);
