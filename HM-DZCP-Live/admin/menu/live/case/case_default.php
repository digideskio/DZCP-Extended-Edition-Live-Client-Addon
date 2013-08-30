<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

print_r(client_api_communicate::send(array('call' => 'news')));
die();

$show .= show($dir."/form_live_news", array('news' => ''));