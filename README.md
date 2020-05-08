# Laravel Essentials

This package provide basic scaffolding for your next big project. This package heavily depend on [v-essentials](https://www.npmjs.com/package/v-essentials)

## installation

``` composer require vortechron/laravel-essentials ```

``` 
php artisan vendor:publish --provider="Vortechron\Essentials\ServiceProvider"
php artisan migrate
```

# What it can do?

## Easy CRUD

TL;DR
<details><summary>Show Code</summary>
<p>

- Requirement

```
// On Model
use \Vortechron\Essentials\Traits\HasMedia;
use \Vortechron\Essentials\Traits\Modeler;
```

```
// On Controller
use \Vortechron\Essentials\Traits\Controller\HasCrud;
```

- Example Usage

PostController.php
```
public function create()
{
    // forModel just a convinient way to normalize null data.
    $this->prepareData(
        $post->forModel(['author'], ['featured']),
        route('post.create')
    );

    return view('post.template');
}

// for model creation/update we dont dictate how you want to do it, its all depend on you
// except for media
public function store(Request $request)
{
    $post = \App\Post::create($request->only('title', 'description'));

    // must use saveMedia if you use our fieldMediaUpload
    $post->saveMedia($request->featured, 'collection_name');

    return view('post.template');
}

public function edit(Post $post)
{
    $this->prepareData(
        $post->forModel(['author'], ['featured']),
        route('post.update', $post)
    );

    return view('post.template');
}

public function update(Request $request, Post $post)
{
    $post->update($request->only('title', 'description'));

    $post->saveMedia($request->featured, 'collection_name');

    return view('post.template');
}
```

post/template.blade.php
```

<form action="{{ $_action }}" method="POST">
    @prepareMethod

    <alpine inline-template :populate-data="{ model: @json($_model) }">

        <vfg>

            {
                ....
                name: 'title'
            },
            {
                ...
                name: 'description'
            },
            {
                type: 'media-upload',
                name: 'featured',
                multiple: false
            }

        </vfg>

        @create
        <button type="submit">Create</button>
        @else
        <button type="submit">Save</button>
        <button @click.prevent="$refs.deleteForm.submit()">Delete</button>
        @endif

    </alpine>

</form>

<form ref="deleteForm" action="{{ $_action }}" method="POST">
    @method('delete')
</form>

```

</p>
</details>


## IC Card Helper (Malaysia Citizen Identification Card)

```
$ic = new \Vortechron\Essentials\Core\IC($request->ic);

$user->dob = $ic->getDateOfBirth();
$user->ic = $ic->getIc();
$user->gender = $ic->getGender();

```

## Turbolinks support

Just include below line in your Kernel.php
```
\Vortechron\Essentials\Core\TurbolinksMiddleware::class
```

see Core directory for more classes

## helpers

- [Ziggy](https://github.com/tightenco/ziggy) routes

` @routes `

- helpers

see Http/helpers.php

plus

see https://github.com/calebporzio/awesome-helpers

## blade helper

```
@errors // with danger() helper
@alerts // with flash() helper
@old // alias to {{ old() }}
@route // alias to {{ route() }}
```

## glorious @declareFalse @declareTrue @declareNull directives

use case

```
// auth.register
@extends('layouts.app')

// auth.register-no-padding
@extends('layouts.app', ['padding' => false, 'um' => 'test'])

// layouts.app
@declareTrue('padding') 
@declareNull('hm', 'um') 
@declareFalse('hmm') 
<main class="

@if($padding) // $padding variable will return true if undefine else it will return it define variable from @extends
py-4
@endif

">
    @yield('content')
</main>

@dump($hm, $um, $hmm) // null, 'test', false
```

## other

fix mysql error: Specified key was too long error
