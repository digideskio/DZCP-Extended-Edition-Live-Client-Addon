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
    if(class_exists('PclZip') && array_key_exists('temp_file', $update_data) && array_key_exists('hash', $update_data))
    {
        if(array_key_exists('extract_to', $update_data) && $extract_to != '/')
            $extract_to = $update_data['extract_to'];

        $file = basePath.'/inc/additional-addons/HM-DZCP-Live/_download_/'.$update_data['temp_file'].'.zip';
        if(file_exists($file))
        {
            if(md5_file($file) != $update_data['hash']) //Checksums
            {
                DebugConsole::insert_error(update_extract(), 'The checksums do not match with original file!');
                return false;
            }

            $archive = new PclZip($file);
            if(!($list = $archive->extract(PCLZIP_OPT_PATH, basePath.$extract_to, PCLZIP_OPT_EXTRACT_AS_STRING)))
                DebugConsole::insert_error(update_extract(), 'The extract of the data has failed! <p> File: '.$file);

            //Prüfung
            $ftp_upload = false; $ftp_open = false;
            foreach ($list as $data)
            {
                echo '<pre>';
                print_r($data);


                if($data['folder'] ? is_dir(basePath.'/'.$data['filename']) : is_file(basePath.'/'.$data['filename']))
                {
                    // crc32
                    if(!$data['folder'] && !empty($data['crc']) && file_exists(basePath.'/'.$data['filename']) && is_file(basePath.'/'.$data['filename']))
                    {
                        if(@crc32(file_get_contents(basePath.'/'.$data['filename'])) != $data['crc'])
                        {
                            $ftp_upload = true;
                            DebugConsole::insert_info('update_extract()', 'FTP Upload needed');
                        }
                    }
                }
                else
                    $ftp_upload = true;

                // File Upload per FTP
                if($ftp_upload)
                {
                    ftp::connect(); $ftp_open = true;
                    DebugConsole::insert_info('update_extract()', 'FTP Upload started');
                    if(!$data['folder'])
                    {
                        ftp::move(settings('ftp_path'));
                        ftp::put_stream($data['content'],$data['filename'],true);
                        if($data['folder'] ? is_dir(basePath.'/'.$data['filename']) : is_file(basePath.'/'.$data['filename']))
                        {
                            // crc32
                            if(!$data['folder'] && !empty($data['crc']) && file_exists(basePath.'/'.$data['filename']) && is_file(basePath.'/'.$data['filename']))
                            {
                                if(@crc32(file_get_contents(basePath.'/'.$data['filename'])) != $data['crc'])
                                {
                                    DebugConsole::insert_error('update_extract()', $data['filename'].' hat den crc32 check nicht bestanden!');
                                    DebugConsole::insert_info('update_extract()', crc32(file_get_contents(basePath.'/'.$data['filename'])) ." <=> ". $data['crc']);
                                    DebugConsole::insert_error('update_extract()', 'FTP Upload faild!');
                                    return false;
                                }
                            }
                        }
                    }
                }
            }

            if($ftp_open) ftp::close();

            unlink($file);
            if(file_exists($file))
                DebugConsole::insert_error('update_extract()', 'The update file: "'.$file.'" could not be deleted!');

            DebugConsole::insert_successful('update_extract()','The extract of the data is finished!');
            return true;
        }
    }

    return false;
}
