<?php 

namespace Vortechron\Essentials\Traits;

use Vortechron\Essentials\Models\Defer;
use Spatie\MediaLibrary\InteractsWithMedia;

trait HasMedia
{
    use InteractsWithMedia;

    public function saveDeferredMedia($defers, $collection = 'default', $customProps = [])
    {
        foreach ($defers as $deferredMedia) {
            $deferredMedia->model_type = get_class($this);
            $deferredMedia->model_id = $this->id;
            $deferredMedia->collection_name = $collection;
            
            foreach ($customProps as $key => $value) {
                $deferredMedia->setCustomProperty($key, $value);
            }
            
            $deferredMedia->save();
        }
    }

    public function saveMedia($request, $collection = 'default', $customProps = [])
    {
        $ids = collect($request ?? [])->pluck('id')->toArray();
        if (count($ids) > 0) {
            $defers = config('medialibrary.media_model')::whereIn('id', $ids)->get();
            $this->saveDeferredMedia($defers, $collection, $customProps);
            $this->updateMedia(
                $defers->toArray(), 
                $collection
            );
    
            config('medialibrary.media_model')::setNewOrder($ids);
        } else {
            $this->updateMedia([], $collection);
        }
    }
}