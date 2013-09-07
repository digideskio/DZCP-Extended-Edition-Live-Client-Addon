<?php
/**
 * <DZCP-Extended Edition>
 * @package: DZCP-Extended Edition
 * @author: DZCP Developer Team || Hammermaps.de Developer Team
 * @link: http://www.dzcp.de || http://www.hammermaps.de
 */

if(_adminMenu != 'true') exit();

//Get News from Live Server & Cache
#if(!($news_array = Cache::check('live_news') ? Cache::get('live_news') : false))
#{
    $news_array = client_api_communicate::send(array('call' => 'news'));
#    Cache::set('live_news',$news_array,60);
#}

$news_tb = '';
if($news_array != false && count($news_array) >= 1)
{
    foreach ($news_array as $news)
    {
        $news_tb .= show($dir."/news/live_news_show",array('titel' => cut($news['title'],95,true), 'newsimage' => $news['newsimage'], 'date' => $news['date'], 'text' => $news['text']));
    }
}
$show .= show($dir."/news/live_news", array('show' => $news_tb));