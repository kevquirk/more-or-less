<?php get_header(); ?>

<section id="primary" class="container main_content_area">
	<!-- open row and col in case of sidebar layout -->
	<?php $sidebar_width_class = 'no_sidebar_post_single'; ?>

	<?php if (alia_cross_option('alia_post_layout', '', 'fullwidth') == 'sidebar_l'): ?>
		<div class="row post_width_sidebar_row">
			<div class="col8 sidebar_post_content_col">
			<?php $sidebar_width_class = 'sidebar_post_single' ?>
	<?php endif; ?> <!-- end check for post layout -->

			<div class="row full_width_post_single <?php echo esc_attr($sidebar_width_class); ?>">
				<div class="col12">
				<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/post/content', get_post_format() );

					// alia_share_icons();

					if (function_exists('alia_share_icons') && alia_cross_option('alia_show_share_buttons_posts', '', 1)):
						alia_share_icons();
					endif;
					?>

					<?php
					if (alia_cross_option('alia_mailchimp_code')) {
						?>
						<div class="newsletter_susbcripe_form newsletter_susbcripe_form_single">
							<div class="newsletter_icon"><i class="far fa-envelope-open"></i></div>
						<?php
						echo alia_cross_option('alia_mailchimp_code');
						?>
							<script src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
						</div>
						<?php
					}
					?>

					<?php if (alia_cross_option('alia_show_author_box_posts', '', 1) && get_the_author_meta('description')): ?>
					<div class="author_info_container author_single_box">
						<div class="row">
							<div class="author_avatar_col col">
								<?php printf( '<a class="author_avatar_url" href="%1$s">%2$s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_avatar(get_the_author_meta('ID'), 150) ); ?>
							</div>
							<div class="author_info_col col">

								<div class="author_box_info_header">
									<h2 class="author_display_name title"><?php printf( '<a class="author_name_url url fn n" href="%1$s">%2$s</a>', esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_the_author() ); ?></h2>

									<?php
									// show author social icons in header only if no story circles shown
									if (
										function_exists('alia_create_stories') && alia_option('alia_stories_author_box', 1) && function_exists( 'alia_author_social_icons' ) && alia_author_social_icons() != '') {
										if (function_exists( 'alia_author_social_icons' )) {
											echo alia_author_social_icons();
										}
									}
									?>
								</div>

								<div class="author_description">
									<?php the_author_meta('description'); ?>
								</div>
								<?php
								if ( function_exists('alia_create_stories') && alia_option('alia_stories_author_box', 1) ) {
									echo alia_stories_circles(5, '', get_the_author_meta('ID'));
								}else{
									// if no stories, show social icons below text
									if (function_exists( 'alia_author_social_icons' )) {
										echo alia_author_social_icons();
									}
								}
								?>
							</div>
						</div>
					</div>
					<?php
					endif;

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						echo '<div class="comment_container">';
							comments_template();
						echo '</div>';
					endif;


					$posts_not_in = array($post->ID);

					if (alia_cross_option('alia_show_next_post', '', 1)):
						$prev_post = get_previous_post();

						if (!empty( $prev_post )) {

							set_query_var('alia_content_width', 'two_coloumns_list');
							if (alia_cross_option('alia_post_layout', '', 'fullwidth') == 'sidebar_l') {
								set_query_var('alia_content_layout', 'layout_with_sidebar');
							}
							set_query_var('alia_post_position', 'related_posts');
							array_push($posts_not_in, $prev_post->ID);

							$post = $prev_post;
							setup_postdata( $post );
							echo '<div class="read_next_loop_container">';
								echo '<h4 class="read_next_title section_title title">'.esc_attr__('Read Next', 'alia').'</h4>';

								echo '<div class="row two_coloumns_list"><div class="col12">';
									echo '<div class="thepost_row row">';
										get_template_part( 'template-parts/post/content', get_post_format($prev_post->ID) );
									echo '</div>'; // end thepost_row
								echo '</div></div>'; // end two_coloumns_list & col12
							echo '</div>';
							wp_reset_postdata();
						}else{

							// if last post show first post in the blog to loop posts
							$args = array('posts_per_page' => 1, 'ignore_sticky_posts' => 1, 'post__not_in' => $posts_not_in );

							$recent_posts = new WP_Query($args);

							if ($recent_posts->have_posts()):
								echo '<div class="read_next_loop_container">';
									echo '<div class="row two_coloumns_list"><div class="col12">';
									while ($recent_posts->have_posts()) : $recent_posts->the_post();
										set_query_var('alia_content_width', 'two_coloumns_list');
										if ($sidebar_width_class == 'sidebar_post_single') {
											set_query_var('alia_content_layout', 'layout_with_sidebar');
										}
										set_query_var('alia_post_position', 'related_posts');
										array_push($posts_not_in, $post->ID);

										echo '<h4 class="read_next_title section_title title">'.esc_attr__('Read Next', 'alia').'</h4>';
										echo '<div class="thepost_row row">';
											get_template_part( 'template-parts/post/content', get_post_format($post->ID) );
										echo '</div>'; // end thepost_row
									endwhile;
									echo '</div></div>'; // end two_coloumns_list & col12
								echo '</div>'; // end read_next_loop_container

							endif;
							wp_reset_query();
						}
					endif; // end check for alia_show_next_post




				endwhile; // End of the loop.

				?>

				</div><!-- close col12 just inside .full_width_list -->
			</div> <!-- close .full_width_list -->

			<!-- start related posts -->
			<?php
			if (alia_cross_option('alia_show_related_post', '', 1)):
				// start related posts
				$args = array('orderby' => 'rand', 'posts_per_page' => 3, 'ignore_sticky_posts' => 1, 'post__not_in' => $posts_not_in );


				$posts_relation_setting = '';
				if ($posts_relation_setting != '') {
					if ($posts_relation_setting == 'category') {
						$categories = get_the_category($post->ID);
						foreach ( $categories as $category ) {
							if (isset($cats)) {
								$cats .= ','.$category->term_id;
							} else {
								$cats = $category->term_id;
							}
						}
						if (isset($cats) && $cats != '') {
							$args['cat'] = $cats;
						}
					} else if ($posts_relation_setting == 'tag') {
						$posttags = get_the_tags($post->ID);
						if ($posttags) {
						  foreach($posttags as $tag) {
						    if (isset($tags)) {
									$tags .= ','.$tag->name;
								} else {
									$tags = $tag->name;
								}
						  }
							if (isset($tags) && $tags != '') {
								$args['tag'] = $tags;
							}
						}
					} else if ($posts_relation_setting == 'author') {
						$authors = $post->post_author;
						if ($authors) {
								$args['author'] = $authors;
							}
					}

				}

				$related_query = new WP_Query($args);

				if ($related_query->have_posts()):

					set_query_var('alia_content_width', 'two_coloumns_list');
					set_query_var('alia_post_position', 'related_posts');
					if (alia_cross_option('alia_post_layout', '', 'fullwidth') == 'sidebar_l') {
						set_query_var('alia_content_layout', 'layout_with_sidebar');
					}
					echo '<div class="post_related">';

						echo '<div class="row two_coloumns_list"><div class="col12">';
						while ($related_query->have_posts()) : $related_query->the_post();
							echo '<div class="thepost_row row">';
								get_template_part( 'template-parts/post/content', get_post_format() );
							echo '</div>';
						endwhile;
						echo '</div></div>'; // end col12 & two_coloumns_list
					echo '</div>';	// end post_related
				endif;
				wp_reset_query();

			endif; // end check for alia_show_related_post
			?>
			<!-- end related posts -->

	<!-- close col and row in case of sidebar layout -->
	<?php if (alia_cross_option('alia_post_layout', '', 'fullwidth') == 'sidebar_l'): ?>


			</div><!-- close post_content_col col8 -->

			<!-- start default sidebar col -->
			<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
				<div class="default_widgets_container default_widgets_col col4">
					<div id="default_sidebar_widget" class="widget_area">
						<?php dynamic_sidebar( 'sidebar-1' ); ?>
					</div>
				</div><!-- #intro_widgets_container -->
			<?php endif; ?>
			<!-- end default sidebar col -->

		</div><!-- close row -->
	<?php endif; ?> <!-- end check for post layout -->

</section><!-- #primary -->
<?php get_footer();
