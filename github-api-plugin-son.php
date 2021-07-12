<?php
/*
 * Plugin Name: Git Hub LB
 * Description: Lista os repositórios de um usuário
 *  Version: 1.0.0
 * Author: Lucas B. M.
*/

//=================================================
// Security: Abort if this file is called directly
//=================================================
if (!defined('ABSPATH')) {
    die;
}

//=================================================
// Constante de caminho abslouto
//=================================================
define('PLUGIN_DIR', plugin_dir_path(__FILE__));

//=================================================
// Includes
//=================================================
require_once('GithubWidget.php');

//=================================================
// Widget
//=================================================
add_action('widgets_init', 'gaps_registrar_widget');
function gaps_registrar_widget()
{
    register_widget('GithubWidget');
}

//=================================================
// Styles
//=================================================
// enqueueing the custom style sheet on WordPress login page
add_action('wp_enqueue_scripts', 'github_api_stylesheet');

function github_api_stylesheet()
{
    wp_enqueue_style('github-api-styles', plugin_dir_url(__FILE__) . '/css/github-api-plugin-son-styles.css');
}
