<?php
/**
 * Cards Preset Template.
 *
 * @package awsm-team
 */

$flip      = false;
$flipclass = array( 'awsm-figcaption' );
if ( in_array( $options['preset'], array( 'style-2' ) ) ) {
	$flip      = true;
	$flipclass = array( 'awsm-flip-back' );
}
?>
<div id="<?php echo esc_attr( $this->add_id( array( 'awsm-team', $id ) ) ); ?>" class="awsm-grid-wrapper">
	<?php if ( $team->have_posts() ) : ?>
		<div class="awsm-grid <?php echo esc_attr( $this->item_style( $options ) ); ?>">
		<?php
		while ( $team->have_posts() ) :
			$team->the_post();
			$teamdata = $this->get_options( 'awsm_team_member', $team->post->ID );

			$personal_info = sprintf( '<div class="awsm-personal-info"><h3>%1$s</h3><span>%2$s</span></div>', get_the_title(), wp_kses( $teamdata['awsm-team-designation'], 'post' ) );
			?>
				<div id="<?php echo esc_attr( $this->add_id( array( 'awsm-member', $id, $team->post->ID ) ) ); ?>" class="awsm-grid-card">
				   <figure>
						<?php
							$this->checkprint( '<div class="awsm-flip-front">', $flip );
							echo $this->get_team_thumbnail( $team->post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						if ( $flip ) {
							echo $personal_info; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
							$this->checkprint( '</div>', $flip );
						?>
						 <figcaption class="<?php echo esc_attr( $this->addclass( $flipclass ) ); ?>">
							<?php
								$this->checkprint( '<div class="awsm-flip-back-inner">', $flip );
								echo $personal_info; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
							<div class="awsm-contact-info">
								<?php
									$this->checkprint( '<p>%s</p>', wp_kses( $teamdata['awsm-team-short-desc'], 'post' ) );
									include $this->settings['plugin_path'] . 'templates/partials/social.php';
								?>
							</div> <!-- .awsm-contact-info -->
							<?php $this->checkprint( '</div><!-- .awsm-flip-back-inner -->', $flip ); ?>
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
