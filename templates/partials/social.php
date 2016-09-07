<?php
if (!empty($teamdata['awsm_social'])) {
    echo '<div class="awsm-social-icons">';
    foreach ($teamdata['awsm_social'] as $social) {
        echo '<span><a href="' . esc_url($social['link']) . '" target="_blank"><i class="awsm-icon-' . $social['icon'] . '" aria-hidden="true"></i></a></span>';
    }
    echo '</div>';
}
?>