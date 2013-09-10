<?php
/**
 * Einpacken der Updates und überschreiben der Originalen Dateien.
 *
 * @param array $update_data
 * @param string $extract_to
 * @return boolean
 */
function update_download($update_data=array())
{
    if(class_exists('client_api_communicate') && array_key_exists('download_url', $update_data) && array_key_exists('temp_file', $update_data))
    {
        $file = 'inc/additional-addons/HM-DZCP-Live/_download_/'.$update_data['temp_file'].'.zip';
        $downloader = client_api_communicate::download($update_data['download_url'],$file);
        return ($downloader && file_exists(basePath.'/'.$file));
    }
}