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

use Wootook\Core\Profiler,
    Wootook\Core\Mvc\View,
    Wootook\Core\Model,
    Wootook\Core\Exception as CoreException;

class Template
    extends View\View
{
    protected function _getTemplatePath($file)
    {
        if ($this->getScriptPath() == '') {
            Profiler\ErrorProfiler::getSingleton()
                ->addException(new CoreException\RuntimeException("No script path defined in block {$this->getNameInLayout()}."));
            return null;
        }

        $pattern = "{$this->getScriptPath()}/%s/%s/scripts/{$file}";
        if (($layout = $this->getLayout()) !== null) {
            $package = $this->getLayout()->getPackage();
            $theme = $this->getLayout()->getTheme();

            if ($package !== Model\Layout::DEFAULT_PACKAGE) {
                if ($theme !== Model\Layout::DEFAULT_THEME) {
                    $path = sprintf($pattern, $package, $theme);
                    if (\Wootook::fileExists($path)) {
                        return $path;
                    }
                }

                $path = sprintf($pattern, $package, Model\Layout::DEFAULT_THEME);
                if (\Wootook::fileExists($path)) {
                    return $path;
                }
            }
        } else {
            Profiler\ErrorProfiler::getSingleton()
                ->addException(new CoreException\RuntimeException("No layout defined."));
        }

        $path = sprintf($pattern, Model\Layout::DEFAULT_PACKAGE, Model\Layout::DEFAULT_THEME);
        if (\Wootook::fileExists($path)) {
            return $path;
        }

        Profiler\ErrorProfiler::getSingleton()
            ->addException(new CoreException\RuntimeException("Template '{$file}' could not be found."));
        return null;
    }
}
