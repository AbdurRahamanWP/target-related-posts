<?php
/**
 * Plugin Name: Related Posts
 * Plugin URI: https://targetsoftbd.com
 * Description: This is Related Posts Plugin show in single page
 * Version: 1.0.0
 * Author: Target Themes
 * Author URI: https://targetsoftbd.com
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: related-posts
 */

if ( ! defined( 'ABSPATH' ) ) {
    return;
}


class target_related_posts{
  
    public function __construct(){
        
    add_filter( 'the_content', array( $this, 'the_content_for_related_posts' ) ); 

    }

    function the_content_for_related_posts( $content ){
        global $post;

        if ( ! is_singular( 'post' ) ) {
            return $contents;
        }
        ob_start();
        $tags = wp_get_post_tags($post->ID);
        ?>
         <h2><?php the_title();  ?></h2> 
        
        <?php
        $html = ob_get_clean();
        $content .= $html;

        return $content;
    }
}

new target_related_posts();