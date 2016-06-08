<?php
/**
 * For file size @see image_size.php
 *
 * @todo Upload default image on switch theme
 *       (da usare invece della fallback
 *       dell'immagine nella cartella img)
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/switch_theme
 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/after_switch_theme
 * @link https://wordpress.org/plugins/auto-upload-images/
 *
 * @todo Al momento dalla versione 3.1 ho cambiato solo il path delle varie
 *       immagini prese dal nuovo theme customizer in futuro eventualmente
 *       migliorare queste funzioni in base alle varie situazioni,
 *       per esempio se non ci sono immagini non ritornare nessun valore
 * @todo L'immagine di default dovrebbe anche essere creata per
 *       le misure varie misure impostate
 *
 * @package ItalyStrap
 */

/**
 * Get the custom image URL from customizer
 * @param  string $key     Custom image array's key name
 *                         default_image
 *                         logo
 *                         default_404.
 * @param  string $default SRC of default image url.
 * @return string          Return the image URL if exist
 */
function italystrap_get_the_custom_image_url( $key = null, $default = null ) {

	if ( ! $key )
		return;

	global $italystrap_theme_mods;

	if ( empty( $italystrap_theme_mods[ $key ] ) )
		return;

	if ( is_numeric( $italystrap_theme_mods[ $key ] ) )
		$image = wp_get_attachment_url( $italystrap_theme_mods[ $key ] );

	elseif ( $italystrap_theme_mods[ $key ] )
		$image = $italystrap_theme_mods[ $key ];

	else $image = $default;

	return esc_url( $image );

}

/**
 * Return the defaul image
 * Useful for Opengraph
 * @return string Return url of default image
 * @deprecated 3.1 Funzione deprecata in favore di italystrap_get_the_custom_image_url()
 */
function italystrap_get_default_image() {

	global $italystrap_theme_mods;

	if ( empty( $italystrap_theme_mods['default_image'] ) )
		return;

	if ( is_int( $italystrap_theme_mods['default_image'] ) )
		$default_image = wp_get_attachment_url( $italystrap_theme_mods['default_image'] );

	elseif ( $italystrap_theme_mods['default_image'] )
		$default_image = $italystrap_theme_mods['default_image'];

	else $default_image = ITALYSTRAP_PARENT_PATH . '/img/italystrap-default-image.png';

	return esc_url( $default_image );

}

/**
 * Echo image url, if exist get the post image, else get the default image
 * @return string Echo image url
 */
function italystrap_thumb_url(){

	if ( has_post_thumbnail() ) {

		$post_thumbnail_id = get_post_thumbnail_id();
		$image_attributes = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
		echo $image_attributes[0]; 
	
	}
	else
		echo italystrap_get_default_image();
	
}



/**
 * Get the logo url
 * @return string Return logo url
 */
function italystrap_logo(){

	// global $italystrap_theme_mods;

	// if ( empty( $italystrap_theme_mods['logo'] ) )
	// 	return;

	// if ( is_numeric( $italystrap_theme_mods['logo'] ) )
	// 	$logo = wp_get_attachment_url( $italystrap_theme_mods['logo'] );
	// elseif ( $italystrap_theme_mods['logo'] )
	// 	$logo = $italystrap_theme_mods['logo'];
	// else
	// 	$logo = ITALYSTRAP_PARENT_PATH . '/img/italystrap-logo.jpg';

	// return esc_url( $logo );
	// 
	// 
	echo italystrap_get_the_custom_image_url( 'logo', ITALYSTRAP_PARENT_PATH . '/img/italystrap-logo.jpg' );

}

//funzione per estrapolare le url da gravatar
/**
 * Get the Gravatar URL
 * @param  string $url [description]
 * @return string      Return Gravatar url
 */
function estraiUrlsGravatar($url){

	$url_pulito = substr($url,17,-56);
	return $url_pulito; 
}

/**
 * Retrieve the avatar url
 *
 * @since 1.8.7
 *
 * @link http://wordpress.stackexchange.com/questions/59442/how-do-i-get-the-avatar-url-instead-of-an-html-img-tag-when-using-get-avatar
 *
 * @param string $email email address of Author or Author comment
 * @return string Avatar url
 */
