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

    add_action('wp_enqueue_scripts', array( $this,'related_post_enqueue_styles') );

    }

    public function related_post_enqueue_styles() {
       
        wp_enqueue_style('main_style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css',array(),'1.0','all');

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
            'post_type' => 'post',
            'category__in' => $category_ids,
            'post__not_in' => [get_the_ID()],
            'posts_per_page' => 5,
            'orderby' => 'rand',
        ];
        
        $related_posts = new WP_Query($query_args);
        ob_start();

        if ($related_posts->have_posts()) {
        ?>
            <h3 class="title_related">Related Posts </h3>
            <div class="post_content_area">
                <?php 
                while ($related_posts->have_posts()) {
                    $related_posts->the_post();
                    $terms = get_the_category( );
                    //var_dump($terms); 
                ?>    
                <div class="post_content">
                        <div class="title"> <a href="<?php  get_permalink(); ?>"> <?php echo the_title(); ?> </a></div>
                        <div class="Thumbnail">  <?php  the_post_thumbnail( 'medium' );  ?> </div>
                        <div class="cat-title">
                            <ul class="taxonomy">
                                <?php foreach($terms as $cd){ ?>
                                <li> <a href="#" class="tags"> <?php echo $cd->cat_name; ?>  </a></li>
                                <?PHP } ?>
                            </ul>    
                        </div>
                        <div class="content">  <?php echo wp_trim_words(get_the_content(), 20, '...');  ?> </div>
                        <a href="<?php  get_permalink(); ?>" class="button_more">View Details</a>
                </div>
                <?php 
                }
                wp_reset_postdata();
                ?>
            </div>    
        <?php
        }
        $html = ob_get_clean();
        $content .= $html;
        return $content;
    }

    
}


new target_related_posts();