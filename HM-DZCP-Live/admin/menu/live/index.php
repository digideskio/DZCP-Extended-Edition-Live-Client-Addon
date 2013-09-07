<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

//MySQL Tabellen prüfen und anlegen

#client_api_communicate::download('127.0.0.1/DZCPEE/test1234.zip','inc/additional-addons/HM-DZCP-Live/download/test.zip');


client_api_communicate::set_api_url('127.0.0.1',80);
#client_api_communicate::set_api_cryptkey('test1234');

$show = show($dir."/form_live", array());