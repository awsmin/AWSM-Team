<?php
/**
 * List Preset Template.
 *
 * @package awsm-team
 */

?>
<div id="<?php echo esc_attr( $this->add_id( array( 'awsm-team', $id ) ) ); ?>" class="awsm-grid-wrapper">
	<?php if ( $team->have_posts() ) : ?>
		<div class="awsm-grid <?php echo esc_attr( $this->item_style( $options ) ); ?>">
		<?php
		while ( $team->have_posts() ) :
			$team->the_post();
			$teamdata = $this->get_options( 'awsm_team_member', $team->post->ID );
			?>
				<div id="<?php echo esc_attr( $this->add_id( array( 'awsm-member', $id, $team->post->ID ) ) ); ?>" class="awsm-grid-card">
				   <figure>
						 <?php echo $this->get_team_thumbnail( $team->post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						 <figcaption>
							<div class="awsm-personal-info">
							   <h3><?php the_title(); ?></h3>
							   <?php $this->checkprint( '<span>%s</span>', $teamdata['awsm-team-designation'] ); ?>
							</div> <!-- .awsm-personal-info -->
							<div class="awsm-contact-info">
								<?php
								the_content();
								include $this->settings['plugin_path'] . 'templates/partials/social.php';
								?>
							</div> <!-- .awsm-contact-info -->
						 </figcaption>
					  <!-- </span> -->
				   </figure>
				</div>
			<?php
			endwhile;
		wp_reset_postdata();
		?>
		</div><!-- .grid -->
		<?php endif; ?>
</div>
