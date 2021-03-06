<?php

/**
 * @package    Grav\Common\Assets\Traits
 *
 * @copyright  Copyright (C) 2015 - 2019 Trilby Media, LLC. All rights reserved.
 * @license    MIT License; see LICENSE file for details.
 */

namespace Grav\Common\Assets\Traits;

use Grav\Common\Assets;

trait LegacyAssetsTrait
{

    /**
     * @param array $args
     * @param string $type
     * @return array
     */
    protected function unifyLegacyArguments($args, $type = Assets::CSS_TYPE)
    {
        // First argument is always the asset
        array_shift($args);

        if (\count($args) === 0) {
            return [];
        }
        if (\count($args) === 1 && \is_array($args[0])) {
            return $args[0];
        }

        switch ($type) {
            case(Assets::INLINE_CSS_TYPE):
                $defaults = ['priority' => null, 'group' => null];
                $arguments = $this->createArgumentsFromLegacy($args, $defaults);
                break;

            case(Assets::JS_TYPE):
                $defaults = ['priority' => null, 'pipeline' => true, 'loading' => null, 'group' => null];
                $arguments = $this->createArgumentsFromLegacy($args, $defaults);
                break;

            case(Assets::INLINE_JS_TYPE):
                $defaults = ['priority' => null, 'group' => null, 'attributes' => null];
                $arguments = $this->createArgumentsFromLegacy($args, $defaults);

                // special case to handle old attributes being passed in
                if (isset($arguments['attributes'])) {
                    $old_attributes = $arguments['attributes'];
                    $arguments = array_merge($arguments, $old_attributes);
                }
                unset($arguments['attributes']);

                break;

            default:
            case(Assets::CSS_TYPE):
                $defaults = ['priority' => null, 'pipeline' => true, 'group' => null, 'loading' => null];
                $arguments = $this->createArgumentsFromLegacy($args, $defaults);
        }

        return $arguments;
    }

    protected function createArgumentsFromLegacy(array $args, array $defaults)
    {
        // Remove arguments with old default values.
        $arguments = [];
        foreach ($args as $arg) {
            $default = current($defaults);
            if ($arg !== $default) {
                $arguments[key($defaults)] = $arg;
            }
            next($defaults);
        }

        return $arguments;
    }

    /**
     * Convenience wrapper for async loading of JavaScript
     *
     * @param string|array  $asset
     * @param int           $priority
     * @param bool          $pipeline
     * @param string        $group name of the group
     *
     * @return \Grav\Common\Assets
     * @deprecated Please use dynamic method with ['loading' => 'async'].
     */
    public function addAsyncJs($asset, $priority = 10, $pipeline = true, $group = 'head')
    {
        user_error(__CLASS__ . '::' . __FUNCTION__ . '() is deprecated since Grav 1.6, use dynamic method with [\'loading\' => \'async\']', E_USER_DEPRECATED);

        return $this->addJs($asset, $priority, $pipeline, 'async', $group);
    }

    /**
     * Convenience wrapper for deferred loading of JavaScript
     *
     * @param string|array  $asset
     * @param int           $priority
     * @param bool          $pipeline
     * @param string        $group name of the group
     *
     * @return \Grav\Common\Assets
     * @deprecated Please use dynamic method with ['loading' => 'defer'].
     */
    public function addDeferJs($asset, $priority = 10, $pipeline = true, $group = 'head')
    {
        user_error(__CLASS__ . '::' . __FUNCTION__ . '() is deprecated since Grav 1.6, use dynamic method with [\'loading\' => \'defer\']', E_USER_DEPRECATED);

        return $this->addJs($asset, $priority, $pipeline, 'defer', $group);
    }

}
