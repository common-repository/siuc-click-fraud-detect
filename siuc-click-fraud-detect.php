<?php
/*
Plugin Name: Siuc click fraud detect
Plugin URI: https://siuc.biz/plugin/wp
Description: Client for Service to identify unfair competition
Version: 19.1
Author: Artem Bondarenko
Author URI: https://siuc.biz
License: GPL2
Text Domain: siuc-click-fraud-detect
Domain Path: /languages
*/

/*  Copyright 2015  Artem Bondarenko  (email : support@siuc.biz )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


add_action('init', 'siuc');
add_shortcode("siuc", "get_counter");
add_filter('widget_text', 'do_shortcode');
add_action( 'init', 'siuc_load_textdomain' );

function siuc_load_textdomain() {
  load_plugin_textdomain( 'siuc-click-fraud-detect', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function siuc()
{

    include_once (dirname(__FILE__).'/siuc.class.php');
    $options = get_option('siuc_options');
    if (!defined('_SIUC_')){
        define('_SIUC_', $options['unic']);
    }

    $GLOBALS['siuc'] = new Siuc();
}


add_action('admin_menu', 'add_page');

if (!function_exists('add_page')) {

    function add_page()
    {
        add_options_page( __( 'Siuc Setting Page', 'siuc-click-fraud-detect' ), __( 'Siuc Setting Page', 'siuc-click-fraud-detect' ), 'manage_options', 'siuc', 'siuc_options_frontpage');

    }
}

function siuc_options_frontpage()
{

    ?>

    <div class="wrap">

        <form action="options.php" method="post">

            <?php settings_fields('siuc_options'); ?>
            <?php do_settings_sections('siuc'); ?>
            <table class="form-table">

                <tr valign="top">

                    <td colspan="2">
                        <input name="Submit" type="submit" class="button button-primary"
                               value="<?php esc_attr_e('Save Changes', 'siuc-click-fraud-detect'); ?>"/>
                    </td>

                </tr>
            </table>
        </form>

    </div>

    <?php
}


add_action('admin_init', 'siuc_admin_init');
function siuc_admin_init()
{
    register_setting('siuc_options', 'siuc_options', 'siuc_options_validate');
    add_settings_section('siuc_main', 'Siuc Settings', 'siuc_section_text', 'siuc');
    add_settings_field('siuc_text_input', 'Unic', 'siuc_input_unic', 'siuc', 'siuc_main');
}

function get_counter(){
    return $GLOBALS['siuc']->counter();
}


function siuc_section_text()
{
    echo __( 'To get UNIC key, visit', 'siuc-click-fraud-detect' ) .  ' <a href="https://siuc.biz/sites/" target="_blank">https://siuc.biz/sites/</a></p>';
}

function siuc_input_unic()
{
    $options = get_option('siuc_options');
    echo "<input id='siuc_unic' class='normal-text code' name='siuc_options[unic]' size='30' type='text' value='{$options['unic']}' />";
}

function siuc_options_validate($input)
{
    $options = get_option('siuc_options');
    $options['unic'] = trim($input['unic']);
    if (!preg_match('/^[a-z0-9]*$/i', $options['unic'])) {
        $options['unic'] = '';
    }

    return $options;
}

?>