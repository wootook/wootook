<?php

class Wootook_Core_Database_Sql_Delete
    extends Wootook_Core_Database_Sql_DmlFilterableQuery
{
    const FROM = 'FROM';

    protected function _init($tableName = null)
    {
        parent::_init($tableName);

        if ($tableName !== null) {
            $this->into($tableName);
        }

        return $this;
    }

    public function reset($part = null)
    {
        if ($part === null) {
            $this->_parts = array(
                self::FROM   => null,
                self::WHERE  => array(),
                self::OFFSET => null,
                self::LIMIT  => null,
                );
        } else if (isset($this->_parts[$part])) {
            $this->_parts[$part] = array();
        }

        return $this;
    }

    public function from($table, $schema = null)
    {
        $this->_parts[self::FROM] = array(
            'table'  => $table,
            'schema' => $schema
            );

        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function toString($part = null)
    {
        if ($part === null) {
            return $this->render();
        }

        switch ($part) {
        case self::FROM:
            return $this->renderFrom();
            break;
        case self::WHERE:
            return $this->renderWhere();
            break;
        case self::WHERE:
            return $this->renderWhere();
            break;
        case self::LIMIT:
            return $this->renderLimit();
            break;
        }

        return null;
    }

    public function renderFrom()
    {
        if ($this->_parts[self::FROM]['schema'] !== null) {
            return "DELETE FROM {$this->getConnection()->quoteIdentifier($this->_parts[self::FROM]['schema'])}.{$this->getConnection()->quoteIdentifier($this->_parts[self::FROM]['table'])}";
        }
        return "DELETE FROM {$this->getConnection()->quoteIdentifier($this->_parts[self::FROM]['table'])}";
    }

    public function render()
    {
        return implode("\n", array(
            $this->renderFrom(),
            $this->renderWhere(),
            $this->renderLimit(),
            ));
    }
}