function italystrap_get_avatar_url( $email ){

	if ( !$email )
		return;

	$hash = md5( strtolower( trim ( $email ) ) );
	return 'http://gravatar.com/avatar/' . $hash;

}

/**
 * Retrieve the avatar for a user who provided a user ID or email address.
 *
 * @since 1.8.7
 *
 * @param int|string|object $id_or_email A user ID,  email address, or comment object
 * @param int $size Size of the avatar image
 * @param string $default URL to a default image to use if no avatar is available
 * @param string $alt Alternative text to use in image tag. Defaults to blank
 * @param string $class Add custom CSS class for avatar
 * @return string <img> tag for the user's avatar
 */
function italystrap_get_avatar(  $id_or_email, $size = '96', $default = '', $alt = false, $class = '' ){

	$avatar = get_avatar( $id_or_email, $size, $default, $alt );

	if ($class)
		$avatar = str_replace('photo', "photo $class" , $avatar);

	return $avatar;
}

/**
 *
 * Add img-responsive css class when new images are upload
 * For old image install Search regex plugin and replace '<img class="' to '<img class="img-responsive ' without apostrophe mark ;-)
 */
function italystrap_add_image_class($class){
	$class .= ' img-responsive';
	return $class;
}
add_filter('get_image_tag_class','italystrap_add_image_class');

/**
 * For other image class see cleanup.php from line 142 to line 189
 * There is thumbnail class for attachment and img-responsive and thumbnail for figure and figure caption
 */

/**
 * Add a favicons to site
 * @link http://www.robertoiacono.it/aggiungere-favicon-wordpress-come-perche/
 */
function ri_wp_favicon(){
	_deprecated_function( __FUNCTION__, '3.1' );

	$favicon = false;

	if ( $GLOBALS['italystrap_options']['favicon'] )
		$favicon = $GLOBALS['italystrap_options']['favicon'];

	elseif ( is_child_theme() && !$favicon ) {

		global $pathchild;
		$favicon = $pathchild . '/img/favicon.ico';

	} else {

		$favicon = ITALYSTRAP_PARENT_PATH . '/img/favicon.ico';

	}

	echo '<link rel="shortcut icon" type="image/x-icon" href="' . $favicon . '" />';
}
// add_action('wp_head', 'ri_wp_favicon');

/**
 * Get the image for 404 page
 * The image is set in the customizer
 * Default /img/404.jpg
 *
 * @link https://wordpress.org/support/topic/need-to-get-attachment-id-by-image-url
 * @see https://codex.wordpress.org/Function_Reference/wp_get_attachment_metadata
 * @return string Return html image string for 404 page
 */
function italystrap_get_404_image( $class = '' ){

	global $italystrap_theme_mods;

	if ( empty( $italystrap_theme_mods['default_404'] ) )
		return;

	// $image_404_url = ITALYSTRAP_PARENT_PATH . '/img/404.jpg';
	$image_404_url = $italystrap_theme_mods['default_404'];
	$width = 848;
	$height = 477;
	$alt = __( 'Image for 404 page', 'ItalyStrap' ) . ' ' . esc_attr( GET_BLOGINFO_NAME );

	if ( is_int( $italystrap_theme_mods['default_404'] ) ){

		// global $wpdb;

		// $image_404_url = esc_attr( $italystrap_theme_mods['default_404'] );
		// $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_404_url'";
		// $id = $wpdb->get_var($query);
		// $meta = wp_get_attachment_metadata( $id );var_dump($meta);
		// $width = ( isset( $meta['width'] ) ) ? $meta['width'] : '' ;
		// $height = ( isset( $meta['height'] ) ) ? $meta['height'] : '' ;
		// $alt = trim( strip_tags( get_post_meta($id, '_wp_attachment_image_alt', true) ) );
		$size = apply_filters( '404-image-size', 'article-thumb' );
		$id = $italystrap_theme_mods['default_404'];
		$meta = wp_get_attachment_image_src( $id, $size );
		$image_404_url = $meta[0];
		$width = esc_attr( $meta[1] );
		$height = esc_attr( $meta[2] );

	}

	$html = '<img width="' . $width . 'px" height="' . $height . 'px" src="' . esc_url( $image_404_url ) . '" alt="' . $alt . '" class="' . $class . '">';

	$html = apply_filters( 'italystrap-404-image', $html );

	/**
	 * If is active ItalyStrap plugin
	 */
	if ( function_exists( 'italystrap_apply_lazyload' ) )
		return italystrap_get_apply_lazyload( $html );
	else
		return $html;

}

