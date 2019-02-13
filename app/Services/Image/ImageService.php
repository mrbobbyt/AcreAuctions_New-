<?php
declare(strict_types = 1);
# only for listings, because have fullsize and preview

namespace App\Services\Image;

use App\Models\FullsizePreview;
use App\Repositories\Listing\Contracts\ListingRepositoryContract;
use Exception;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use ImageConverter;
use App\Models\Image;
use App\Services\Image\Contracts\ImageServiceContract;
use Throwable;

class ImageService implements ImageServiceContract
{
    protected $listingRepo;

    public function __construct(ListingRepositoryContract $listingRepo)
    {
        $this->listingRepo = $listingRepo;
    }


    /**
     * @param UploadedFile $item
     * @param int $id
     * @return bool
     * @throws Throwable
     */
    public function create(UploadedFile $item, int $id): bool
    {
        $full = $this->createImage($item, $id, 'fullsize');
        $preview = $this->createImage($item, $id, 'preview');

        return FullsizePreview::query()->make()->fill([
            'listing_id' => $id,
            'fullsize_id' => $full,
            'preview_id' => $preview,
        ])->saveOrFail();
    }


    /**
     * @param UploadedFile $item
     * @param int $id
     * @param string $type
     * @return int
     * @throws Throwable
     */
    protected function createImage(UploadedFile $item, int $id, string $type): int
    {
        $img = ImageConverter::make($item);
        $width = $type === 'fullsize' ? self::MAX_IMG_WIDTH : self::MAX_PREVIEW_WIDTH;
        $height = $type === 'fullsize' ? self::MAX_IMG_HEIGHT : self::MAX_PREVIEW_HEIGHT;

        if ($img->width() > $width) {
            $img->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } elseif ($img->height() > $height) {
            $img->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        $name = str_random(20) .'_listing_'. $id;
        $img->save(public_path().'/images/'.$type.'/'.$name.'.jpg');

        $image = Image::query()->make()->fill([
            'entity_id' => $id,
            'entity_type' => Image::TYPE_LISTING,
            'name' => $name.'.jpg',
        ]);

        $image->saveOrFail();

        return $image->id;
    }


    /**
     * @param UploadedFile $item
     * @param int $key
     * @param int $id
     * @return bool
     * @throws Throwable
     */
    public function update(int $key, UploadedFile $item, int $id): bool
    {
        if ($image = $this->listingRepo->findImage($key, $id)) {
            $relation = FullsizePreview::query()->where('fullsize_id', $image->id)->first();
            $imagePreview = $this->listingRepo->findImage($relation->preview_id, $id);
            $this->delete($image);
            $this->delete($imagePreview);
        }
        $this->create($item, $id);

        return true;
    }


    /**
     * @param Model $image
     * @return bool
     * @throws Exception
     */
    public function delete(Model $image): bool
    {
        if ($relation = FullsizePreview::query()->where('fullsize_id', $image->id)->first()) {
            $type = 'fullsize';
            $relation->delete();
        } else {
            $type = 'preview';
        }

        if (File::exists(public_path().'/images/'.$type.'/'.$image->name)) {
            File::delete(public_path().'/images/'.$type.'/'.$image->name);
        }

        return $image->delete();
    }
}
