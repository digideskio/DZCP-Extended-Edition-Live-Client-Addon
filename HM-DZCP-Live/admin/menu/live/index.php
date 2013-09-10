<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

bbcode::use_glossar(false);
$plugin_server_online = false; $plugin_server_maintenance = false; $plugin_server_name = '';
if(class_exists('client_api_communicate'))
{
    //MySQL Tabellen prüfen und anlegen

    #client_api_communicate::download('127.0.0.1/DZCPEE/test1234.zip','inc/additional-addons/HM-DZCP-Live/download/test.zip');

    client_api_communicate::set_api_url('127.0.0.1',80);

    if($handshake = client_api_communicate::send(array('call' => 'handshake', 'cryptkey_use' => false)))
    {
        $plugin_server_maintenance = $handshake['maintenance'];
        $plugin_server_online = $handshake['online'];
        $plugin_server_name = $handshake['name'];
    }
    unset($handshake);

    #client_api_communicate::set_api_cryptkey('test1234');

    if(!$plugin_server_online)
        $show = show($dir."/errors/net_offline");
    else if($plugin_server_online && $plugin_server_maintenance)
        $show = show($dir."/errors/net_maintenance");
    else if($plugin_server_online && !$plugin_server_maintenance)
        $show = show($dir."/form_live");
}
else
    $show = show($dir."/errors/class_api_missing");