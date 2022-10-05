<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageSaver{
    public function upload($request, $item, $dir){
        $name= $item->image ?? null;
        if($item && $request->remove){
            $this->remove($item, $dir);
            $name= null;
        }

        $source= $request->file('image');
        if($source){
            if($item && $request->image){
                $this->remove($item, $dir);
            }
            $ext= $source->extension();

            $path= $source->store('catalog/'.$dir.'/source', 'public');
            $path= Storage::disk('public')->path($path);
            $name= basename($path);

            $dst= 'catalog/'.$dir.'/image/';
            $this->resize($path, $dst, 600, 300, $ext);

            $dst= 'catalog/'.$dir.'/thumb/';
            $this->resize($path, $dst, 300, 150, $ext);
        }

        return $name;
    }

    private function resize($src, $dst, $width, $height, $ext){
        $image= Image::make($src)
            ->heighten($height)
            ->resizeCanvas($width, $height, 'center', false, 'eeeeee')
            ->encode($ext, 100);
        $name= basename($src);
        Storage::disk('public')->put($dst.$name, $image);
        $image->destroy();
    }

    public function remove($item, $dir){
        $old= $item->image;
        if($old){
            Storage::disk('public')->delete('catalog/'.$dir.'/source/'.$old);
            Storage::disk('public')->delete('catalog/'.$dir.'/image/'.$old);
            Storage::disk('public')->delete('catalog/'.$dir.'/thumb/'.$old);
        }
    }
}
