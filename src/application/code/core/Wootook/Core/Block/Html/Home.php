<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Greg
 * Date: 24/03/12
 * Time: 10:51
 * To change this template use File | Settings | File Templates.
 */

class Wootook_Core_Block_Html_Home
    extends Wootook_Core_Block_Template
{
    public function getTitle()
    {
        return Wootook::getGameConfig('game/home/title');
    }

    public function getFormatedWelcomeText()
    {
        return Wootook::getGameConfig('game/home/formated-welcome-text');
    }
}
