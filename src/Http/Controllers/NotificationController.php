<?php

namespace Vortechron\Essentials\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\Filesystem;

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

        return view('admin.notification.index', compact('data'));
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

        return view('admin.notification.template', compact('notification', 'model'));
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

        $content = $this->sanitize($notification, $request->content);

        file_put_contents(
            $notification->content_path,
            $content
        );

        return $this->handleRedirect(route('admin.notifications.edit', $notification->slug), route('admin.notifications.index'));
    }

    protected function getNotifications()
    {
        $data = collect([]);
        $fs = new Filesystem;
    
        foreach ($fs->files(app_path('Notifications')) as $file) {
            $baseName = $file->getBasename('.php');
            $slugName = Str::slug($file->getBasename('.php'));

            $fullName = '\App\Notifications\\'. $baseName;
            $instance = new $fullName;

            if ($instance instanceof \Vortechron\Essentials\Core\Notification == false) continue;

            $basePath = $instance->getBasePath();
            $contentPath = resource_path("views\\{$basePath}\\{$slugName}.blade.php");
            $content = nl2br($fs->get($contentPath));

            $data->push((object) [
                'base_name' => $baseName,
                'slug' => $slugName,
                'instance' => $instance,
                'content' => $content,
                'content_path' => $contentPath
            ]);
        }

        return $data;
    }

    protected function sanitize($notification, $raw)
    {
        fo
    }

}
