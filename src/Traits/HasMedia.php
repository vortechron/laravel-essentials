<?php 

namespace Vortechron\Essentials\Traits;

use App\Defer;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait HasMedia
{
    use HasMediaTrait;

    public function saveMedia($request, $collection = 'default')
    {
        $deferred = Defer::where('session_key', $request['key'])->first();
        $media = isset($request['media']) ? $request['media'] : null;
        
        if ($deferred) {
            foreach ($deferred->getMedia() as $deferredMedia) {
                $deferredMedia->model_type = get_class($this);
                $deferredMedia->model_id = $this->id;
                $deferredMedia->collection_name = $collection;
                $deferredMedia->save();
            }

            $deferred->is_bind = true;
            $deferred->save();
        }

        if ($media) {
            // Resync Media
            $this->updateMedia(
                config('medialibrary.media_model')::whereIn('id', $media)->get()->toArray(), 
                $collection
            );
    
            config('medialibrary.media_model')::setNewOrder($media);
        } else {
            $this->updateMedia(
                [], 
                $collection
            );
        }
    }
}