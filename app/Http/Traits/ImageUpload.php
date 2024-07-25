<?php

namespace App\Http\Traits;


use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

trait ImageUpload
{
    public function ImageUpload($requestFile, $directory, $width, $height)
    {
        try {
            $manager = new ImageManager(new Driver());
            $filename = hexdec(uniqid()).'.'.$requestFile->getClientOriginalExtension();
            $image = $manager->read($requestFile);
            Storage::disk('public')->makeDirectory($directory);
            if (!empty($width) && !empty($height)) {
                $image->resize($width, $height)->save(storage_path() . '/app/public/' . $directory . '/' . $filename);
            }else{
                $image->save(storage_path() . '/app/public/' . $directory . '/' . $filename);
            }

            return $filename;
        } catch (\Exception $e) {
            return $this->sendError('Image not successfully upload!', '');
        }
    }
}
