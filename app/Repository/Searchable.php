<?php
/**
 * Date: 2019/2/27 Time: 10:48
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Repository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait Searchable
{
    public static function buildQuery(Builder $query, array $condition)
    {
        foreach ($condition as $k => $v) {
            $type = 'like';
            $value = $v;
            if (is_array($v)) {
                list($type, $value) = $v;
            }
            $value = trim($value);
            if ($value === '') {
                continue;
            }

            if ($k === 'created_at' || $k === 'updated_at') {
                $dates = explode(' ~ ', $value);
                if (count($dates) === 2) {
                    $query->whereBetween($k, [
                        Carbon::parse($dates[0])->startOfDay(),
                        Carbon::parse($dates[1])->endOfDay(),
                    ]);
                }
            } else {
                $query->where($k, $type, $type === 'like' ? "%{$value}%" : $value);
            }
        }
    }
}
