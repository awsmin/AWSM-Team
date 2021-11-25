<?php
/**
 * Table Preset Template.
 *
 * @package awsm-team
 */

?>
<div id="<?php echo esc_attr( $this->add_id( array( 'awsm-team', $id ) ) ); ?>" class="awsm-grid-wrapper">
	<?php if ( $team->have_posts() ) : ?>
	<div class="awsm-table <?php echo esc_attr( $this->item_style( $options ) ); ?>">
		<div class="awsm-table-row awsm-table-head">
			<div class="awsm-table-cell">
				<?php esc_html_e( 'Image', 'awsm-team' ); ?>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell">
				<?php esc_html_e( 'Name', 'awsm-team' ); ?>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell">
				<?php esc_html_e( 'Designation', 'awsm-team' ); ?>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell">
				<?php esc_html_e( 'Short Description', 'awsm-team' ); ?>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell">
				<?php esc_html_e( 'Social Links', 'awsm-team' ); ?>
			</div><!-- .awsm-table-cell -->
		</div>
		<?php
		while ( $team->have_posts() ) :
			$team->the_post();
			$teamdata = $this->get_options( 'awsm_team_member', $team->post->ID );
			?>
		<div id="<?php echo esc_attr( $this->add_id( array( 'awsm-member', $id, $team->post->ID ) ) ); ?>" class="awsm-table-row">
			<div class="awsm-table-cell awsm-table-image">
				<div class="awsm-table-img-holder">
					<?php echo $this->get_team_thumbnail( $team->post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div><!-- .awsm-img-holder -->
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell awsm-table-name">
				<div class="awsm-table-cell-inner"><?php the_title(); ?></div>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell awsm-table-designation">
				<div class="awsm-table-cell-inner"><?php $this->checkprint( '%s', $teamdata['awsm-team-designation'] ); ?></div>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell awsm-table-description">
				<div class="awsm-table-cell-inner"><?php $this->checkprint( '<p>%s</p>', $teamdata['awsm-team-short-desc'] ); ?></div>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell">
				<?php include $this->settings['plugin_path'] . 'templates/partials/social.php'; ?>
			</div><!-- .awsm-table-cell -->
		</div><!-- .awsm-table-row -->
			<?php
		endwhile;
		wp_reset_postdata();
		?>
	</div>
	<?php endif; ?>
</div>
