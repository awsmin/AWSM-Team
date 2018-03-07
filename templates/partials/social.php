<?php
if (!empty($teamdata['awsm_social'])) {
    echo '<div class="awsm-social-icons">';
    foreach ($teamdata['awsm_social'] as $social) {

    	$link = '';
    	if(filter_var($social['link'], FILTER_VALIDATE_EMAIL)){
    		$link = sprintf('href="mailto:%1$s"',$social['link']);
    	}else{
    		$link = sprintf('href="%1$s" target="_blank"',esc_url($social['link']));
    	}
    	
        echo '<span><a ' . $link . '><i class="awsm-icon-' . $social['icon'] . '" aria-hidden="true"></i></a></span>';
    }
    echo '</div>';
}
?>