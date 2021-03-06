<?php

namespace Vortechron\Essentials\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\Filesystem;
use Vortechron\Essentials\Models\Config;
use Vortechron\Essentials\Core\Proxy;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->getNotifications();

        return view(config('laravel-essentials.admin.view_path').'.notifications.index', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit($notification)
    {
        $data = $this->getNotifications();

        $notification = $data->where('slug', $notification)->first();

        $model = [
            'subject' => $notification->instance->getSubject(),
            'content' => $notification->content
        ];

        return view(config('laravel-essentials.admin.view_path').'.notifications.template', compact('notification', 'model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $notification)
    {
        $request->validate([
            'subject' => 'required|string',
            'content' => 'required|string'
        ]);

        $data = $this->getNotifications();

        $notification = $data->where('slug', $notification)->first();

        Config::set("notifications.{$notification->slug}", $request->subject);

        $content = $this->sanitize($notification, $request->content);

        file_put_contents(
            $notification->content_path,
            $content
        );

        flashSaved();

        return $this->handleRedirect(route('admin.notifications.edit', $notification->slug), route('admin.notifications.index'));
    }

    protected function getNotifications()
    {
        $data = collect([]);
        $fs = new Filesystem;
    
        foreach ($fs->files(app_path('Notifications')) as $file) {
            $baseName = $file->getBasename('.php');

            $fullName = '\App\Notifications\\'. $baseName;
            $instance = new $fullName(
                new Proxy(null), 
                new Proxy(null), 
                new Proxy(null), 
                new Proxy(null), 
                new Proxy(null), 
                new Proxy(null),
                new Proxy(null)
            );

            if ($instance instanceof \Vortechron\Essentials\Core\Notification == false) continue;

            $basePath = $instance->getBasePath();
            $contentPath = resource_path("views\\{$basePath}\\{$instance->getSlug()}.blade.php");
            $content = $fs->get($contentPath);

            $data->push((object) [
                'base_name' => $baseName,
                'slug' => $instance->getSlug(),
                'instance' => $instance,
                'content' => $content,
                'content_path' => $contentPath
            ]);
        }

        return $data;
    }

    protected function sanitize($notification, $raw)
    {
        // temporary replace variable with placeholder 
        // $content = br2nl($raw);
        $content = $raw;
        $content = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"", $content);

        foreach ($notification->instance->getData() as $key => $value) {
            $content = str_replace("\${$key}", "{{{$key}}}", $content);
        }

        // sanitize
        $content = str_replace(['$', '<?php', '<?=', '?>', '\r'], '', $content);

        // replace back
        foreach ($notification->instance->getData() as $key => $value) {
            $content = str_replace("{{{$key}}}", "\${$key}", $content);
        }

        return $content;
    }

}
