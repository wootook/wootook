<?php

class Legacies_Officers_Model_Observer
{
    public static function planetUpdateListener($eventData)
    {
        if (isset($eventData['planet'])) {
            $planet = $eventData['planet'];

            $user = $planet->getUser();

            $level = floor(sqrt($user->getData('xpminier') / 500));
            if ($user->getData('lvl_minier') < $level) {
                $difference = $level - $user->getData('lvl_minier');
                if ($difference == 1) {
                    Legacies::getSession(Legacies_Empire_Model_User::SESSION_KEY)
                        ->addInfo('You gained 1 miner level.');
                } else {
                    Legacies::getSession(Legacies_Empire_Model_User::SESSION_KEY)
                        ->addInfo('You gained %1$d miner level.', (int) $difference);
                }
            }

            $level = floor(sqrt($user->getData('xpraid')));
            if ($user->getData('lvl_raid') < $level) {
                $difference = $level - $user->getData('lvl_raid');
                if ($difference == 1) {
                    Legacies::getSession(Legacies_Empire_Model_User::SESSION_KEY)
                        ->addInfo('You gained 1 raider level.');
                } else {
                    Legacies::getSession(Legacies_Empire_Model_User::SESSION_KEY)
                        ->addInfo('You gained %1$d raider level.', (int) $difference);
                }
            }
        }
    }
}