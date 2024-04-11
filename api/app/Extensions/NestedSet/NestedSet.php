<?php

namespace App\Extensions\NestedSet;

use Illuminate\Database\Schema\Blueprint;

class NestedSet
{
    /**
     * The name of default parent id column.
     */
    const PARENT_ID = 'parent_id';

    /**
     * The name of default lft column.
     */
    const LFT = 'lft';

    /**
     * The name of default rgt column.
     */
    const RGT = 'rgt';

    const DEPTH = 'depth';

    /**
     * Insert direction.
     */
    const BEFORE = 1;

    /**
     * Insert direction.
     */
    const AFTER = 2;

    /**
     * Add default nested set columns to the table. Also create an index.
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     */
    public static function columns(Blueprint $table)
    {
        $table->unsignedBigInteger(self::PARENT_ID)->nullable();
        $table->unsignedBigInteger(self::LFT)->default(self::BEFORE);
        $table->unsignedBigInteger(self::RGT)->default(self::AFTER);
        $table->unsignedBigInteger(self::DEPTH)->default(0);

        $table->index(static::getDefaultColumns());
    }

    /**
     * Drop NestedSet columns.
     *
     * @param \Illuminate\Database\Schema\Blueprint $table
     */
    public static function dropColumns(Blueprint $table)
    {
        $columns = static::getDefaultColumns();

        $table->dropIndex($columns);
        $table->dropColumn($columns);
    }

    /**
     * Get a list of default columns.
     *
     * @return array
     */
    public static function getDefaultColumns()
    {
        return [static::PARENT_ID, static::LFT, static::RGT, static::DEPTH];
    }

    /**
     * Replaces instanceof calls for this trait.
     *
     * @param mixed $node
     *
     * @return bool
     */
    public static function isNode($node)
    {
        return is_object($node) && in_array(NodeTrait::class, (array)$node);
    }

}
