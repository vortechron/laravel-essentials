<?php

use Vortechron\Essentials\Models\Setting;
use Illuminate\Validation\ValidationException;
use Vortechron\Essentials\Models\Config;

if (! function_exists('parseInput')) {
    function parseInput($array)
    {
        foreach($array as $key => $value) {
            if ($value == 'true' || $value == 'false')
                $array[$key] = $value == 'true' ? 1 : 0;
            else 
                $array[$key] = $value;
        }

        return $array;
    }
}

if (! function_exists('danger_in_json')) {
    function danger_in_json($array)
    {
        return abort(response()->json($array));
    }
}

if (! function_exists('danger')) {
    function danger($title, $subtitle = null, $type = 'danger')
    {
        $response = back()->with($type . 'Flash', [
            'title' => $title,
            'subtitle' => $subtitle,
        ]);
        
        abort($response);
    }
}

if (! function_exists('flash')) {
    function flash($title, $subtitle = null, $type = 'success')
    {
        $text = ['title' => $title, 'subtitle' => $subtitle];
        if (is_null($subtitle)) {
            $text = ['title' => $title, 'subtitle' => ''];
        }
        return request()->session()->flash($type . 'Flash', $text);
    }
}

if (! function_exists('flashSaved')) {
    function flashSaved()
    {
        return flash('Your changes has been saved');
    }
}

if (! function_exists('flashDeleted')) {
    function flashDeleted()
    {
        return flash('Deleted Successfully');
    }
}

if (! function_exists('is_equal_url')) {
    function is_equal_url($route)
    {
        if (is_string($route)) {
            $route = [$route];
        }
        return str_replace(['https', 'http'], '', route(...$route)) == str_replace(['https', 'http'], '', \Request::url());
    }
}

if (! function_exists('add_class_by_route')) {
    function add_class_by_route($route, $class = 'active', $elseClass = '')
    {
        if (!is_equal_url($route)) {
            return $elseClass;
        }
        return $class;
    }
}

if (! function_exists('add_class_by_controller')) {
    function add_class_by_controller($controller, $class = 'active', $elseClass = '')
    {
        if (! current_inside_controller($controller)) {
            return $elseClass;
        }
        return $class;
    }
}

if (! function_exists('error')) {
    function error($msgs)
    {
        if (is_string($msgs)) {
            $msgs = [$msgs];
        }
        throw ValidationException::withMessages([
            'error' => $msgs,
        ]);
    }
}

if (! function_exists('current_inside_controller')) {
    function current_inside_controller($controllerToCheck)
    {
        $controller = explode('@', app('router')->currentRouteAction())[0];
        
        if (is_array($controllerToCheck)) {
            return in_array($controller, $controllerToCheck);
        }
        return $controller == $controllerToCheck;
    }
}

if (! function_exists('base64url_encode')) {
    function base64url_encode($str)
    {
        return strtr(base64_encode($str), '+/', '-_');
    }
}

if (! function_exists('model')) {
    function model($model, $instance = false)
    {
        $model = '\App\\'. studly_case($model);
        if ($instance)
            return new $model;
        return $model;
    }
}

if (! function_exists('placeholder_img')) {
    function placeholder_img()
    {
        return asset('images/placeholder.png');
    }
}

if (! function_exists('remove_element_by_selector')) {
    function remove_element_by_selector($selector, $html)
    {
        $html = \HTMLDomParser::str_get_html($html);
        foreach($html ->find($selector) as $item) {
            $item->outertext = '';
        }
        
        $html->save();
        return $html;
    }
}

if (! function_exists('convert_bytes'))
{
  function convert_bytes($bytes, $precision = 2, $unit = true) 
  { 
      $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
      $bytes = max($bytes, 0); 
      $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
      $pow = min($pow, count($units) - 1); 
      // Uncomment one of the following alternatives
      $bytes /= pow(1024, $pow);
      // $bytes /= (1 << (10 * $pow)); 
      if ($unit) 
        return round($bytes, $precision) . ' ' . $units[$pow]; 
      
      return round($bytes, $precision); 
    } 
}

if (! function_exists('array_to_object')) {
    function array_to_object($array)
    {
        return json_decode(json_encode($array));
    }
}

if (! function_exists('user')) {
    function user()
    {
        return \Auth::user();
    }
}

if (! function_exists('timestamp_format')) {
    function timestamp_format()
    {
        return 'Y-m-d H:i:s';
    }
}

if (! function_exists('datetime_format')) {
    function datetime_format()
    {
        return 'Y-m-d\Th:i';
    }
}

if (! function_exists('br2nl')) {
    function br2nl($string)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }
}

if (! function_exists('setting')) {
    function setting($group = null, $key = null, $default = null)
    {
        if (func_num_args() == 0) return new Setting;

        return Setting::find($group = null, $key = null, $default = null);
    }
}

if (! function_exists('db_config')) {
    function db_config($key = null, $default = null)
    {
        if (func_num_args() == 0) return new Config;
        if (func_num_args() == 1) return Config::find($key);

        return Config::find($key, $default);
    }
}
