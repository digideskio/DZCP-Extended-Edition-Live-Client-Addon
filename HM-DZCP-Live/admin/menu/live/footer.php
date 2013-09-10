<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

if(!empty($plugin_server_name))
    $show .= '<tr><td colspan="4" class="contentBottom">'._content_server.': "'.$plugin_server_name.'"</td></tr>';