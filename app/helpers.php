<?php

namespace App;

use Roots\Sage\Container;

/**
 * Get the sage container.
 *
 * @param string $abstract
 * @param array  $parameters
 * @param Container $container
 * @return Container|mixed
 */
function sage($abstract = null, $parameters = [], Container $container = null)
{
    $container = $container ?: Container::getInstance();
    if (!$abstract) {
        return $container;
    }
    return $container->bound($abstract)
        ? $container->makeWith($abstract, $parameters)
        : $container->makeWith("sage.{$abstract}", $parameters);
}

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param array|string $key
 * @param mixed $default
 * @return mixed|\Roots\Sage\Config
 * @copyright Taylor Otwell
 * @link https://github.com/laravel/framework/blob/c0970285/src/Illuminate/Foundation/helpers.php#L254-L265
 */
function config($key = null, $default = null)
{
    if (is_null($key)) {
        return sage('config');
    }
    if (is_array($key)) {
        return sage('config')->set($key);
    }
    return sage('config')->get($key, $default);
}

/**
 * @param string $file
 * @param array $data
 * @return string
 */
function template($file, $data = [])
{
    if (remove_action('wp_head', 'wp_enqueue_scripts', 1)) {
        wp_enqueue_scripts();
    }

    return sage('blade')->render($file, $data);
}

/**
 * Retrieve path to a compiled blade view
 * @param $file
 * @param array $data
 * @return string
 */
function template_path($file, $data = [])
{
    return sage('blade')->compiledPath($file, $data);
}

/**
 * @param $asset
 * @return string
 */
function asset_path($asset)
{
    return sage('assets')->getUri($asset);
}

/**
 * @param string|string[] $templates Possible template files
 * @return array
 */
function filter_templates($templates)
{
    $paths = apply_filters('sage/filter_templates/paths', [
        'views',
        'resources/views'
    ]);
    $paths_pattern = "#^(" . implode('|', $paths) . ")/#";

    return collect($templates)
        ->map(function ($template) use ($paths_pattern) {
            /** Remove .blade.php/.blade/.php from template names */
            $template = preg_replace('#\.(blade\.?)?(php)?$#', '', ltrim($template));

            /** Remove partial $paths from the beginning of template names */
            if (strpos($template, '/')) {
                $template = preg_replace($paths_pattern, '', $template);
            }

            return $template;
        })
        ->flatMap(function ($template) use ($paths) {
            return collect($paths)
                ->flatMap(function ($path) use ($template) {
                    return [
                        "{$path}/{$template}.blade.php",
                        "{$path}/{$template}.php",
                        "{$template}.blade.php",
                        "{$template}.php",
                    ];
                });
        })
        ->filter()
        ->unique()
        ->all();
}

/**
 * @param string|string[] $templates Relative path to possible template files
 * @return string Location of the template
 */
function locate_template($templates)
{
    return \locate_template(filter_templates($templates));
}

/**
 * Determine whether to show the sidebar
 * @return bool
 */
function display_sidebar()
{
    static $display;
    isset($display) || $display = apply_filters('sage/display_sidebar', false);
    return $display;
}

/**
 * Display a donut chart SVG image
 * from: https://medium.com/@heyoka/scratch-made-svg-donut-pie-charts-in-html5-2c587e935d72
 */
function donut($start = [], $end = [], $id = '', $title = '', $desc = '', $background = '#444') {
    if (empty($start)) {
        return '';
    }

    if (empty($end)) {
        $end = ['value' => '', 'color' => ''];
    }

    if (empty($id)) {
        global $donut_count;
        $donut_count = empty($donut_count) ? 1 : $donut_count + 1;
        $id = 'donut-' . $donut_count;
    }

    $start_remainder = 100 - $start['value'];
    $end_remainder = empty($end['value']) ? '' : 100 - $end['value'];

    $svg = "
        <svg width='100%' height='100%' viewBox='0 0 42 42' class='wp-block-carney-donut' id='$id'
             aria-labelledby='$id-title' aria-describedby='$id-desc' role='img'
             data-start-value='{$start['value']}' data-start-color='{$start['color']}'
             data-end-value='{$end['value']}' data-end-color='{$end['color']}'
        >
            <title id='$id-title'>$title</title>
            <desc id='$id-desc'>$desc</desc>
            <circle class='donut-ring' cx='21' cy='21' r='15.91549430918954'
                    fill='transparent' stroke='$background' stroke-width='5'
                    role='presentation'
            ></circle>

            <circle class='donut-segment' cx='21' cy='21' r='15.91549430918954'
                    fill='transparent' stroke='{$start['color']}' stroke-width='5' stroke-dasharray='{$start['value']} {$start_remainder}' stroke-dashoffset='25' aria-labelledby='$id-text'></circle>

          <g class='wp-block-carney-donut__text' id='$id-text'>
            <text x='50%' y='50%' class='wp-block-carney-donut__value'>
                <tspan class='wp-block-carney-donut__value__number'>{$start['value']}</tspan><tspan dy='-2.75' class='wp-block-carney-donut__value__suffix'>%</tspan>
            </text>
            <text x='50%' y='50%' class='wp-block-carney-donut__label'>$title</text>
          </g>
        </svg>
    ";

    return preg_replace('/\n+\s*/', '', $svg);
}
