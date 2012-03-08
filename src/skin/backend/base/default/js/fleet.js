/**
 * This file is part of Wootook
 *
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
* @see http://www.wootook.com/
 *
 * Copyright (c) 2009-Present, XNova Support Team <http://wootook.org>
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
 * documentation for further information about customizing XNova.
 *
 */

function Fleet(fleetArray) {
    this._fleetArray = fleetArray;

    this.getMaximumSpeed = function() {
        var max = 0;

        for (var shipId in this._fleetArray) {
            document.getElementsByName('')
        }
        var msp = 1000000000;
        for (i = 200; i < 220; i++) {
        if (document.getElementsByName("ship[" + i + "]")[0]) {
        if ((document.getElementsByName("speed[" + i + "]")[0].value * 1) >= 1
        && (document.getElementsByName("ship[" + i + "]")[0].value * 1) >= 1) {
        msp = min(msp, document.getElementsByName("speed[" + i + "]")[0].value);
        }
        }
        }
        return(msp); 
        };

    return this;
}
