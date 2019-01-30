# Laravel Essentials

## installation

``` composer require vortechron/laravel-essentials ```

then,

run ``` php artisan essentials:install ```

this will,
1. modify package.json (u need to npm update)
    "@estudioliver/vue-uuid-v4": "^1.0.0",
    "bootstrap-vue": "^2.0.0-rc.11",
    "form-backend-validation": "^2.3.3",
    "moment": "^2.22.2",
    "moment-timezone": "^0.5.21",
    "slugify": "^1.3.1",
    "sweetalert2": "^7.28.4",
    "tailwindcss": "^0.6.6",
    "v-money": "^0.8.1",
    "validator": "^10.8.0",
    "vue-avatar": "^2.1.6",
    "vue-element-loading": "^1.0.4",
    "vue-flatpickr-component": "^7.0.6",
    "vue-form-generator": "^2.3.1",
    "vue-multiselect": "^2.1.3",
    "vue-overdrive": "0.0.12",
    "vue-social-sharing": "^2.3.3",
    "vue-wait": "^1.3.2",
    "vue2-transitions": "^0.2.3",
    "vuedraggable": "^2.16.0"
2. publish resources/essentials/app.js and app.scss
3. modify webpack.mix.js (u need to npm run dev)


# What it can do?

## helpers

see Http/helpers.php

## blade helper

@errors // with danger() helper
@alerts // with flash() helper
@old // alias to {{ old() }}
@route // alias to {{ route() }}

## other

fix mysql error: Specified key was too long error