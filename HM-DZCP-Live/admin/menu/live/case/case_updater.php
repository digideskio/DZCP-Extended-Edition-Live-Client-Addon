<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

//Sende dem Server die aktuell laufende DZCP Version sowie die installierten Addons
$call = array('call' => 'updater', 'mode' => 'check_version', 'version' => convert::ToString(_version), 'build' => _build, 'edition' => _edition, 'dbv' => convert::ToString(settings('db_version')));
$call['addons'] = array_to_string(API_CORE::$addon_index_xml,true);

if($update_server = client_api_communicate::send($call))
{
    $core_updates = ''; $addon_updates = ''; unset($call);
    if($update_server['update_available'] && count($update_server['updates']) >= 1)
    {
        $updates_core = array(); $updates_addon = array();
        foreach($update_server['updates'] as $update)
        {
            $update = string_to_array($update,true);
            $update['core'] ? $updates_core[] = $update : $updates_addon[] = $update;
        }

        $updates_core = update_type_sort($updates_core); //Sort
        $updates_addon = update_type_sort($updates_addon); //Sort
        $updates = array_merge($updates_core,$updates_addon);
        unset($updates_core,$updates_addon);

        foreach($updates as $update)
        {
            switch($update['type'])
            {
                case 1: $update_type = 'Hotfix'; $box_css = 'bugfix_color_box'; break;
                case 2: $update_type = 'Bugfix'; $box_css = 'bugfix_color_box'; break;
                case 3: $update_type = 'Security'; $box_css = 'security_color_box'; break;
                case 4: $update_type = 'Enhancement'; $box_css = 'enhancement_color_box'; break;
                case 5: $update_type = 'API-Update'; $box_css = 'apiupdate_color_box'; break;
                default: $update_type = 'Update'; $box_css = 'update_color_box'; break;
            }

            $klapp = show(_klapptext_no_link, array("class_ext" => 'live', "id" => $update['id'], "moreicon" => 'expand'));
            $show_update = show($dir."/updater/update_case", array('id' => $update['id'],
                    'box_css' => $box_css,
                    'box_label' => $update_type,
                    'display' => 'none',
                    'klapp' => $klapp,
                    'version' => $update['version'],
                    'rev' => $update['rev'],
                    'date' => $update['date'],
                    'update_text' => bbcode::parse_html($update['text']),
                    'box_tile' => $update['tile']));

            $update['core'] ? $core_updates .= $show_update : $addon_updates .= $show_update;
            unset($show_update);
        }
    }

    unset($update_server);
    $show .= show($dir."/updater/live_updater", array('dzcp' => $core_updates, 'addons' => $addon_updates));
    unset($core_updates,$addon_updates);
}
else
    $show = error(show(_live_net_error,array('code' => 'net_no_response')));