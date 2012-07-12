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

namespace Wootook\Core\Email;

use Wootook\Core,
    Wootook\Core\Exception as CoreException,
    Wootook\Core\Email\Transport,
    Wootook\Core\Email\Part;

/**
 * Email management class
 */
class Email
{
    /**
     * @var Transport\Transport
     */
    protected $_transport = null;

    /**
     * @param null|Transport\Transport $transport
     */
    public function __construct(Transport\Transport $transport = null)
    {
        if ($transport === null) {
            $transport = new Transport\Sendmail();
        }
        $this->setTransport($transport);
    }

    /**
     * @param Transport\Transport $transport
     */
    public function setTransport(Transport\Transport $transport)
    {
        $this->_transport = $transport;
    }

    /**
     * @return Transport\Transport
     */
    public function getTransport()
    {
        return $this->_transport;
    }

    /**
     * @param string|array $to
     * @param string|array $from
     * @param string $subject
     * @param $body
     * @param array $headers
     * @return Core\Email
     * @throws CoreException\RuntimeException
     */
    public function send($to, $from, $subject, $body, Array $headers = array())
    {
        try {
            $this->_transport
                ->addRecipient($to)
                ->setFrom($from)
                ->setSubject($subject)
                ->addPart(new Part\Part($body))
                ->addHeaders($headers)
                ->connect()
                ->send()
            ;
        } catch (CoreException\RuntimeException $e) {
            throw new CoreException\RuntimeException('Could not send mail.', null, $e);
        }

        $this->_transport->disconnect();

        return $this;
    }
}
