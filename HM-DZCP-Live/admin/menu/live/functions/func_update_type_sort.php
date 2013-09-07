<?php
/**
 * Sortiert alle Updates nach der Wichtigkeit aufsteigend
 *
 * @param array $updates
 * @return array
 */
function update_type_sort($updates=array())
{
    if(!is_array($updates) || count($updates) <= 2) return $updates;
    $updates_sort_update = array(); $updates_sort_hotfix = array();
    $updates_sort_bugfix = array(); $updates_sort_security = array();
    $updates_sort_apiupdate = array(); $updates_sort_enhancement = array();

    foreach($updates as $update)
    {
        switch($update['type'])
        {
            case '1': $updates_sort_hotfix[] = $update; break;
            case '2': $updates_sort_bugfix[] = $update; break;
            case '3': $updates_sort_security[] = $update; break;
            case '4': $updates_sort_enhancement[] = $update; break;
            case '5': $updates_sort_apiupdate[] = $update; break;
            default: $updates_sort_update[] = $update; break;
        }
    }

    return array_merge($updates_sort_hotfix,$updates_sort_bugfix,$updates_sort_security,$updates_sort_update,$updates_sort_apiupdate,$updates_sort_enhancement);
}