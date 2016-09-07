<?php
$flip = false;
$flipclass = array("awsm-figcaption");
if(in_array($options['preset'], array('style-2'))){
	$flip = true;	
	$flipclass = array("awsm-flip-back");
}
?>
<div id="<?php echo $this->add_id(array('awsm-team',$id));?>" class="awsm-grid-wrapper">
	<?php if ($team->have_posts()): ?>
		<div class="awsm-grid <?php echo $this->item_style($options);?>">
		<?php
			while ($team->have_posts()): $team->the_post();
    		$teamdata = $this->get_options('awsm_team_member', $team->post->ID);?>
				<div id="<?php echo $this->add_id(array('awsm-member',$id,$team->post->ID));?>" class="awsm-grid-card">
				   <figure>
				      <!-- <span class="awsm-grid-holder"> -->
				      	<?php $this->checkprint('<div class="awsm-flip-front">',$flip);?>
				         <img src="<?php echo $this->team_thumbnail($team->post->ID);?>" alt="<?php the_title();?>">
				        <?php $this->checkprint('</div>',$flip);?>
				         <figcaption class="<?php echo $this->addclass($flipclass);?>">
				         	<?php $this->checkprint('<div class="awsm-flip-back-inner">',$flip);?>
				            <div class="awsm-personal-info">
				               <h3><?php the_title();?></h3>
				               <span><?php echo $teamdata['awsm-team-designation'];?></span>
				            </div> <!-- .awsm-personal-info -->
				            <div class="awsm-contact-info">
				                <?php
							    $this->checkprint('<p>%s</p>', $teamdata['awsm-team-short-desc']);
							    include( $this->settings['plugin_path'].'templates/partials/social.php' );
    							?>
				            </div> <!-- .awsm-contact-info -->
				            <?php $this->checkprint('</div><!-- .awsm-flip-back-inner -->',$flip);?>
				         </figcaption>
				      <!-- </span> -->
				      <!-- .awsm-grid-holder -->
				   </figure>
				</div>
			<?php endwhile; wp_reset_postdata();?>
		</div><!-- .grid -->
		<?php endif;?>
</div>