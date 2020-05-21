<?php 

namespace Vortechron\Essentials\Traits;

use Spatie\MediaLibrary\Models\Media;
use Vortechron\Essentials\Models\Defer;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

trait HasMedia
{
    use HasMediaTrait;

    public function saveDeferredMedia($request, $collection = 'default', $customProps = [])
    {
        $deferred = Defer::where('session_key', $request['key'])->first();
        if ($deferred) {
            foreach ($deferred->getMedia() as $deferredMedia) {
                $deferredMedia->model_type = get_class($this);
                $deferredMedia->model_id = $this->id;
                $deferredMedia->collection_name = $collection;
                
                foreach ($customProps as $key => $value) {
                    $deferredMedia->setCustomProperty($key, $value);
                }
                
                $deferredMedia->save();
            }

            $deferred->is_bind = true;
            $deferred->save();
        }
    }

    public function saveMedia($request, $collection = 'default', $customProps = [])
    {
        $this->saveDeferredMedia($request, $collection, $customProps);

        $media = isset($request['media']) ? $request['media'] : null;
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