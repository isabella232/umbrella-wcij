<?php
// This site is an INN Member
if ( !defined( 'INN_MEMBER' ) ) {
    define( 'INN_MEMBER', true) ;
}

// This site is hosted by INN
if ( !defined( 'INN_HOSTED' ) ) {
    define( 'INN_HOSTED', true) ;
}

/**
 * Largo APIs
 */
require_once( get_template_directory() . '/largo-apis.php' );

/**
 * Misc includes
 */
$includes = array(
	'/inc/custom-post-types.php',
	'/inc/metaboxes.php'
);

// Perform load
foreach ( $includes as $include ) {
	require_once( get_stylesheet_directory() . $include );
}

/**
 * Include compiled style.css
 */
function child_stylesheet() {
	wp_dequeue_style( 'largo-child-styles' );
	$suffix = ( LARGO_DEBUG )? '' : '.min';
	wp_enqueue_style( 'mstoday', get_stylesheet_directory_uri() . '/css/child' . $suffix . '.css' );
}
add_action( 'wp_enqueue_scripts', 'child_stylesheet', 20 );

/**
 * register an extra image size used for cat/tax archive pages
 */
add_image_size( 'rect_thumb', 800, 600, true );

/**
 * add a widget area like the "article bottom" area but at the bottom of blog posts
 */
function wcij_register_sidebars() {
	register_sidebar( array(
		'name' 			=> __( 'Blog Post Bottom', 'largo' ),
		'id' 			=> 'blog-post-bottom',
		'description' 	=> __( 'A unique widget area for the bottom of blog posts (as opposed to articles)', 'largo' ),
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>',
	) );
}
add_action( 'widgets_init', 'wcij_register_sidebars' );

/**
 * allow shortcodes and oembed in text widgets
 */
add_filter( 'widget_text', 'do_shortcode', 20 );
add_filter( 'widget_text', array( $wp_embed, 'autoembed'), 8 );

/**
 * Enqueue JS
 */
function largo_child_enqueue() {
	wp_enqueue_script( 'largo-child', get_stylesheet_directory_uri() . '/js/largo-child.js', array('jquery') );
}
add_action( 'wp_enqueue_scripts', 'largo_child_enqueue' );

/**
 * Don't strip links surrounding images
 */
function wcij_after_setup_theme() {
	remove_filter( 'the_content', 'largo_attachment_image_link_remove_filter');
}
add_action('after_setup_theme', 'wcij_after_setup_theme', 99);

/**
 * Add typekit
 */
function wcij_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/dpf3ziv.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'wcij_typekit' );

function wcij_tinymce_allowed_tags() {
	global $allowedposttags;
	$allowedposttags['script'] = array(
		'type' => array(),
		'src' => array()
	);
}
add_action( 'init', 'wcij_tinymce_allowed_tags' );

function wcij_mip_metrics_tag() { ?>
	<!-- MIP Google Tag Manager -->
	<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-W8RSCN"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','mipData','GTM-W8RSCN');</script>
	<!-- End MIP Google Tag Manager -->
<?php
}
add_action( 'wp_head', 'wcij_mip_metrics_tag' );

/**
 * Header actions for tagline
 * @since Largo 0.5.5
 */
function wcij_largo_header_before_largo_header() {
	if ( is_front_page() || is_home() ) {
		?>
			<h3>Protect the Vulnerable <span>&#183;</span> Expose Wrongdoing <span>&#183;</span> Seek Solutions</h3>
		<?php
	}
}
add_action( 'largo_header_before_largo_header', 'wcij_largo_header_before_largo_header' );

/**
 * Header actions for newsletter signup form
 * @since Largo 0.5.5
 */
function wcij_largo_header_after_largo_header() {
	?>
	<p itemprop="description">Produced by the <strong>Wisconsin Center for Investigative Journalism</strong></p>

	<?php
	if ( is_front_page() || is_home() ) {
	?>
	<div class="newsletter-signup">
		<span class="date"><?php echo date('F j, Y', time()); ?></span>
		<span class="city">Madison, Wisconsin</span>
		<form action="http://wisconsinwatch.us4.list-manage.com/subscribe/post?u=91b0dfab9d494b66c92b76777&amp;id=d7ab6931a6" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
			<label>Subscribe to our free newsletter</label>
			<fieldset>
				<input required type="email" value="" name="EMAIL" class="required email_address" id="mce-EMAIL" placeholder="Email address">
				<input required type="text" value="" name="FNAME" class="required first_name" id="mce-FNAME" placeholder="First name">
				<input required type="text" value="" name="LNAME" class="required last_name" id="mce-LNAME" placeholder="Last name">
				<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn submit">
				<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
				<div style="position: absolute; left: -5000px;"><input type="text" name="b_91b0dfab9d494b66c92b76777_d7ab6931a6" tabindex="-1" value=""></div>
				<div class="error"></div>
			</fieldset>
		</form>
	</div>
	<?php
	}
}
add_action( 'largo_header_after_largo_header', 'wcij_largo_header_after_largo_header' );