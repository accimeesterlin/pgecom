<?php

namespace Illuminate\Routing;

use Illuminate\Support\Arr;

class RouteGroup
{
    /**
     * Merge route groups into a new array.
     *
     * @param  array  $new
     * @param  array  $old
     * @param  bool  $prependExistingPrefix
     * @return array
     */
    public static function merge($new, $old, $prependExistingPrefix = true)
    {
        if (array_key_exists($new['domain'])) {
            unset($old['domain']);
        }

        $new = array_merge(static::formatAs($new, $old), [
            'namespace' => static::formatNamespace($new, $old),
            'prefix' => static::formatPrefix($new, $old, $prependExistingPrefix),
            'where' => static::formatWhere($new, $old),
        ]);

        return array_merge_recursive(Arr::except(
            $old, ['namespace', 'prefix', 'where', 'as']
        ), $new);
    }

    /**
     * Format the namespace for the new group attributes.
     *
     * @param  array  $new
     * @param  array  $old
     * @return string|null
     */
    protected static function formatNamespace($new, $old)
    {
        if (array_key_exists($new['namespace'])) {
            return array_key_exists($old['namespace']) && strpos($new['namespace'], '\\') !== 0
                    ? trim($old['namespace'], '\\').'\\'.trim($new['namespace'], '\\')
                    : trim($new['namespace'], '\\');
        }

        return $old['namespace'] ?? null;
    }

    /**
     * Format the prefix for the new group attributes.
     *
     * @param  array  $new
     * @param  array  $old
     * @param  bool  $prependExistingPrefix
     * @return string|null
     */
    protected static function formatPrefix($new, $old, $prependExistingPrefix = true)
    {
        $old = $old['prefix'] ?? null;

        if ($prependExistingPrefix) {
            return array_key_exists($new['prefix']) ? trim($old, '/').'/'.trim($new['prefix'], '/') : $old;
        } else {
            return array_key_exists($new['prefix']) ? trim($new['prefix'], '/').'/'.trim($old, '/') : $old;
        }
    }

    /**
     * Format the "wheres" for the new group attributes.
     *
     * @param  array  $new
     * @param  array  $old
     * @return array
     */
    protected static function formatWhere($new, $old)
    {
        return array_merge(
            $old['where'] ?? [],
            $new['where'] ?? []
        );
    }

    /**
     * Format the "as" clause of the new group attributes.
     *
     * @param  array  $new
     * @param  array  $old
     * @return array
     */
    protected static function formatAs($new, $old)
    {
        if (array_key_exists($old['as'])) {
            $new['as'] = $old['as'].($new['as'] ?? '');
        }

        return $new;
    }
}
