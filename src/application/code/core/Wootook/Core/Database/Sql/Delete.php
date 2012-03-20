<?php

class Wootook_Core_Database_Sql_Delete
    extends Wootook_Core_Database_Sql_DmlFilterableQuery
{
    const SET    = 'SET';
    const INTO   = 'INTO';

    protected function _init($tableName = null)
    {
        if ($tableName !== null) {
            $this->into($tableName);
        }

        return $this;
    }

    public function reset($part = null)
    {
        if ($part === null) {
            $this->_parts = array(
                self::INTO   => array(),
                self::SET    => array(),
                );
        } else if (isset($this->_parts[$part])) {
            $this->_parts[$part] = array();
        }

        return $this;
    }

    public function set($column, $value)
    {
        if ($value instanceof Wootook_Core_Database_Sql_Placeholder_Placeholder) {
            $this->_placeholders[] = $column;
        }

        $this->_parts[self::COLUMNS][] = array(
            'value' => $value,
            'field' => $column
            );

        return $this;
    }

    public function into($table, $schema = null)
    {
        $this->_parts[self::INTO] = array(
            'table'  => $table,
            'schema' => $schema,
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
        case self::COLUMNS:
            return $this->renderSet();
            break;
        case self::INTO:
            return $this->renderInto();
            break;
        }

        return null;
    }

    public function renderSet()
    {
        $fields = array();
        foreach ($this->_parts[self::SET] as $field) {
            if ($field['value'] instanceof Wootook_Core_Database_Sql_Placeholder_Placeholder) {
                $fields[] = "{$this->_connection->quoteIdentifier($field['field'])}={$field['value']->toString()}";
            } else {
                $fields[] = "{$this->_connection->quoteIdentifier($field['field'])}={$this->_connection->quote($field['value'])}";
            }
        }

        if (!empty($fields)) {
            return "\nSET " . implode(", ", $fields);
        }
    }

    public function renderInto()
    {
        if ($this->_parts[self::INTO]['schema'] !== null) {
           $output = "{$this->_connection->quoteIdentifier($this->_parts[self::INTO]['schema'])}.{$this->_connection->quoteIdentifier($this->_parts[self::INTO]['table'])}";
        } else {
           $output = "{$this->_connection->quoteIdentifier($this->_parts[self::INTO]['table'])}";
        }

        return "INSERT INTO " . $output;
    }

    public function render()
    {
        if (empty($this->_parts[self::SELECT])) {
            return implode('', array(
                $this->renderInto(),
                $this->renderColumns(),
                ));
        } else {
            return implode('', array(
                $this->renderInto(),
                $this->renderSelect(),
                ));
        }
    }
}
