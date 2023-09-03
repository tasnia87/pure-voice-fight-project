<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */
global $product;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! comments_open() ) {
	return;
}

?>
<div id="reviews" class="woocommerce-Reviews">
    <div id="comments">
        <h2 class="woocommerce-Reviews-title">
            <?php
            $count = $product->get_review_count();
            if ( $count && wc_review_ratings_enabled() ) {
                /* translators: 1: reviews count 2: product name */
                $reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
                echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product ); // WPCS: XSS ok.
            } else {
                esc_html_e( 'Reviews', 'woocommerce' );
            }
            ?>
        </h2>

        <?php if ( have_comments() ) : ?>
            <ol class="commentlist">
                <?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
            </ol>

            <?php
            if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
                echo '<nav class="woocommerce-pagination">';
                paginate_comments_links(
                    apply_filters(
                        'woocommerce_comment_pagination_args',
                        array(
                            'prev_text' => '&larr;',
                            'next_text' => '&rarr;',
                            'type'      => 'list',
                        )
                    )
                );
                echo '</nav>';
            endif;
            ?>
        <?php else : ?>
            <p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'woocommerce' ); ?></p>
        <?php endif; ?>
    </div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

		<div id="review_form_wrapper">
			<div id="review_form">
				<?php
					$commenter = wp_get_current_commenter();
					global $current_user;
					$comment_form = array(
						'title_reply'          => have_comments() ? __( 'Your Reviews', 'cactusthemes' ) : '',
						'title_reply_to'       => __( 'Leave a Reply to %s', 'woocommerce' ),
						'comment_notes_before' => '',
						'comment_notes_after'  => '',
						'fields'               => array(
							'author' => '<p class="comment-form-author">
										' .
							            '<p class="cm-text"><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" placeholder="' . __( 'Your Name', 'cactusthemes' ) . '"/></p></p>',
							'email'  => '<p class="comment-form-email">' .
							            '<p class="cm-text"><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" placeholder="' . __( 'Your Email', 'cactusthemes' ) . '"/></p></p>',
						),
						'label_submit'  => __( 'Submit', 'woocommerce' ),
						'logged_in_as'  => '',
						'comment_field' => ''
					);

					if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
						$comment_form['comment_field'] = '<p class="comment-form-rating rate-uni"><select name="rating" id="rating">
							<option value="">' . __( 'Rate&hellip;', 'woocommerce' ) . '</option>
							<option value="5">' . __( 'Perfect', 'woocommerce' ) . '</option>
							<option value="4">' . __( 'Good', 'woocommerce' ) . '</option>
							<option value="3">' . __( 'Average', 'woocommerce' ) . '</option>
							<option value="2">' . __( 'Not that bad', 'woocommerce' ) . '</option>
							<option value="1">' . __( 'Very Poor', 'woocommerce' ) . '</option>
						</select></p>';
					}
					$comment_form['comment_field'] .= '<p class="comment-form-comment">
					<p class="current-user">'.get_avatar( $current_user->ID, 60 ).'</p>
					<p class="cm-text">
					<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" onblur="if(this.value == \'\') this.value = \''.__('Your Message','cactusthemes').'\';" onfocus="if(this.value == \''.__('Your Message','cactusthemes').'\') this.value = \'\'; jQuery(\'.comment-form-tm .collapse\').addClass(\'in\');">'.__('Your Message','cactusthemes').'</textarea>
					</p>
					</p>';

					comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
		</div>

	<?php else : ?>

		<p class="woocommerce-verification-required"><?php _e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
</div>
<?php
if ( is_user_logged_in() ) {
?>
	<style type="text/css">
    .woocommerce #review_form #respond p.current-user, .woocommerce-page #review_form #respond p.current-user{ top:auto}
    </style>
<?php	
}