<?php

class Wootook_Core_Block_Template
    extends Wootook_Core_Mvc_View_View
{
    protected function _getTemplatePath($file)
    {
        if ($this->getScriptPath() == '') {
            Wootook_Core_ErrorProfiler::getSingleton()
                ->addException(new Wootook_Core_Exception_RuntimeException("No script path defined in block {$this->getNameInLayout()}."));
            return null;
        }

        $pattern = "{$this->getScriptPath()}/%s/%s/scripts/{$file}";
        if (($layout = $this->getLayout()) !== null) {
            $package = $this->getLayout()->getPackage();
            $theme = $this->getLayout()->getTheme();

            if ($package !== Wootook_Core_Model_Layout::DEFAULT_PACKAGE) {
                if ($theme !== Wootook_Core_Model_Layout::DEFAULT_THEME) {
                    $path = sprintf($pattern, $package, $theme);
                    if (Wootook::fileExists($path)) {
                        return $path;
                    }
                }

                $path = sprintf($pattern, $package, Wootook_Core_Model_Layout::DEFAULT_THEME);
                if (Wootook::fileExists($path)) {
                    return $path;
                }
            }
        } else {
            Wootook_Core_ErrorProfiler::getSingleton()
                ->addException(new Wootook_Core_Exception_RuntimeException("No layout defined."));
        }

        $path = sprintf($pattern, Wootook_Core_Model_Layout::DEFAULT_PACKAGE, Wootook_Core_Model_Layout::DEFAULT_THEME);
        if (Wootook::fileExists($path)) {
            return $path;
        }

        Wootook_Core_ErrorProfiler::getSingleton()
            ->addException(new Wootook_Core_Exception_RuntimeException("Template '{$file}' could not be found."));
        return null;
    }
}
