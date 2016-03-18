<?php
/**
 * The template used for displaying page content
 *
 * @package ItalyStrap
 * @since 1.0.0
 */

namespace ItalyStrap;

$layout_settings = (array) apply_filters( 'italystrap_layout_settings', array() );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php if ( ! in_array( 'hide_title', $layout_settings, true ) ) : ?>
	<header class="page-header entry-header">
		<h1 class="entry-title">
			<a itemprop="url" href="<?php the_permalink(); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
				<span itemprop="headline">
					<?php the_title(); ?>
				</span>
			</a>
		</h1>
	</header>
<?php endif; ?>

<?php if ( ! in_array( 'hide_meta', $layout_settings, true ) ) : ?>
	<footer class="entry-footer">
		<?php get_template_part( 'template/meta' ); ?>
	</footer>
<?php endif; ?>

<?php if ( is_preview() ) : ?>
	<div class="alert alert-info">  
		<?php esc_attr_e( '<strong>Note:</strong> You are previewing this post. This post has not yet been published.', 'ItalyStrap' ); ?>  
	</div>  
<?php endif; ?>

	<section class="entry-content">

		<?php if ( has_post_thumbnail() && ! in_array( 'hide_thumb', $layout_settings, true )  ) { ?>
			<figure class="<?php echo esc_attr( apply_filters( 'italystrap-figure-thumb-class', 'thumbnail' ) ); ?>">
				<?php
				if ( is_page_template( 'full-width.php' ) ) {
					$thumb_size = 'full-width';
				} else {
					$thumb_size = 'article-thumb';
				}
				the_post_thumbnail(
					$thumb_size,
					array(
						'class' => 'center-block img-responsive',
						'alt'   => trim( strip_tags( get_post_meta( get_post_thumbnail_id( $post->ID ), '_wp_attachment_image_alt', true ) ) ),
						'itemprop'	=> 'image',
					)
				);
				?>
			</figure>
		<?php } ?>

		<div  itemprop="articleBody"><?php the_content(); ?></div>
		<span class="clearfix"></span>
		<p class="sr-only"><?php esc_attr_e( 'Last edit:', 'ItalyStrap' ); ?> <time datetime="<?php the_modified_time( 'Y-m-d' ) ?>" itemprop="dateModified"><?php the_modified_time( 'd F Y' ) ?></time></p>
		<span class="clearfix"></span>
		<?php
		/**
		 * Arguments for edit_post_link()
		 *
		 * @var array
		 */
		$args = array(
			/* translators: %s: Name of current post */
			'link_text'	=> __( 'Edit<span class="screen-reader-text"> "%s"</span>', 'ItalyStrap' ),
			'before'	=> '<span class="btn btn-sm btn-primary">',
			'after'		=> '</span>',
			);
		$args = apply_filters( 'italystrap_edit_post_link_args', $args );

		edit_post_link(
			sprintf(
				$args['link_text'],
				get_the_title()
			),
			$args['before'],
			$args['after']
		);
		?>
	</section><!-- /.entry-content -->

	<?php echo italystrap_ttr_wc(); // XSS ok.?>
	<span class="clearfix"></span>

	<?php
	/**
	 * Display author info box
	 */
	if ( ! in_array( 'hide_author', $layout_settings, true ) ) {
		get_template_part( 'template/content', 'author-info' );
	}
	?>

	<meta itemprop="interactionCount" content="UserComments:<?php comments_number( 0, 1, '%' );?>" />
</article>
