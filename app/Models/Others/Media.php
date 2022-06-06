<?php

namespace ClassyPOS\Models\Others;

use Carbon\Carbon;
use Hyn\Tenancy\Website\Directory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Mediable\Media as PMedia;
use Plank\Mediable\MediaUploaderFacade as MediaUploader;

class Media extends PMedia
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function upload($file)
    {
        $date = Carbon::now()->format('Y-m-d');

        $media = MediaUploader::fromSource($file)
            ->toDestination('tenancy', $date)
            ->upload();
        $media_path = $media->getDiskPath();

        return $media_path;
    }
}
