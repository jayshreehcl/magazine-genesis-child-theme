<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'WPVKP - Best Magazine MIN' );
define( 'CHILD_THEME_URL', 'http://wpvkp.com/' );
define( 'CHILD_THEME_VERSION', '0.1' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'default-text-color'     => '000000',
	'header-selector'        => '.site-title a',
	'header-text'            => false,
	'height'                 => 90,
	'width'                  => 380,
) );

// Remove the site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

// Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'custom_scripts_styles_mobile_responsive' );
function custom_scripts_styles_mobile_responsive() {

	wp_enqueue_script( 'responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_style( 'dashicons' );

}

// Customize the previous page link
add_filter ( 'genesis_prev_link_text' , 'sp_previous_page_link' );
function sp_previous_page_link ( $text ) {
	return g_ent( '&laquo; ' ) . __( 'Previous Page', CHILD_DOMAIN );
}

// Customize the next page link
add_filter ( 'genesis_next_link_text' , 'sp_next_page_link' );
function sp_next_page_link ( $text ) {
	return __( 'Next Page', CHILD_DOMAIN ) . g_ent( ' &raquo; ' );
}

/**
 * Remove Genesis Page Templates
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/remove-genesis-page-templates
 *
 * @param array $page_templates
 * @return array
 */
function be_remove_genesis_page_templates( $page_templates ) {
	unset( $page_templates['page_archive.php'] );
	unset( $page_templates['page_blog.php'] );
	return $page_templates;
}
add_filter( 'theme_page_templates', 'be_remove_genesis_page_templates' );


//* Download Button
function down($atts, $content = null) {
 extract( shortcode_atts( array(
          'url' => '#'
), $atts ) );
return '<a class="down" rel="nofollow" target="_blank" href="'.$url.'"><p>' . do_shortcode($content) . '</p></a>';
}
add_shortcode('down', 'down');

//* Custom Size Of Gravatars
add_filter( 'avatar_defaults', 'custom_default_avatar' );

function custom_default_avatar ( $avatar_defaults ) {
    
    $new_avatar_url = get_stylesheet_directory_uri() . '/images/gravatar.png';
    $avatar_defaults[$new_avatar_url] = 'New Default Gravatar';
    return $avatar_defaults;
}

add_filter('comment_form_default_fields', 'url_filtered');
function url_filtered($fields)
{
if(isset($fields['url']))
unset($fields['url']);
return $fields;
}

	if( !function_exists("disable_comment_author_links")){
		function disable_comment_author_links( $author_link ){
			return strip_tags( $author_link );
		}
		add_filter( 'get_comment_author_link', 'disable_comment_author_links' );
	}

	
	add_filter( 'genesis_comment_list_args', 'custom_genesis_comment_list_args' );

	
/**
* Callback for Genesis 'genesis_comment_list_args' filter.
*
* Override the comment callback and implement custom comment.
*
* @package Genesis
* @category comments
* @author Ryan Meier <rfmeier@gmail.com>
*
* @link http://codex.wordpress.org/Function_Reference/wp_list_comments
*
* @param array $args arguments for wp_list_comments()
* @return array $args arguments for wp_list_comments()
*/
function custom_genesis_comment_list_args( $args ){
// set out custom function
$args['callback'] = 'custom_genesis_comment_callback';
// return the arguments
return $args;
}
/**
* Custom callback for WordPress wp_list_comments() argument
*
* Still using the default Genesis comment conent, remove the link for the
* comment date.
*
* @package Genesis
* @category comments
* @author Ryan Meier <rfmeier@gmail.com>
*
* @link http://codex.wordpress.org/Function_Reference/wp_list_comments
*
* @param object $comment current comment object
* @param array $args arguments for the comment
* @param int $depth depth of replies
*/
function custom_genesis_comment_callback( $comment, $args, $depth ){
$GLOBALS['comment'] = $comment; ?>
 
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
 
<?php do_action( 'genesis_before_comment' ); ?>
 
<div class="comment-header">
<div class="comment-author vcard">
<?php echo get_avatar( $comment, $size = $args['avatar_size'] ); ?>
<?php printf( __( '<cite class="fn">%s</cite> <span class="says">%s:</span>', 'genesis' ), get_comment_author_link(), apply_filters( 'comment_author_says_text', __( 'says', 'genesis' ) ) ); ?>
</div><!-- end .comment-author -->
 
<div class="comment-content">
<?php if ( $comment->comment_approved == '0' ) : ?>
<p class="alert"><?php echo apply_filters( 'genesis_comment_awaiting_moderation', __( 'Your comment is awaiting moderation.', 'genesis' ) ); ?></p>
<?php endif; ?>
 
<?php comment_text(); ?>
</div><!-- end .comment-content -->
 
<div class="reply">
<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
</div>
 
<?php do_action( 'genesis_after_comment' ); 
}

//* Customize the post info function
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter($post_info) {
if ( !is_page() ) {
$post_info = 'Authored By&nbsp;[post_author_posts_link]';
return $post_info;
}} 

//* This cheat will help you to customize genesis footer credit area.
//* For this cheat to work it is necessary that your child theme is HTML5 enabled.
add_filter('genesis_footer_creds_text', 'wpvkp_footer_creds_filter');
function wpvkp_footer_creds_filter( $editthecredit ) {
$editthecredit = 'Copyright &copy; &middot; <a href="http://wpvkp.com/" rel="nofollow" target="_blank">Free & Premium WordPress Theme</a>';
return $editthecredit ;
}
//*Please refer to the original post at my blog for full explaination of this snippet and other cheats

// Add Read More Link to Excerpts
add_filter('excerpt_more', 'get_read_more_link');
add_filter( 'the_content_more_link', 'get_read_more_link' );
function get_read_more_link() {
   return '<p><a class="more-link" href="' . get_permalink() . '" rel="nofollow">Read&nbsp;More&nbsp;&nbsp;&#8594;  </a></p>';
}

//* Remove entry meta in entry footer
add_action( 'genesis_before_entry', 'magazine_remove_entry_meta' );
function magazine_remove_entry_meta() {
	
	//* Remove if not single post
	if ( ! is_single() ) {
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
		remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );
	}

}

/** Reposition breadcrumbs in genesis */
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
add_action( 'genesis_after_post_content', 'genesis_do_breadcrumbs' );

//* Do NOT include the opening php tag shown above. Copy the code shown below.
//* Modify the length of post excerpts
add_filter( 'excerpt_length', 'sp_excerpt_length' );
function sp_excerpt_length( $length ) {
return 50; // pull first 50 words
} 
