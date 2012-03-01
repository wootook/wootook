<?php

class Wootook_Core_Database_Sql_Mysql_Select
    extends Wootook_Core_Database_Sql_Select
{
    public function renderColumns()
    {
        $fields = array();
        foreach ($this->_parts[self::COLUMNS] as $field) {
            if ($field['field'] instanceof Wootook_Core_Database_Sql_Placeholder_Placeholder) {
                $field['field']->process($this);

                if ($field['alias'] !== null) {
                    $fields[] = "{$field['field']} AS {$field['alias']}";
                } else {
                    $fields[] = "{$field['field']}";
                }
            } else if ($field['alias'] !== null) {
                if ($field['table'] !== null) {
                    $fields[] = "{$field['table']}.{$field['field']} AS {$field['alias']}";
                } else {
                    $fields[] = "{$field['field']} AS {$field['alias']}";
                }
            } else if ($field['table'] !== null) {
                $fields[] = "{$field['table']}.{$field['field']}";
            } else {
                $fields[] = "{$field['field']}";
            }
        }

        if (!empty($fields)) {
            return implode(", ", $fields);
        }
        return '*';
    }

    public function render()
    {
        if (empty($this->_parts[self::UNION])) {
            $fields = $this->renderColumns();

            if (!empty($this->_parts[self::WHERE])) {
                $where = 'WHERE (' . implode(") AND (", $this->_parts[self::WHERE]) . ')';
            } else {
                $where = '';
            }
            $joinedTables = implode("\n ", $this->_parts[self::JOIN]);

            $order = '';
            if (!empty($this->_parts[self::ORDER])) {
                $order = 'ORDER BY ' . implode(", ", $this->_parts[self::ORDER]);
            }
            $limit = '';
            if ($this->_parts[self::LIMIT]) {
                if ($this->_parts[self::OFFSET]) {
                    $limit = "LIMIT {$this->_parts[self::LIMIT]}, {$this->_parts[self::OFFSET]}";
                } else {
                    $limit = "LIMIT {$this->_parts[self::LIMIT]}";
                }
            }
            $group = '';
            if (!empty($this->_parts[self::GROUP])) {
                foreach ($this->_parts[self::GROUP] as &$groupedField) {
                    $groupedField = $this->_connection->quoteIdentifier($groupedField);
                }
                unset($groupedField);
                $group = 'GROUP BY ' . implode(', ', $this->_parts[self::GROUP]);
            }

            $tableList = array();
            foreach ($this->_parts[self::FROM] as $table) {
                if ($table['schema'] !== null) {
                    $tableString = "{$this->_connection->quoteIdentifier($table['schema'])}.{$this->_connection->quoteIdentifier($this->_connection->getTable($table['table']))}";
                } else {
                    $tableString = $this->_connection->quoteIdentifier($this->_connection->getTable($table['table']));
                }
                if ($table['alias'] !== null) {
                    $tableString .= " AS {$this->_connection->quoteIdentifier($table['alias'])}";
                }

                $tableList[] = $tableString;
            }
            $tableList = implode(', ', $tableList);

            return <<<SQL_EOF
SELECT {$fields}
    FROM {$tableList}
    {$joinedTables}
    {$where}
    {$group}
    {$order}
    {$limit}
SQL_EOF;
        } else {
            $statements = array();
            foreach ($this->_parts[self::UNION] as $statement) {
                $statements[] = $statement->_prepareSql();
            }

            $statements = '(' . implode(') UNION (', $statements) . ')';
            if (!empty($where)) {
                $where = 'WHERE (' . implode(") AND (", $this->_parts[self::WHERE]) . ')';
            } else {
                $where = '';
            }

            $limit = '';
            if ($this->_parts[self::LIMIT]) {
                if ($this->_parts[self::OFFSET]) {
                    $limit = "LIMIT {$this->_parts[self::LIMIT]}, {$this->_parts[self::OFFSET]}";
                } else {
                    $limit = "LIMIT {$this->_parts[self::LIMIT]}";
                }
            }

            return <<<SQL_EOF
$statements
    {$where}
    {$limit}
SQL_EOF;
        }
    }
}