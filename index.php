<?php
/*
Thinkstack Wordpress Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Thinkstack Wordpress plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Thinkstack Wordpress plugin.
*/

/*
 * Plugin Name: Thinkstack
 * Description: Enables thinkstack chatbot.
 * Version: 1.3
 * Author: Thinkstack
 * Author URI: https://thinkstack.ai
 * License: GPLv2
 * Text Domain: thinkstack
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Script tag that loads the thinkstack chatbot to a website
// <script chatbot_id="chatbot_id" src="https://thinkstack.ai/chatbot.js"></script>

// Add the script tag to the footer of the page
function thinkstack_add_script_tag()
{
    $chatbot_id = get_option('thinkstack_chatbot_id');
    $chatbot_enabled = get_option('thinkstack_chatbot_enabled');

    if (empty($chatbot_id) || !$chatbot_enabled) {
        return;
    }
    echo '<script chatbot_id="' . $chatbot_id . '" src="https://app.thinkstack.ai/bot/thinkstackai-loader.min.js"></script>';
}

add_action('wp_footer', 'thinkstack_add_script_tag');

// Add the settings page to the admin menu
function thinkstack_add_admin_menu()
{
    add_menu_page('Thinkstack', 'Thinkstack', 'manage_options', 'thinkstack', 'thinkstack_options_page');
}

add_action('admin_menu', 'thinkstack_add_admin_menu');

// Register the settings
function thinkstack_settings_init()
{
    register_setting('pluginPage', 'thinkstack_chatbot_enabled');
    register_setting('pluginPage', 'thinkstack_chatbot_id');

    add_settings_section(
        'thinkstack_pluginPage_section',
        __('Chatbot Settings', 'thinkstack'),
        'thinkstack_settings_section_callback',
        'pluginPage'
    );

    // add a checkbox to enable/disable the chatbot default is enabled
    add_settings_field(
        'thinkstack_chatbot_enabled',
        __('Enable Chatbot', 'thinkstack'),
        'thinkstack_chatbot_enabled_render',
        'pluginPage',
        'thinkstack_pluginPage_section'
    );

    add_settings_field(
        'thinkstack_chatbot_id',
        __('Chatbot ID', 'thinkstack'),
        'thinkstack_chatbot_id_render',
        'pluginPage',
        'thinkstack_pluginPage_section'
    );
}

// custom icon for the plugin settings
// icon url https://app.dev.thinkstack.ai/logo.svg

function thinkstack_add_icon()
{
?>
    <style>
        #adminmenu #toplevel_page_thinkstack .wp-menu-image::before {
            content: '';
            background: url(<?php echo plugin_dir_url(  __FILE__ ) . 'thinkstack_logo.svg'; ?>) no-repeat center;
            width: 40px;
            height: 20px;
            background-size: 30px;
        }
    </style>
<?php
}

add_action('admin_head', 'thinkstack_add_icon');
add_action('admin_init', 'thinkstack_settings_init');

// Render the checkbox to enable/disable the chatbot
function thinkstack_chatbot_enabled_render()
{
    $chatbot_enabled = get_option('thinkstack_chatbot_enabled');
?>
    <input type="checkbox" name="thinkstack_chatbot_enabled" <?php checked($chatbot_enabled, 1); ?> value="1">
<?php
}

// Render the settings page
function thinkstack_chatbot_id_render()
{
    $chatbot_id = get_option('thinkstack_chatbot_id');
?>
    <input type="text" name="thinkstack_chatbot_id" value="<?php echo $chatbot_id; ?>">
<?php
}

function thinkstack_settings_section_callback()
{
    echo esc_html__('Enter your chatbot ID below.', 'thinkstack');
}

function thinkstack_options_page()
{
?>
    <form action="options.php" method="post">
        <h1><?php echo esc_html__('Thinkstack Chatbot', 'thinkstack'); ?></h1>
        <?php
        settings_fields('pluginPage');
        do_settings_sections('pluginPage');
        submit_button();
        ?>
    </form>
<?php
}

?>