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
		$links       = isset( $social['link'] ) ? $social['link'] : '';
		$icon        = isset( $social['icon'] ) ? $social['icon'] : '';
		if ( filter_var( $links, FILTER_VALIDATE_EMAIL ) ) {
			$social_link = sprintf( 'href="mailto:%1$s"', esc_attr( $links ) );
		} elseif ( $this->validate_phone_number( $links ) === true ) {
			$social_link = sprintf( 'href="tel:%1$s"', esc_attr( $links ) );
		} else {

			$social_link = sprintf( 'href="%1$s" target="_blank"', esc_url( $links ) );
		}

		echo '<span><a ' . $social_link . '><i class="awsm-icon-' . esc_attr( $icon ) . '" aria-hidden="true"></i></a></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</div>';
}