/**
 * Get the attachment ID from image url
 *
 * @todo Get the ID from image resized (eg: thumbnail)
 * 
 * @link https://wordpress.org/support/topic/need-to-get-attachment-id-by-image-url
 * @param  string $url The url of image
 * @return int         The ID of the image
 */
function italystrap_get_ID_image_from_url( $url ){

	global $wpdb;

	$query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$url'";
	$id = $wpdb->get_var( $query );

	return absint( $id );

}

/**
 * Questa funzione pesca l'immagine correlata all'articolo (attachment) e la visualizza
 * se non c'è visualizza un'immagine di default inserita nella cartella img del tema
 * 
 * Viene inserita l'ultima immagine associata ad un post, per visualizzarne un'altra
 * bisogna cancellare la prima e inserire la nuova.
 *
 * @todo In questa funzione creare una if per stampare o ritornare il codice, per farlo aggiungere un parametro alla funzione o all'array, esempio:
 * if (true)
 * 	return
 * else
 * 	echo
 *
 * @todo Interessante funzionalità potrebbe essere quella di avere più immagini di default variabili.
 * 
 * @param $postID ID del post nel loop
 * @param $size Il nome della thumb dichiarate in add_image_size()
 * @param $default_width Deve essere un numero intero corrispondente alla larghezza dell'immagine di default
 * @param $default_height Deve essere un numero intero corrispondente all'altezza' dell'immagine di default
 */
function italystrap_get_the_post_thumbnail( $postID = null, $size = 'post-thumbnail' , $attr = array(),  $default_width = 0, $default_height = 0, $default_image = '' ) {

	/**
	 * If has feautured image return that
	 */
	if ( has_post_thumbnail() )
		return get_the_post_thumbnail( $postID, $size, $attr );


	$postID = ( null === $postID ) ? get_the_ID() : $postID;

	/**
	 * The value to return
	 * @var string
	 */
	$image_html = '';

	/**
	 * Array arguments for get_posts()
	 * @var array
	 */
	$args = array(
		'numberposts' => 1,
		'post_parent' => $postID,
		'post_type' => 'attachment',
		// 'post_status' => null,
		'post_mime_type' => 'image',
		'order' => 'ASC',
	);

	/**
	 * Get the post object
	 * @var object
	 */
	$first_images = get_posts( $args );

	/**
	 * Text alternative for image
	 * @var string
	 */
	$alt = ( empty($first_images[0]->post_title) ) ? get_the_title() : $first_images[0]->post_title ;

	/**
	 * Set the default alt value if $attr['alt'] is empty
	 */
	$attr['alt'] = ( !empty( $attr['alt'] ) ) ? $attr['alt'] : $alt;

	/**
	 * Set the default class value if $attr['class'] is empty
	 */
	$attr['class'] = ( !empty( $attr['class'] ) ) ? $attr['class'] : 'center-block img-responsive';

	$default_image = italystrap_get_the_custom_image_url( 'default_image' );
	/**
	 * Fallback image
	 * @var string
	 */
	$default_image = '<img src="' . $default_image . '" width="' . $default_width . 'px" height="' . $default_height . 'px" alt="' . $attr['alt'] . '" class="' . $attr['class'] . '">';

	/**
	 * Set the default image
	 * @var string
	 */
	$image_html = $default_image;

	if ( $first_images ) {

		/**
		 * Get the attachment value
		 * @var array
		 */
		$image_attributes = wp_get_attachment_image_src( $first_images[0]->ID, $size );

		/**
		 * $default_width imposta la larghezza di default dell'immagine
		 * Se l'immagine nel post è più piccola del 10% la mostra altrimenti no.
		 */
		if ( $image_attributes[1] >= $default_width / 1.1 )
			$image_html = '<img src="' . $image_attributes[0] . '" width="' . $image_attributes[1] . '" height="' . $image_attributes[2] . '" alt="' . $attr['alt'] . '" class="' . $attr['class'] . '">';

	}

	if ( function_exists( 'italystrap_get_apply_lazyload' ) )
		$image_html = italystrap_get_apply_lazyload( $image_html ); 

	return $image_html;

}

