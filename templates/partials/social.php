<?php
/**
 * Social Info Template Part.
 *
 * @package awsm-team
 */

if ( ! empty( $teamdata['awsm_social'] ) ) {
	echo '<div class="awsm-social-icons">';
	foreach ( $teamdata['awsm_social'] as $social ) {

		$social_link = '';
		if ( filter_var( $social['link'], FILTER_VALIDATE_EMAIL ) ) {
			$social_link = sprintf( 'href="mailto:%1$s"', esc_attr( $social['link'] ) );
		} elseif ( $this->validate_phone_number( $social['link'] ) === true ) {
			$social_link = sprintf( 'href="tel:%1$s"', esc_attr( $social['link'] ) );
		} else {
			$social_link = sprintf( 'href="%1$s" target="_blank"', esc_url( $social['link'] ) );
		}

		echo '<span><a ' . $social_link . '><i class="awsm-icon-' . esc_attr( $social['icon'] ) . '" aria-hidden="true"></i></a></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</div>';
}

