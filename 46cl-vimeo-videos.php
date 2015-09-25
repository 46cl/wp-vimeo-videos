<?php
/*
Plugin Name: 46cl - Vimeo Videos
Plugin URI:  https://github.com/46cl/wp-vimeo-videos
Description: Vimeo Videos integration by 46cl for Wordpress
Version:     0.0.1
Author:      46cl
Author URI:  http://46cl.fr
*/

require_once __DIR__ . '/vendor/autoload.php';
Qscl\VimeoVideos\VimeoVideos::load();
