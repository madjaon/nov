<?php 
namespace App\Helpers;

class CommonDrive
{
    static function uploadFileToGDrive($imagename, $foldername)
    {
        // check & get full image url
        $fileurl = CommonMethod::getFullImageLink($imagename, CommonMethod::getDomainSource());
        if($fileurl == '') {
            return '';
        }

        $filename = basename(CommonMethod::removeParameters($fileurl));

        $dir = '/';
        $recursive = false; // Get subdirectories also?
        $contents = collect(\Storage::cloud()->listContents($dir, $recursive));
        $dir = $contents->where('type', 'dir')
                    ->where('filename', $foldername)
                    ->first(); // There could be duplicate directory names!
        if (!$dir) {
            // create dir
            $makeDir = \Storage::cloud()->makeDirectory($foldername);
            if($makeDir) {
                $contents = collect(\Storage::cloud()->listContents($dir, $recursive));
                $dir = $contents->where('type', 'dir')
                    ->where('filename', $foldername)
                    ->first(); // There could be duplicate directory names!
            }
        }
        
        // put in dir
        \Storage::cloud()->put($dir['path'].'/'.$filename, file_get_contents($fileurl));

        // get file upload
        $dir = '/';
        $recursive = true; // Get subdirectories also?
        $file = collect(\Storage::cloud()->listContents($dir, $recursive))
                    ->where('type', 'file')
                    ->where('filename', pathinfo($filename, PATHINFO_FILENAME))
                    ->where('extension', pathinfo($filename, PATHINFO_EXTENSION))
                    ->sortBy('timestamp')
                    ->last();
        if($file) {
            return $file['basename'];
        }

        return null;
    }

}