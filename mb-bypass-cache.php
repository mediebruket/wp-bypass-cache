<?php

/**
* Plugin Name: Bypass Cache
* Description: Bypass WPFC cache for specified URLs.
* Author:      Mediebruket
* Version:     0.1
* Author URI:  https://www.mediebruket.no/
*/

namespace Mediebruket\BypassCache;

require_once __DIR__ . '/src/BypassCache.php';

// If this filed is called directly.
defined('WPINC') || die;

new BypassCache();