function italystrap_the_post_thumbnail( $size = 'post-thumbnail' , $attr = '',  $default_width = '', $default_height = '' ) {

	echo italystrap_get_the_post_thumbnail( null, $size, $attr,  $default_width, $default_height );

}


/*
* Display Image from the_post_thumbnail or the first image of a post else display a default Image
* Chose the size from "thumbnail", "medium", "large", "full" or your own defined size using filters.
* USAGE: <?php echo my_image_display(); ?>
*/
 
// function my_image_display($size = 'article-thumb') {
// 	global $pathchild;
// 	if (has_post_thumbnail()) {
// 		$image_id = get_post_thumbnail_id();
// 		$image_url = wp_get_attachment_image_src($image_id, $size);
// 		$image_url = $image_url[0];
// 	} else {
// 		global $post, $posts;
// 		$image_url = '';
// 		ob_start();
// 		ob_end_clean();
// 		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
// 		$image_url = $matches [1] [0];
// 		//Defines a default image
// 		if(empty($image_url)){
// 			$image_url = $pathchild . "/img/default.jpg";
// 		}
// 	}
// 	return $image_url;
// }

/**
 * Add Bootstrap thumbnail styling to images with captions
 * Use <figure> and <figcaption>
 *
 * @see https://developer.wordpress.org/reference/functions/img_caption_shortcode/
 *
 * @link http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
function italystrap_new_caption_style( $output, array $attr, $content ) {

	if ( is_feed() ) {
		return $output;
	}

	if ( ! isset( $attr ) ) {
		return $output;
	}

	$defaults = array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => '',
		'caption' => '',
		'class'   => '',
	);

	$attr = shortcode_atts( $defaults, $attr, 'caption' );

	$attr['width'] = (int) $attr['width'];

	/**
	 * If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
	 */
	if ( $attr['width'] < 1 || empty( $attr['caption'] ) ) {
		return $content;
	}

	$figure_attr = array(
		'class'	=> 'img-responsive wp-caption ' . esc_attr( $attr['align'] ),
		'style'	=> 'width: ' . esc_attr( $attr['width'] ) . 'px',
		);

	if ( ! empty( $attr['id'] ) ) {
		$figure_attr['id'] = 'id="' . esc_attr( $attr['id'] ) . '"';
	}

	/**
	 * Filter the figure attribute
	 *
	 * @var array
	 */
	$figure_attr = apply_filters( 'italystrap_img_caption_shortcode_figure_attr', $figure_attr );

	$html_figure_attributes = ItalyStrap\Core\get_html_tag_attr( $figure_attr );

	$output  = '<figure ' . $html_figure_attributes .'>';
	$output .= do_shortcode( $content );

	$figcaption_attr = array(
		'class'	=> 'caption wp-caption-text',
		);

	/**
	 * Filter the figcaption attribute
	 *
	 * @var array
	 */
	$figcaption_attr = apply_filters( 'italystrap_img_caption_shortcode_figcaption_attr', $figcaption_attr );

	$html_figcaption_attributes = ItalyStrap\Core\get_html_tag_attr( $figcaption_attr );

	$output .= '<figcaption ' . $html_figcaption_attributes . '>' . $attr['caption'] . '</figcaption></figure>';

	return $output;

}
add_filter( 'img_caption_shortcode', 'italystrap_new_caption_style', 10, 3 );