<?php

namespace App\Occasions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Course extends Model implements HasMedia
{
    use HasMediaTrait;

    const GPX_DISK = 'admin_uploads';
    const IMAGES = 'images';

    protected $fillable = [
        'name',
        'distance',
        'description',
    ];

    protected $casts = [
        'name'        => 'array',
        'distance'    => 'array',
        'description' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function setGPXFile(UploadedFile $file)
    {
        $path = $file->store('gpx', self::GPX_DISK);

        $this->gpx_filename = $path;
        $this->gpx_disk = self::GPX_DISK;
        $this->save();
    }

    public function clearGPXFile()
    {
        if(Storage::disk($this->gpx_disk)->exists($this->gpx_filename)) {
            Storage::disk($this->gpx_disk)->delete($this->gpx_filename);
        }

        $this->gpx_filename = null;
        $this->gpx_disk = null;
        $this->save();
    }

    public function addImage(UploadedFile $file): Media
    {
        return $this->addMedia($file)
                    ->preservingOriginal()
                    ->usingFileName($file->hashName())
                    ->toMediaCollection(self::IMAGES);
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
             ->fit(Manipulations::FIT_CROP, 500, 333)
             ->optimize()
             ->performOnCollections(self::IMAGES);

        $this->addMediaConversion('web')
             ->fit(Manipulations::FIT_CROP, 1200, 800)
             ->optimize()
             ->performOnCollections(self::IMAGES);
    }

    public function setImagePositions(array $image_ids)
    {
        collect($image_ids)
            ->map(fn ($id) => Media::find($id))
            ->reject(fn ($image) => !($image instanceof Media))
            ->each(fn (Media $media, $position) => $this->updateMediaPosition($media, $position));

    }

    private function updateMediaPosition(Media $image, int $position)
    {
        $image->setCustomProperty('position', $position);
        $image->save();
    }
}