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
        public function the_content_for_related_posts($content) {
            if (!is_single() || !in_the_loop()) {
                return $content;
            }

            $categories = get_the_category();
            if (empty($categories)) {
                return $content;
            }

            $category_ids = array_map(function ($category) {
                return $category->term_id;
            }, $categories);

            $query_args = [
                'category__in' => $category_ids,
                'post__not_in' => [get_the_ID()],
                'posts_per_page' => 5,
                'orderby' => 'rand',
            ];
            
            $related_posts = new WP_Query($query_args);

            ob_start();

            if ($related_posts->have_posts()) {
            ?>
                <h3>Related Posts:</h3>
            <?php 
                while ($related_posts->have_posts()) {
                    $related_posts->the_post();
                ?>    
                <div class="title">
                        <div class="title"> <a href="<?php  get_permalink(); ?>"> <?php echo the_title(); ?> </a></div>
                        <div class="Thumbnail">  <?php  the_post_thumbnail( 'thumbnail' );  ?> </div>
                        <div class="content">  <?php echo wp_trim_words(get_the_content(), 20, '...');  ?> </div>
                </div>
                <?php 
                }
    
                wp_reset_postdata();
            }
            $html = ob_get_clean();
            $content .= $html;
            return $content;
        }
    }


new target_related_posts();