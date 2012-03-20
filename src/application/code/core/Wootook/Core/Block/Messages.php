<?php

class Wootook_Core_Block_Messages
    extends Wootook_Core_Block_Template
{
    protected $_storages = array();

    public function prepareMessages($namespace)
    {
        $this->_storages[] = $namespace;
    }

    public function renderGroupedHtml()
    {
        $messages = array();

        foreach ($this->_storages as $namespace) {
            $session = Wootook::getSession($namespace);

            $messageList = $session->getMessages();
            if (!is_array($messageList)) {
                continue;
            }
            foreach ($messageList as $messageLevel => $messageList) {
                if (!isset($messages[$messageLevel])) {
                    $messages[$messageLevel] = $messageList;
                } else {
                    $messages[$messageLevel] += $messageList;
                }
            }
        }

        rsort($messages, SORT_NUMERIC);

        $output = '<div class="messages">';
        foreach ($messages as $messageLevel => $messageList) {
            $output .= "<ul class=\"{$messageLevel}\">";
            foreach ($messageList as $message) {
                $output .= "<li>{$message}</li>";
            }
            $output .= '</ul>';
        }
        $output .= '</div>';

        return $output;
    }
}
