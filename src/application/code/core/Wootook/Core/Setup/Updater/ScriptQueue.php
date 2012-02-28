<?php

class Wootook_Core_Setup_Updater_ScriptQueue
    implements Iterator, Countable, ArrayAccess
{
    const PCRE_FILE_PATTERN = '%^(?<action>install|upgrade|uninstall|downgrade)-(?<version1>[0-9]+\.[0-9]+\.[0-9]+)(?:\.(?<stage1>[a-z]+)(?<level1>[0-9]+))?(?:-(?<version2>[0-9]+\.[0-9]+\.[0-9]+)(?:\.(?<stage2>[a-z]+)(?<level2>[0-9]+))?)?$%';
    const PCRE_VERSION_PATTERN = '%^(?<version>[0-9]+\.[0-9]+\.[0-9]+)(?:-(?<stage>[a-z]+)(?<level>[0-9]+)?)?$%';

    const STAGE_ALPHA  = 'alpha';
    const STAGE_BETA   = 'beta';
    const STAGE_RC     = 'rc';
    const STAGE_STABLE = 'stable';

    const MODE_INSTALL   = 'install';
    const MODE_UNINSTALL = 'uninstall';
    const MODE_UPGRADE   = 'upgrade';
    const MODE_DOWNGRADE = 'downgrade';

    const VERSION_NULL = '0.0.0';

    protected $_installVersions = array();
    protected $_upgradeVersions = array();
    protected $_uninstallVersions = array();
    protected $_downgradeVersions = array();

    protected $_finalVersion = null;

    protected $_scripts = array();

    public function __construct($path, $fromVersion = null, $toVersion = null)
    {
        try {
            $this->_analyzePath($path);
        } catch (Exception $e) {
            return;
        }

        if ($fromVersion === null) {
            $fromVersion = array(
                'version' => self::VERSION_NULL,
                'stage'   => self::STAGE_STABLE,
                'level'   => 0,
                );
        } else {
            preg_match(self::PCRE_VERSION_PATTERN, $fromVersion, $matches);
            $fromVersion = array(
                'version' => isset($matches['version']) ? $matches['version'] : self::VERSION_NULL,
                'stage'   => isset($matches['stage']) && !empty($matches['stage']) ? $matches['stage'] : self::STAGE_STABLE,
                'level'   => isset($matches['level']) && !empty($matches['level']) ? (int) $matches['level'] : 0,
                );
        }

        if ($toVersion === null) {
            $toVersion = array(
                'version' => self::VERSION_NULL,
                'stage'   => self::STAGE_STABLE,
                'level'   => 0,
                );
        } else {
            preg_match(self::PCRE_VERSION_PATTERN, $toVersion, $matches);
            $toVersion = array(
                'version' => isset($matches['version']) ? $matches['version'] : self::VERSION_NULL,
                'stage'   => isset($matches['stage']) && !empty($matches['stage']) ? $matches['stage'] : self::STAGE_STABLE,
                'level'   => isset($matches['level']) && !empty($matches['level']) ? (int) $matches['level'] : 0,
                );
        }

        switch (version_compare($toVersion['version'], $fromVersion['version'])) {
        case 1:
            $this->_upgradeToVersion($fromVersion, $toVersion);
            break;

        case -1:
            $this->_downgradeToVersion($fromVersion, $toVersion);
            break;

        case 0:
            if ($toVersion['stage'] === self::STAGE_STABLE) {
                if ($fromVersion['stage'] !== self::STAGE_STABLE) {
                    $this->_upgradeToVersion($fromVersion, $toVersion);
                } else {
                    $this->_downgradeToVersion($fromVersion, $toVersion);
                }
            } else if ($toVersion['stage'] === self::STAGE_RC) {
                if ($fromVersion['stage'] === self::STAGE_STABLE) {
                    $this->_downgradeToVersion($fromVersion, $toVersion);
                } else if ($fromVersion['stage'] === self::STAGE_RC) {
                    if ($fromVersion['level'] < $toVersion['level']) {
                        $this->_upgradeToVersion($fromVersion, $toVersion);
                    } else {
//                        throw new Wootook_Core_Exception_RuntimeException("Version already installed.");
                    }
                } else {
                    $this->_upgradeToVersion($fromVersion, $toVersion);
                }
            } else if ($toVersion['stage'] === self::STAGE_BETA) {
                if (in_array($fromVersion['stage'], array(self::STAGE_STABLE, self::STAGE_RC))) {
                    $this->_downgradeToVersion($fromVersion, $toVersion);
                } else if ($fromVersion['stage'] === self::STAGE_BETA) {
                    if ($fromVersion['level'] < $toVersion['level']) {
                        $this->_upgradeToVersion($fromVersion, $toVersion);
                    } else {
//                        throw new Wootook_Core_Exception_RuntimeException("Version already installed.");
                    }
                } else {
                    $this->_upgradeToVersion($fromVersion, $toVersion);
                }
            } else if ($toVersion['stage'] === self::STAGE_ALPHA) {
                if (in_array($fromVersion['stage'], array(self::STAGE_STABLE, self::STAGE_RC, self::STAGE_BETA))) {
                    $this->_downgradeToVersion($fromVersion, $toVersion);
                } else if ($fromVersion['stage'] === self::STAGE_ALPHA) {
                    if ($fromVersion['level'] < $toVersion['level']) {
                        $this->_upgradeToVersion($fromVersion, $toVersion);
                    } else {
//                        throw new Wootook_Core_Exception_RuntimeException("Version already installed.");
                    }
                } else {
                    $this->_upgradeToVersion($fromVersion, $toVersion);
                }
            } else {
                throw new Wootook_Core_Setup_Exception_VersionStageError("Invalid version stage.");
            }
            break;
        }

        return $this;
    }

    public function getFinalVersion()
    {
        return $this->_finalVersion;
    }

    public function getCurrentVersion()
    {
        $current = $this->current();

        return $current['version'];
    }

    protected function _installHighestInstaller($currentVersion, $toVersion)
    {
        $highestVersion = self::VERSION_NULL;
        foreach (array_keys($this->_installVersions) as $version) {
            if (version_compare($version, $highestVersion, '>') && version_compare($version, $toVersion['version'], '<=')) {
                $highestVersion = $version;
            }
        }

        if (isset($this->_installVersions[$highestVersion][self::STAGE_STABLE][0])) {
            $version = array(
                'version' => $highestVersion,
                'stage'   => self::STAGE_STABLE,
                'level'   => 0,
                );
            $this->enqueue(array($version, $this->_installVersions[$highestVersion][self::STAGE_STABLE][0]));

            return $version;
        }

        if (isset($this->_installVersions[$highestVersion][self::STAGE_STABLE])) {
            $stage = self::STAGE_STABLE;
        } else if (isset($this->_installVersions[$highestVersion][self::STAGE_RC])) {
            $stage = self::STAGE_RC;
        } else if (isset($this->_installVersions[$highestVersion][self::STAGE_BETA])) {
            $stage = self::STAGE_BETA;
        } else if (isset($this->_installVersions[$highestVersion][self::STAGE_ALPHA])) {
            $stage = self::STAGE_ALPHA;
        } else {
            throw new Wootook_Core_Setup_Exception_VersionStageError("Invalid version stage.");
        }

        $levelList = array_keys($this->_installVersions[$highestVersion][$stage]);

        $highestLevel = 0;
        foreach ($levelList as $level) {
            if ($level > $highestLevel) {
                $highestLevel = $level;
            }
        }

        $version = array(
            'version' => $highestVersion,
            'stage'   => $stage,
            'level'   => $highestLevel,
            );

        $this->enqueue(array($version, $this->_installVersions[$highestVersion][$stage][$highestLevel]));

        return $version;
    }

    protected function _downgradeToVersion($fromVersion, $toVersion)
    {
        throw new Wootook_Core_Exception_RuntimeException("Downgrade isn't yet implemented.");
    }

    protected function _upgradeToVersion($fromVersion, $toVersion)
    {
        $currentVersion = $fromVersion;

        if ($fromVersion['version'] === self::VERSION_NULL) {
            // run the higher install script
            $currentVersion = $this->_installHighestInstaller($currentVersion, $toVersion);
        }

        $upgradeVersions = $this->_upgradeVersions;
        while (true) {

            if ($currentVersion['version'] === $toVersion['version'] &&
                $currentVersion['stage'] === $toVersion['stage'] &&
                $currentVersion['level'] === $toVersion['level']) {
                break;
            }

            $versionPointer = &$upgradeVersions[$currentVersion['version']][$currentVersion['stage']][$currentVersion['level']];
            if (empty($versionPointer)) {
                break;
            }

            $highestVersion = $currentVersion['version'];
            foreach ($versionPointer as $version => $stages) {
                if (version_compare($version, $highestVersion['version'], '>') && version_compare($version, $toVersion['version'], '<=')) {
                    $highestVersion = $version;
                }
            }

            if (version_compare($highestVersion, $toVersion['version'], '<')) {
                $stage = self::STAGE_STABLE;
                $level = 0;
            } else if (version_compare($highestVersion, $toVersion['version']) === 0) {
                if (isset($versionPointer[$highestVersion][self::STAGE_STABLE]) && $toVersion['stage'] === self::STAGE_STABLE) {
                    $stage = self::STAGE_STABLE;
                } else if (isset($versionPointer[$highestVersion][self::STAGE_RC]) &&
                    in_array($toVersion['stage'], array(self::STAGE_RC, self::STAGE_STABLE))) {

                    $stage = self::STAGE_RC;
                }  else if (isset($versionPointer[$highestVersion][self::STAGE_BETA]) &&
                    in_array($toVersion['stage'], array(self::STAGE_RC, self::STAGE_STABLE, self::STAGE_BETA))) {

                    $stage = self::STAGE_BETA;
                }  else if (isset($versionPointer[$highestVersion][self::STAGE_ALPHA]) &&
                    in_array($toVersion['stage'], array(self::STAGE_RC, self::STAGE_STABLE, self::STAGE_BETA, self::STAGE_ALPHA))) {

                    $stage = self::STAGE_ALPHA;
                } else {
                    break;
                }

                $level = max(array_keys($versionPointer[$highestVersion][$stage]));
            } else {
                throw new Wootook_Core_Setup_Exception_VersionValueError("Invalid version value.");
            }

            $currentVersion = array(
                'version' => $highestVersion,
                'stage'   => $stage,
                'level'   => $level
                );
            $this->enqueue(array($currentVersion, $versionPointer[$highestVersion][$stage][$level]));
        }

        $this->_finalVersion = $currentVersion;
    }

    protected function _analyzePath($path)
    {
        $iterator = new DirectoryIterator($path);

        foreach ($iterator as $file) {
            if ($file->isDir() || $file->isDot()) {
                continue;
            }

            if (!preg_match(self::PCRE_FILE_PATTERN, $file->getBasename('.php'), $matches)) {
                continue;
            }

            switch ($matches['action']) {
            case self::MODE_INSTALL:
                $this->_addInstallFile(
                    $file->getPathname(),
                    $matches['version1'],
                    (isset($matches['stage1']) && !empty($matches['stage1']) ? $matches['stage1'] : self::STAGE_STABLE),
                    (isset($matches['level1']) && !empty($matches['level1']) ? (int) $matches['level1'] : 0)
                    );
                break;

            case self::MODE_UNINSTALL:
                $this->_addUninstallFile(
                    $file->getPathname(),
                    $matches['version1'],
                    (isset($matches['stage1']) && !empty($matches['stage1']) ? $matches['stage1'] : self::STAGE_STABLE),
                    (isset($matches['level1']) && !empty($matches['level1']) ? (int) $matches['level1'] : 0)
                    );
                break;

            case self::MODE_UPGRADE:
                $this->_addUpgradeFile(
                    $file->getPathname(),
                    $matches['version1'],
                    $matches['version2'],
                    (isset($matches['stage1']) && !empty($matches['stage1']) ? $matches['stage1'] : self::STAGE_STABLE),
                    (isset($matches['stage2']) && !empty($matches['stage2']) ? $matches['stage2'] : self::STAGE_STABLE),
                    (isset($matches['level1']) && !empty($matches['level1']) ? (int) $matches['level1'] : 0),
                    (isset($matches['level2']) && !empty($matches['level2']) ? (int) $matches['level2'] : 0)
                    );
                break;

            case self::MODE_DOWNGRADE:
                $this->_addDowngradeFile(
                    $file->getPathname(),
                    $matches['version1'],
                    $matches['version2'],
                    (isset($matches['stage1']) && !empty($matches['stage1']) ? $matches['stage1'] : self::STAGE_STABLE),
                    (isset($matches['stage2']) && !empty($matches['stage2']) ? $matches['stage2'] : self::STAGE_STABLE),
                    (isset($matches['level1']) && !empty($matches['level1']) ? (int) $matches['level1'] : 0),
                    (isset($matches['level2']) && !empty($matches['level2']) ? (int) $matches['level2'] : 0)
                    );
                break;
            }
        }
    }

    private function _addInstallFile($file, $version, $stage = self::STAGE_STABLE, $level = null)
    {
        if (!isset($this->_installVersions[$version])) {
            $this->_installVersions[$version] = array();
        }
        if (!isset($this->_installVersions[$version][$stage])) {
            $this->_installVersions[$version][$stage] = array();
        }

        $this->_installVersions[$version][$stage][$level] = $file;

        return $this;
    }

    private function _addUpgradeFile($file, $upperVersion, $lowerVersion, $upperStage = self::STAGE_STABLE, $lowerStage = self::STAGE_STABLE, $upperLevel = null, $lowerLevel = null)
    {
        if (!isset($this->_upgradeVersions[$upperVersion])) {
            $this->_upgradeVersions[$upperVersion] = array();
        }
        if (!isset($this->_upgradeVersions[$upperVersion][$upperStage])) {
            $this->_upgradeVersions[$upperVersion][$upperStage] = array();
        }
        if (!isset($this->_upgradeVersions[$upperVersion][$upperStage][$upperLevel])) {
            $this->_upgradeVersions[$upperVersion][$upperStage][$upperLevel] = array();
        }
        if (!isset($this->_upgradeVersions[$upperVersion][$upperStage][$upperLevel][$lowerVersion])) {
            $this->_upgradeVersions[$upperVersion][$upperStage][$upperLevel][$lowerVersion] = array();
        }
        if (!isset($this->_upgradeVersions[$upperVersion][$upperStage][$upperLevel][$lowerVersion][$lowerStage])) {
            $this->_upgradeVersions[$upperVersion][$upperStage][$upperLevel][$lowerVersion][$lowerStage] = array();
        }

        $this->_upgradeVersions[$upperVersion][$upperStage][$upperLevel][$lowerVersion][$lowerStage][$lowerLevel] = $file;

        return $this;
    }

    private function _addUninstallFile($file, $version, $stage = self::STAGE_STABLE, $level = null)
    {
        if (!isset($this->_uninstallVersions[$version])) {
            $this->_uninstallVersions[$version] = array();
        }
        if (!isset($this->_uninstallVersions[$version][$stage])) {
            $this->_uninstallVersions[$version][$stage] = array();
        }

        $this->_uninstallVersions[$version][$stage][$level] = $file;

        return $this;
    }

    private function _addDowngradeFile($file, $upperVersion, $lowerVersion, $upperStage = self::STAGE_STABLE, $lowerStage = self::STAGE_STABLE, $upperLevel = null, $lowerLevel = null)
    {
        if (!isset($this->_downgradeVersions[$upperVersion])) {
            $this->_downgradeVersions[$upperVersion] = array();
        }
        if (!isset($this->_downgradeVersions[$upperVersion][$upperStage])) {
            $this->_downgradeVersions[$upperVersion][$upperStage] = array();
        }
        if (!isset($this->_downgradeVersions[$upperVersion][$upperStage][$upperLevel])) {
            $this->_downgradeVersions[$upperVersion][$upperStage][$upperLevel] = array();
        }
        if (!isset($this->_downgradeVersions[$upperVersion][$upperStage][$upperLevel][$lowerVersion])) {
            $this->_downgradeVersions[$upperVersion][$upperStage][$upperLevel][$lowerVersion] = array();
        }
        if (!isset($this->_downgradeVersions[$upperVersion][$upperStage][$upperLevel][$lowerVersion][$lowerStage])) {
            $this->_downgradeVersions[$upperVersion][$upperStage][$upperLevel][$lowerVersion][$lowerStage] = array();
        }

        $this->_downgradeVersions[$upperVersion][$upperStage][$upperLevel][$lowerVersion][$lowerStage][$lowerLevel] = $file;

        return $this;
    }

    public function count()
    {
        return count($this->_scripts);
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_scripts);
    }

    public function offsetSet($offset, $value)
    {
        $this->_scripts[(int) $offset] = $value;
    }

    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->_scripts[(int) $offset];
        }
        return null;
    }

    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->_scripts[(int) $offset]);
        }
    }

    public function current()
    {
        return current($this->_scripts);
    }

    public function key()
    {
        return key($this->_scripts);
    }

    public function next()
    {
        next($this->_scripts);
    }

    public function rewind()
    {
        reset($this->_scripts);
    }

    public function valid()
    {
        return (bool) (key($this->_scripts) !== null);
    }

    public function enqueue($data)
    {
        $this->_scripts[] = array(
            'script'  => $data[1],
            'version' => $data[0]
            );
    }
}
