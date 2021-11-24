<?php
/**
 * Member Details meta.
 *
 * @package awsm-team
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="member-details-section">
<p>
	<label for="awsm-team-designation"><?php esc_html_e( 'Designation', 'awsm-team' ); ?></label>
	<input class="widefat" type="text" name="awsm-team-designation" id="awsm-team-designation" value="<?php echo esc_attr( get_post_meta( $post->ID, 'awsm-team-designation', true ) ); ?>"/>
</p>
<p>
	<label for="awsm-team-short-desc"><?php esc_html_e( 'Short Description (In 140 characters or less)', 'awsm-team' ); ?></label>
	<textarea id="awsm-team-short-desc" name="awsm-team-short-desc" class="widefat" type="text" maxlength="140"><?php echo esc_attr( get_post_meta( $post->ID, 'awsm-team-short-desc', true ) ); ?></textarea>
</p>
</div>

<p class="awsm-sorable-table-label"><?php esc_html_e( 'Links (Twitter, LinkedIn, etc)', 'awsm-team' ); ?></p>
<div class="member-details-section">
<table id="repeatable-fieldset-two" width="100%" class="awsm-sorable-table">
	<thead>
		<tr>
			<td width="3%"></td>
			<td width="45%"><?php esc_html_e( 'Icon', 'awsm-team' ); ?></td>
			<td width="42%"><?php esc_html_e( 'Link', 'awsm-team' ); ?></td>
			<td width="10%"></td>
		</tr>
	</thead>
	<tbody>
		<?php
		if ( $awsm_social ) :
			foreach ( $awsm_social as $field ) :
				?>
		<tr>
			<td><span class="dashicons dashicons-move"></span></td>
			<td>
				<?php $this->selectbuilder( 'awsm-team-icon[]', $socialicons, $field['icon'], __( 'Select icon', 'awsm-team' ), 'widefat awsm-icon-select' ); ?>
			</td>
			<td><input type="text" placeholder="<?php esc_attr_e( 'ex: http://www.twitter.com/awsmin', 'awsm-team' ); ?>" class="widefat" name="awsm-team-link[]" value="<?php echo isset( $field['link'] ) ? esc_attr( $field['link'] ) : ''; ?>
			"/></td>
			<td><a class="button remove-row" href="#"><?php esc_html_e( 'Remove', 'awsm-team' ); ?></a></td>
		</tr>
				<?php
				endforeach;
			else :
				?>
		<tr>
			<td><span class="dashicons dashicons-move"></span></td>
			<td>
				<?php $this->selectbuilder( 'awsm-team-icon[]', $socialicons, '', __( 'Select icon', 'awsm-team' ), 'widefat awsm-icon-select' ); ?>
			</td>
			<td><input type="text" placeholder="<?php esc_attr_e( 'ex: http://www.twitter.com/awsmin', 'awsm-team' ); ?>" class="widefat" name="awsm-team-link[]" value=""/></td>
			<td><a class="button remove-row" href="#"><?php esc_html_e( 'Remove', 'awsm-team' ); ?></a></td>
		</tr>
		<?php endif; ?>
		<tr class="empty-row screen-reader-text">
			<td><span class="dashicons dashicons-move"></span></td>
			<td>
				<?php $this->selectbuilder( 'awsm-team-icon[]', $socialicons, '', __( 'Select icon', 'awsm-team' ), 'widefat' ); ?>
			</td>
			<td><input type="text" placeholder="<?php esc_attr_e( 'ex: http://www.twitter.com/awsmin', 'awsm-team' ); ?>" class="widefat" name="awsm-team-link[]" value=""/></td>
			<td><a class="button remove-row" href="#"><?php esc_html_e( 'Remove', 'awsm-team' ); ?></a></td>
		</tr>
	</tbody>
</table>
<p><a class="button awsm-add-row" href="#" data-table="repeatable-fieldset-two"><?php esc_html_e( 'Add row', 'awsm-team' ); ?></a></p>
</div>
