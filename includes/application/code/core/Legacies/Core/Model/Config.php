<?php

/**
 *
 * Enter description here ...
 *
 * @uses Legacies_Object
 * @uses Legacies_Empire
 */
class Legacies_Core_Model_Config
    extends Legacies_Core_Model
    implements Legacies_Core_Singleton
{
    private static $_singleton = null;

    protected $_eventPrefix = 'core.config';
    protected $_eventObject = 'config';

    public static function getSingleton()
    {
        if (self::$_singleton === null) {
            self::$_singleton = new self();
        }
        return self::$_singleton;
    }

    protected function _init()
    {
        $this->load();
    }

    protected function _load()
    {
        $database = Legacies_Database::getSingleton();

        $sql =<<<SQL_EOF
SELECT config_name AS attribute, config_value AS value
  FROM {$database->getTable('config')} AS config
SQL_EOF;

        $attributeName = null;
        $attributeValue = null;

        $statement = $database->prepare($sql);
        $statement->execute();

        $statement->bindColumn('attribute', $attributeName, PDO::PARAM_STR);
        $statement->bindColumn('value', $attributeValue, PDO::PARAM_STR);

        while ($statement->fetch(PDO::FETCH_BOUND)) {
            $this->setData($attributeName, $attributeValue);
        }
        return $this;
    }

    protected function _save()
    {
        $database = Legacies_Database::getSingleton();
        $fields = array();

        $sql =<<<SQL_EOF
UPDATE {{table}}
  SET config_value=:value
  WHERE config_name=:name
SQL_EOF;
        $statement = $database->prepare($sql);

        foreach ($this->getAllDatas() as $attributeName => $attributeValue) {
            $statement->execute(array(
                'name'  => $attributeName,
                'value' => $attributeValue
                ));
        }

        return $this;
    }

    protected function _delete()
    {
        // NOP
        return $this;
    }

    public function isEnabled()
    {
        return (bool) $this->getData('game_disable');
    }
}