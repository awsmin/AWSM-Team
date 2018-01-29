<div id="<?php echo $this->add_id(array('slide-ins',$id,$team->post->ID));?>" class="awsm-modal-item">
    <div id="<?php echo $this->add_id(array('awsm-member-info',$id,$team->post->ID));?>" class="awsm-modal-content">
        <div class="awsm-modal-content-main">
            <div class="awsm-modal-image-main">
                <img src="<?php echo $this->team_thumbnail($team->post->ID);?>" alt="<?php the_title();?>">
            </div>
            <!-- .image-main -->
            <div class="awsm-modal-details">
                <?php 
                $this->checkprint('<h3>%s</h3>', $teamdata['awsm-team-designation']);
                the_title( '<h2>', '</h2>'); 
                the_content();
                include( $this->settings['plugin_path'].'templates/partials/social.php' );
                ?>
            </div> <!-- .awsm-modal-details -->
        </div> <!-- .awsm-modal-content-main -->
    </div> <!-- .awsm-modal-content -->
</div> <!-- .awsm-modal-item -->