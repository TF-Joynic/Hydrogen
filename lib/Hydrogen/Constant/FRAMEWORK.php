<?php

/**
 * Request Method(s)
 */

// regard XML_HTTP_REQUEST as AJAX from the name
defined('HTTP_METH_AJAX') || define('HTTP_METH_AJAX', 'AJAX');

/**
 * HTTP Header(s)
 */
defined('HTTP_HEADER_HOST') || define('HTTP_HEADER_HOST', 'Host');
defined('HTTP_HEADER_CONNECTION') || define('HTTP_HEADER_CONNECTION', 'Connection');
defined('HTTP_HEADER_CACHE_CTRL') || define('HTTP_HEADER_CACHE_CTRL', 'Cache-Control');
defined('HTTP_HEADER_ACCEPT') || define('HTTP_HEADER_ACCEPT', 'Accept');

defined('HTTP_HEADER_UPGRADE_INSECURE_REQUESTS')
|| define('HTTP_HEADER_UPGRADE_INSECURE_REQUESTS', 'Upgrade-Insecure-Requests');

defined('HTTP_HEADER_USER_AGENT') || define('HTTP_HEADER_USER_AGENT', 'User-Agent');
defined('HTTP_HEADER_ACCEPT_ENCODING') || define('HTTP_HEADER_ACCEPT_ENCODING', 'Accept-Encoding');
defined('HTTP_HEADER_ACCEPT_LANGUAGE') || define('HTTP_HEADER_ACCEPT_LANGUAGE', 'Accept-Language');
defined('HTTP_HEADER_CONTENT_TYPE') || define('HTTP_HEADER_CONTENT_TYPE', 'Content-Type');
defined('HTTP_HEADER_ACCESS_CONTROL_ALLOW_ORIGIN') || define('HTTP_HEADER_ACCESS_CONTROL_ALLOW_ORIGIN', 'Access-Control-Allow-Origin');
/**
 * HTTP Header(s) definition end.
 */


defined('SCOPE_APPLICATION') || define('SCOPE_APPLICATION', 'application');

defined('PRELOAD_FILES') || define('PRELOAD_FILES', 'preloadFiles');
defined('CLASS_LOAD_MAP') || define('CLASS_LOAD_MAP', 'classLoadMap');
defined('COMPOSER') || define('COMPOSER', 'composer');

defined('COMPOSER_AUTOLOAD_CLASS_MAP_FILENAME')
|| define('COMPOSER_AUTOLOAD_CLASS_MAP_FILENAME', 'autoload_classmap.php');
