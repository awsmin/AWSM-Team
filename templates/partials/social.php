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
		$link         = isset( $social['link'] ) ? $social['link'] : '';
		if ( filter_var( $link, FILTER_VALIDATE_EMAIL ) ) {
			$social_link = sprintf( 'href="mailto:%1$s"', esc_attr( $link ) );
		} elseif ( $this->validate_phone_number( $link ) === true ) {
			$social_link = sprintf( 'href="tel:%1$s"', esc_attr( $link ) );
		} else {

			$social_link = sprintf( 'href="%1$s" target="_blank"', esc_url( $link ) );
		}

		echo '<span><a ' . $social_link . '><i class="awsm-icon-' . esc_attr( $social['icon'] ) . '" aria-hidden="true"></i></a></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</div>';
}

