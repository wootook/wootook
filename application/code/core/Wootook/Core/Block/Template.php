<?php

class Wootook_Core_Block_Template
    extends Wootook_Core_View
{
    protected function _getTemplatePath($file)
    {
        if ($this->getScriptPath() == '') {
            trigger_error("No script path defined in block {$this->getNameInLayout()}.", E_USER_NOTICE);
            return null;
        }

        $pattern = "{$this->getScriptPath()}/%s/%s/scripts/{$file}";
        if (($layout = $this->getLayout()) !== null) {
            $package = $this->getLayout()->getPackage();
            $theme = $this->getLayout()->getTheme();

            if ($package !== Wootook_Core_Layout::DEFAULT_PACKAGE) {
                if ($theme !== Wootook_Core_Layout::DEFAULT_THEME) {
                    $path = sprintf($pattern, $package, $theme);
                    if (Wootook::fileExists($path)) {
                        return $path;
                    }
                }

                $path = sprintf($pattern, $package, Wootook_Core_Layout::DEFAULT_THEME);
                if (Wootook::fileExists($path)) {
                    return $path;
                }
            }
        } else {
            trigger_error("No layout defined.", E_USER_NOTICE);
        }

        $path = sprintf($pattern, Wootook_Core_Layout::DEFAULT_PACKAGE, Wootook_Core_Layout::DEFAULT_THEME);
        if (Wootook::fileExists($path)) {
            return $path;
        }

        trigger_error("Template '{$file}' could not be found.", E_USER_ERROR);
        return null;
    }
}