<?php
/*
 Plugin Name: Woocommerce Template Debug
 Plugin URI: https://binaryengine.tech
 Description: Prints debug HTML comments at the start and end of Woocommerce templates.
 Author: Binary Engine
 Version: 0.1
 Author URI: https://binaryengine.tech
 Text Domain: woocommerce-template-debug
 */

/**
 * The debug statements will only be emitted if `WP_DEBUG` is set to true.
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!defined('WP_DEBUG') || !WP_DEBUG) {
	return;
}

add_action('woocommerce_before_template_part', 'woocommerce_template_debug_before_template_part', 0, 4);

/**
 * Print debug information about Woocommerce templates before the template is emitted.
 *
 * @return void
 */
function woocommerce_template_debug_before_template_part()
{
	$args            = func_get_args();
	$wooTemplate     = ($args[0] ?? 'N/A');
	$locatedTemplate = ($args[2] ?? 'N/A');
	$templatePath    = ($args[1] ?? 'N/A');

	// Indent the template arguments.
	$templateArguments = print_r($args[3] ?? [], true);
	$templateArguments = woocommerce_template_debug_indent_lines($templateArguments, 6);

	// We need to make the located path relative to the WordPress base directory.
	$locatedTemplate = woocommerce_template_debug_wordpress_relative_path($locatedTemplate);

	echo <<<EOF

<!-- Woocommerce DEBUG TEMPLATE START: $wooTemplate

    Located Template: $locatedTemplate
    Template Path: $templatePath
    Template Arguments: 
$templateArguments
-->

EOF;
}

add_action('woocommerce_after_template_part', 'woocommerce_template_debug_after_template_part', 99, 4);

/**
 * Print a message signaling the end of the Woocommerce template.
 *
 * @return void
 */
function woocommerce_template_debug_after_template_part()
{
	$args        = func_get_args();
	$wooTemplate = ($args[0] ?? 'N/A');

	echo <<<EOF
<!-- Woocommerce DEBUG TEMPLATE END: $wooTemplate -->

EOF;
}

add_filter('wc_get_template_part', 'woocommerce_template_debug_wc_get_template_part', 0, 3);

/**
 * Print debug information about Woocommerce templates parts before the template is emitted.
 *
 * @return string
 */
function woocommerce_template_debug_wc_get_template_part(): string
{
	$args            = func_get_args();
	$wooTemplate     = ($args[0] ?? 'N/A');
	$name    = ($args[1] ?? 'N/A');
	$slug = ($args[2] ?? 'N/A');

	// We need to make the located path relative to the WordPress base directory.
	$wooTemplate = woocommerce_template_debug_wordpress_relative_path($wooTemplate);

	$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
	$calleeBacktrace = $backtrace[3];
	$calleeBacktrace['file'] = woocommerce_template_debug_wordpress_relative_path($calleeBacktrace['file']);

	$callee = print_r($calleeBacktrace, true);
	$callee = woocommerce_template_debug_indent_lines($callee, 3);

	echo <<<EOF

<!-- Woocommerce DEBUG TEMPLATE PART: $slug-$name.php
    Located Template: $wooTemplate
    Slug: $slug
    Name: $name
    Source:
$callee
-->
EOF;

	return $args[0];
}

/**
 * Get the path of the file relative to the WordPress base directory.
 *
 * @param string $file
 *
 * @return string
 */
function woocommerce_template_debug_wordpress_relative_path(string $file): string {
	$prefixPosition = strpos($file, ABSPATH);

	if ($prefixPosition === 0) {
		$file = '/' . substr($file, strlen(ABSPATH));
	}

	return $file;
}

/**
 * Indent each line with spaces.
 *
 * @param string $string
 * @param int $indents
 *
 * @return string
 */
function woocommerce_template_debug_indent_lines(string $string, int $indents): string {
	$indentString = str_repeat('    ', $indents);
	$lines = explode(PHP_EOL, $string);

	foreach ($lines as $number => $line) {
		$lines[$number] = $indentString . $line;
	}

	return implode(PHP_EOL, $lines);
}
