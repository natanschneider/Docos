<?php

namespace App\Services;

use PhpMyAdmin\SqlParser\Parser;

class ParseSqlService
{
    public function extractTables(string $sql): array
    {
        $parser = new Parser($sql);
        $tree = $parser->parse();

        $result = [];
        $nodes = $tree;
        if (is_array($tree) && isset($tree['statements']) && is_array($tree['statements'])) {
            $nodes = $tree['statements'];
        }

        foreach ((array) $nodes as $stmt) {
            $exprType = strtolower((string)($stmt['expr_type'] ?? ''));

            if (!($exprType === 'create' && strtolower((string)($stmt['create_type'] ?? '')) === 'table')) {
                continue;
            }

            $tableName =
                $stmt['name']['table'] ??
                ($stmt['name'] ?? null);

            $columns = [];
            $primaryKey = [];
            $indexes = [];
            $foreignKeys = [];

            $defs = $stmt['create_definition'] ?? ($stmt['definition'] ?? []);
            foreach ((array) $defs as $def) {
                $defType = strtolower((string)($def['expr_type'] ?? ''));

                if ($defType === 'column' || isset($def['column_name'])) {
                    $columns[] = [
                        'name' => $def['column_name'] ?? null,
                        'type' => $this->normalizeType($def['data_type'] ?? ($def['type'] ?? null)),
                        'nullable' => $this->inferNullable($def),
                        'default' => $def['default_value'] ?? ($def['default'] ?? null),
                        'auto_increment' => (bool)($def['auto_increment'] ?? false),
                    ];
                    continue;
                }

                if ($defType === 'constraint_primary' && isset($def['keys'])) {
                    $primaryKey = array_map(fn($k) => $k['column'] ?? $k, $def['keys']);
                    continue;
                }

                if (in_array($defType, ['key_constraint', 'index'], true) && isset($def['keys'])) {
                    $indexes[] = [
                        'name' => $def['name'] ?? null,
                        'columns' => array_map(fn($k) => $k['column'] ?? $k, $def['keys']),
                        'unique' => (bool)($def['unique'] ?? false),
                    ];
                    continue;
                }

                if ($defType === 'constraint_foreign' && isset($def['keys'])) {
                    $foreignKeys[] = [
                        'name' => $def['name'] ?? null,
                        'columns' => array_map(fn($k) => $k['column'] ?? $k, $def['keys']),
                        'references' => $def['references'] ?? null,
                    ];
                    continue;
                }
            }

            $result[] = [
                'table' => is_string($tableName) ? $tableName : null,
                'columns' => $columns,
                'primaryKey' => $primaryKey,
                'indexes' => $indexes,
                'foreignKeys' => $foreignKeys,
            ];
        }

        return $result;
    }

    private function normalizeType($type): ?string
    {
        if ($type === null) return null;
        if (is_string($type)) return $type;
        return trim(implode(' ', array_map('strval', (array) $type)));
    }

    private function inferNullable(array $def): bool
    {
        $blob = strtolower(implode(' ', array_map('strval', $def)));

        if (str_contains($blob, 'not null')) return false;
        if (str_contains($blob, 'null')) return true;

        return false;
    }
}
