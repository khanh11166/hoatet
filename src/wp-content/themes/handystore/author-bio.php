<?php
/**
 * The template for displaying Author bios
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

<?php 
    global $wpdb;

    $user_mail = get_the_author_meta( 'user_email' );

	$count = $wpdb->get_var( $wpdb->prepare(
		"SELECT COUNT(comment_ID) FROM $wpdb->comments WHERE comment_author_email = %s",
		$user_mail
	) );
?>

<div class="author-info">
	<div class="author-avatar">
		<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'plumtree_author_bio_avatar_size', 70 ) ); ?>
	</div>

	<div class="author-description">
		<h2 class="author-title"><?php printf( __( 'By <a class="author-link" rel="author" href="%s">%s</a>', 'plumtree' ), esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_the_author() ); ?></h2>
		<div class="author-total-comments"><i class="fa fa-comments-o"></i><?php echo esc_attr($count); ?></div>

		<div class="author-bio">
			<?php the_author_meta( 'description' ); ?>
		</div>
	</div>

	<?php if (function_exists('pt_output_author_contacts')) { pt_output_author_contacts(); } ?>
</div>
