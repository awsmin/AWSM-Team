<div id="<?php echo $this->add_id(array('awsm-team',$id));?>" class="awsm-grid-wrapper">
	<?php if ($team->have_posts()): ?>
	<div class="awsm-table <?php echo $this->item_style($options);?>">
		<div class="awsm-table-row awsm-table-head">
			<div class="awsm-table-cell">
				<?php _e('Image','awsm-team'); ?>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell">
				<?php _e('Name','awsm-team'); ?>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell">
				<?php _e('Designation','awsm-team'); ?>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell">
				<?php _e('Short Description','awsm-team'); ?>
			</div><!-- .awsm-table-cell -->			
			<div class="awsm-table-cell">
				<?php _e('Social Links','awsm-team'); ?>
			</div><!-- .awsm-table-cell -->
		</div>
		<?php 
		while ($team->have_posts()): $team->the_post();
		$teamdata = $this->get_options('awsm_team_member', $team->post->ID);
		?>
		<div id="<?php echo $this->add_id(array('awsm-member',$id,$team->post->ID));?>" class="awsm-table-row">
			<div class="awsm-table-cell awsm-table-image">
				<div class="awsm-table-img-holder">
					<img src="<?php echo $this->team_thumbnail($team->post->ID);?>" alt="<?php the_title();?>">
				</div><!-- .awsm-img-holder -->
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell awsm-table-name">
				<div class="awsm-table-cell-inner"><?php the_title(); ?></div>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell awsm-table-designation">
				<div class="awsm-table-cell-inner"><?php $this->checkprint('%s', $teamdata['awsm-team-designation']);?></div>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell awsm-table-description">
				<div class="awsm-table-cell-inner"><?php $this->checkprint('<p>%s</p>', $teamdata['awsm-team-short-desc']);?></div>
			</div><!-- .awsm-table-cell -->
			<div class="awsm-table-cell">
				<?php include( $this->settings['plugin_path'].'templates/partials/social.php' ); ?>
			</div><!-- .awsm-table-cell -->
		</div><!-- .awsm-table-row -->
		<?php endwhile; wp_reset_postdata();?>
	</div>
	<?php endif;?>	
</div>