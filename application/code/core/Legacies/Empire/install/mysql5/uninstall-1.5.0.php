<?php
/**
 * This file is part of Wootook
 *
 * @license Modified BSD
 * @see https://github.com/gplanchat/one.platform
 *
 * Copyright (c) 2009-2010, Grégory PLANCHAT <g.planchat at gmail.com>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     - Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *
 *     - Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *
 *     - Neither the name of Grégory PLANCHAT nor the names of its
 *       contributors may be used to endorse or promote products derived from this
 *       software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *                                --> NOTICE <--
 *  This file is part of the core development branch, changing its contents will
 * make you unable to use the automatic updates manager. Please refer to the
 * documentation for further information about customizing One.Platform.
 *
 */

$this->setSetupConnection('core_setup');

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('aks')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('alliance')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('annonce')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('banned')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('buddy')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('chat')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('declared')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('fleets')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('galaxy')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('iraks')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('lunas')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('messages')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('multi')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('notes')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('planets')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('rw')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('statpoints')};
SQL_EOF;

$this->query($sql);

$sql = <<<SQL_EOF
DROP TABLE {$this->getTableName('users')};
SQL_EOF;

$this->query($sql);
