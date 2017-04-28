<?php
/**
 * This file init only admin functionality.
 *
 * @link www.italystrap.com
 * @since 4.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\Core;

if ( ! is_admin() ) {
	return;
}

$autoload_concrete = array_merge( $autoload_concrete, array(
	'ItalyStrap\Admin\Tinymce\Editor',
	'ItalyStrap\Admin\Metabox\Register',
	'ItalyStrap\Core\User\Contact_Methods',
) );

require( TEMPLATEPATH . '/admin/functions.php' );

/**
 * TinyMCE Editor in Category description
 */
$editor = $injector->make( '\ItalyStrap\Admin\Category\Editor' );

/**
 * Add fields to widget areas
 * The $register_metabox is declared in plugin
 */
if ( isset( $register_metabox ) ) {
	add_action( 'cmb2_admin_init', array( $register_metabox, 'register_widget_areas_fields' ) );
}
