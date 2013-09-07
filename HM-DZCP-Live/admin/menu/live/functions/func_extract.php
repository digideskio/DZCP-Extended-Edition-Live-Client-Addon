<?php
/**
 * Einpacken der Updates und überschreiben der Originalen Dateien.
 *
 * @param array $update_data
 * @param string $extract_to
 * @return boolean
 */
function update_extract($update_data=array(),$extract_to='/')
{
    if(class_exists('PclZip') && array_key_exists('file', $update_data) && array_key_exists('hash', $update_data))
    {
        $dir = basePath.'/'.'inc/additional-addons/HM-DZCP-Live/_download_/'.$update_data['file'].'.zip';
        if(file_exists($dir))
        {
            if(md5_file($dir) != $update_data['file']) //Checksums
            {
                DebugConsole::insert_error(update_extract(), 'The checksums do not match with original file!');
                return false;
            }

            $archive = new PclZip($dir);
            if(!$archive->extract(PCLZIP_OPT_PATH, basePath.$extract_to))
            {
                DebugConsole::insert_error(update_extract(), 'The extract of the data has failed! <p> File: '.$dir);
                return false;
            }

            unlink($dir);
            if(file_exists($dir))
                DebugConsole::insert_error(update_extract(), 'The update file: "'.$dir.'" could not be deleted!');

            DebugConsole::insert_successful(update_extract(),'The extract of the data is finished!');
            return true;
        }
    }
}