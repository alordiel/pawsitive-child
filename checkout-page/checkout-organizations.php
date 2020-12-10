<?php
$organizations = get_posts( [
	'post_type'      => 'organization',
	'post_status'    => 'publish',
	'posts_per_page' => -1
] );

if ( ! empty($organizations)) {

	echo '<div class="donation-organization-container">';

	foreach ($organizations as $post) {
		$small_image_id = get_post_meta($post->ID, 'small_logo', true);
		$small_image = wp_get_attachment_image_url($small_image_id,'thumbnail');
		$large_image_id = get_post_meta($post->ID, 'large_logo', true);
		$large_image = wp_get_attachment_image_url($small_image_id,'full');
		$link = get_permalink($post->ID);
		$excerpt = addslashes(get_the_excerpt($post->ID));
		$statistics = get_post_meta( $post->ID, 'pwa_statistics', true );
		$collected = number_format( (int) $statistics['received'], 2) . '€';


		echo '<div class="single-organisation" id="organization-' . $post->ID .'">';
		echo   '<img class="organization-logo-ds" src="'. $small_image .'" alt="' . $post->post_title . '"
					data-id="' . $post->ID .'"
					data-title="' . $post->post_title .'"
					data-image="' . $large_image .'"
					data-link="' . $link .'"
					data-collected ="'.$collected.'"
					data-excerpt="' . $excerpt .'">';
		echo '</div>';
	}

	echo '</div>';
	// Modal for details of the organizations
	echo '<div class="modal fade" tabindex="-1" id="organization-short-info">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div class="modal-body">
				  	<p class="organization-subtitle">' . __('Total collected', 'paw') .': <span class="total-collected"></span></p>
					<p class="modal-excerpt"></p>
					<a class="modal-link-view-more" href="">(' . __( 'see more', 'paw' ) . ')</a>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">' . __( 'Close', 'paw' ) . '</button>
					<button type="button" class="btn btn-primary">' . __( 'Select', 'paw' ) . '</button>
				  </div>
				</div>
			  </div>
			</div>';


}
