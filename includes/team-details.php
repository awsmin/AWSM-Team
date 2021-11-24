<?php
/**
 * Team Details Meta.
 *
 * @package awsm-team
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
			<div class="awsm-team-customize">
			<!-- <h2 class="awsm-sub-heading"></h2> -->
			<div class="awsm-team-customize-inner">
				<div class="awsm-team-customize-member">
					<div class="awsm-heading-group">
						<h2 class="sub-h"><?php esc_html_e( 'Members', 'awsm-team' ); ?></h2>
						<span><?php esc_html_e( 'Select members from the dropdown, drag and drop them to reorder.', 'awsm-team' ); ?></span>
					</div>
					<div class="awsm-select-members">
						<?php
						if ( $members->have_posts() ) :
							?>
						<select name="members" id="awsm-members">
							<?php
							echo '<option value="" data-img="' . esc_attr( $defaultimage ) . '">' . esc_html_e( 'Select a member', 'awsm-team' ) . '</option>';
							while ( $members->have_posts() ) :
								$members->the_post();
								$disabled = '';
								if ( in_array( $members->post->ID, $options['memberlist'] ) ) {
									$disabled = 'disabled';
								}
								echo '<option value="' . esc_attr( $members->post->ID ) . '" data-img="' . esc_attr( $this->team_thumbnail( $members->post->ID, 'thumbnail' ) ) . '" ' . esc_attr( $disabled ) . '>' . esc_html( get_the_title() ) . '</option>';
							endwhile;
							wp_reset_postdata();
							?>
						</select>
							<?php
						else :
							$addmember = admin_url( 'post-new.php?post_type=awsm_team_member' );
							echo '<p>';
							esc_html_e( 'You haven’t added any team members yet. ', 'awsm-team' );
							echo '<a href="' . esc_url( $addmember ) . '">' . esc_html__( 'Add a team member', 'awsm-team' ) . '</a>';
							echo '</p>';
						endif;
						?>
					</div><!-- .awsm-select-members -->
					<ul class="awsm-members-list-selected">
						<div class="awsm-members-info"><?php esc_html_e( 'No Members Selected', 'awsm-team' ); ?></div>
						<script type="text/html" id="tmpl-awsm-member-list">
						   <li data-member-id="{{{data.id}}}" class="">
							<img width="31" height="31" src="{{{data.src}}}"/>
							<p>{{{data.title}}}</p><span class="remove-member-to-list" data-member="{{{data.id}}}"><i class="awsm-icon-close"></i></span>
							<input type="hidden" name="memberlist[]" value='{{{data.id}}}'>
							</li>
						</script>
						<?php
						if ( $options['memberlist'] ) :
							$teamargs = array(
								'orderby'        => 'post__in',
								'post_type'      => 'awsm_team_member',
								'post__in'       => $options['memberlist'],
								'posts_per_page' => -1,
							);
							$team     = new WP_Query( $teamargs );
							if ( $team->have_posts() ) :
								while ( $team->have_posts() ) :
									$team->the_post();
									?>
								   <li data-member-id="<?php echo esc_attr( $team->post->ID ); ?>" class="">
									<img width="31" height="31" src="<?php echo esc_url( $this->team_thumbnail( $team->post->ID, 'thumbnail' ) ); ?>"/>
									<p><?php the_title(); ?></p><span class="remove-member-to-list" data-member="<?php echo esc_attr( $team->post->ID ); ?>"><i class="awsm-icon-close"></i></span>
									<input type="hidden" name="memberlist[]" value="<?php echo esc_attr( $team->post->ID ); ?>">
									</li>
									<?php
								endwhile;
								wp_reset_postdata();
							endif;
						endif;
						?>
					</ul><!-- .awsm-members-list-selected -->
				</div><!-- .awsm-team-customize-member -->
				<div class="awsm-team-customize-style">
					<div class="awsm-heading-group">
						<h2 class="sub-h"><?php esc_html_e( 'Presets', 'awsm-team' ); ?></h2>
						<span><?php esc_html_e( 'Choose a preset from below.', 'awsm-team' ); ?></span>
					</div>
					<div class="awsm-preset-list awsm-clearfix">
								<?php
								$styles = array(
									'Cards'     => array( 4, 1, 1 ),
									'List'      => array( 2, 0, 1 ),
									'Table'     => array( 3, 0, 1 ),
									'Drawer'    => array( 2, 1, 0 ),
									'Modal'     => array( 1, 1, 0 ),
									'Grid'      => array( 4, 1, 0 ),
									'Circles'   => array( 4, 1, 0 ),
									'Slide-Ins' => array( 2, 1, 0 ),
								);
								foreach ( $styles as $key => $set ) :
									$val = strtolower( $key );
									?>
								<input class="awsm-radio-hidden" id="rad-<?php echo esc_attr( $val ); ?>" type="radio" data-style="<?php echo esc_attr( $set[0] ); ?>" data-column="<?php echo esc_attr( $set[1] ); ?>" name="team-style" value="<?php echo esc_attr( $val ); ?>" <?php checked( $val, $options['team-style'] ); ?><?php echo ! $set[2] ? ' disabled' : ''; ?>>
								<label for="rad-<?php echo esc_attr( $val ); ?>" class="<?php echo ! $set[2] ? 'awsm_pro_feature' : ''; ?>"><img src="<?php echo esc_url( $this->settings['plugin_url'] . '/images/' . $val . '.jpg' ); ?>">
									<span data-type="<?php echo esc_attr( $val ); ?>"><?php echo esc_html( $key ); ?></span>
								</label>
							<?php endforeach; ?>
					</div><!-- .awsm-preset-list -->
					<div class="awsm-section awsm-clearfix">
							<div class="awsm-heading-group">
								<h2 class="sub-h"><?php esc_html_e( 'Style', 'awsm-team' ); ?></h2>
								<span>
								<?php
									$url = 'https://demo.awsm.in/team-pro/';
									printf(
										wp_kses(
											/* translators: %s: Team demo link */
											__( 'We have a set of predefined styles for each preset. Choose your favorite. Refer <a href="%s" target="_blank">demo</a>.', 'awsm-team' ),
											array(
												'a' => array(
													'href' => array(),
													'target' => array(),
												),
											)
										),
										esc_url( $url )
									);
									?>
									</span>
							</div><!-- .awsm-heading-group -->
							<div class="awsm-row">
								<div class="awsm-col-2">
									<?php
									$preset = array(
										'style-1' => 'Style 1',
										'style-2' => 'Style 2',
										'style-3' => 'Style 3',
										'style-4' => 'Style 4',
									);
									$this->selectbuilder( 'preset', $preset, $options['preset'], '', 'awsm-select-default dyn-sel awsm-styles', 'key' );
									?>
								</div><!-- .awsm-col-2 -->
								<div class="awsm-col-2 awsm-columns-wrap">
									<?php
									$columns = array(
										'2' => '2 Column',
										'3' => '3 Column',
										'4' => '4 Column',
										'5' => '5 Column',
									);
									$this->selectbuilder( 'columns', $columns, $options['columns'], '', 'awsm-select-default dyn-sel awsm-columns', 'key' );
									?>
								</div><!-- .awsm-col-2 -->
							</div><!-- .awsm-row -->
					</div><!-- .awsm-row -->
					<div class="awsm-custom-css-wrap">
						<div class="awsm-heading-group">
							<h2 class="sub-h"><?php esc_html_e( 'Custom CSS', 'awsm-team' ); ?></h2>
							<span><?php esc_html_e( 'Want to add your own colours and flavours? Add your custom CSS in the text box below.', 'awsm-team' ); ?></span>
						</div><!-- .awsm-heading-group -->
						<textarea name="custom_css"><?php echo esc_textarea( $options['custom_css'] ); ?></textarea>
					</div>
				</div><!-- .awsm-team-customize-style -->
				<div class="awsm-clearfix"></div>
			</div><!-- .awsm-team-customize-inner -->
		</div><!-- .awsm-team-customize -->
</div><!-- wrap -->
<script type="text/html" id="tmpl-awsm-member-select">
	<div class="select2-result-repository clearfix">
		<# if ( data.src ) { #>
			<div class="awsm-member-thumb">
				<img class="select2-result-repository__avatar" width="150" height="150" src="{{{data.src}}}" />
			</div>
		<# } #>
		<p class="select2-result-repository__title">{{{data.title}}}</p>
	</div>
</script>
