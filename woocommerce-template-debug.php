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
	if (WP_DEBUG) {
		$args = func_get_args();

		$wooTemplate       = ($args[0] ?? 'N/A');
		$locatedTemplate   = ($args[2] ?? 'N/A');
		$templatePath      = ($args[1] ?? 'N/A');
		$templateArguments = print_r($args[3] ?? [], true);

		$argumentLines = explode(PHP_EOL, $templateArguments);

		foreach ($argumentLines as $number => $argumentLine) {
			$argumentLines[$number] = "                        $argumentLine";
		}

		$templateArguments = implode(PHP_EOL, $argumentLines);

		$prefixPosition = strpos($locatedTemplate, ABSPATH);

		if ($prefixPosition === 0) {
			$locatedTemplate = '/' . substr($locatedTemplate, strlen(ABSPATH));
		}

		echo <<<EOF

<!-- Woocommerce DEBUG TEMPLATE START: $wooTemplate

    Located Template: $locatedTemplate
    Template Path: $templatePath
    Template Arguments: 
$templateArguments
-->

EOF;
	}
}

add_action('woocommerce_after_template_part', 'woocommerce_template_debug_after_template_part', 99, 4);

/**
 * Print a message signaling the end of the Woocommerce template.
 *
 * @return void
 */
function woocommerce_template_debug_after_template_part()
{
	if (WP_DEBUG) {
		$args        = func_get_args();
		$wooTemplate = ($args[0] ?? 'N/A');

		echo <<<EOF
<!-- Woocommerce DEBUG TEMPLATE END: $wooTemplate -->

EOF;
	}
}
