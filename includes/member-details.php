<div class="member-details-section">
<p>
    <label for="awsm-team-designation"><?php _e( "Designation", 'awsm-team' ); ?></label>
    <input class="widefat" type="text" name="awsm-team-designation" id="awsm-team-designation" value="<?php echo esc_attr(get_post_meta($post->ID, 'awsm-team-designation', true));?>"/>
</p>
<p>
	<label for="awsm-team-short-desc"><?php _e( 'Short Description (In 140 characters or less)', 'awsm-team' ); ?></label><br/>
	<textarea id="awsm-team-short-desc" name="awsm-team-short-desc" class="widefat" type="text" maxlength="140"><?php echo esc_attr(get_post_meta($post->ID, 'awsm-team-short-desc', true));?></textarea>
</p>
</div>

<h3><?php _e('Links (Twitter, LinkedIn, etc)','awsm-team');?></h3>
<div class="member-details-section">
<table id="repeatable-fieldset-two" width="100%" class="awsm-sorable-table">
	<thead>
		<tr>
			<td width="3%"></td>
			<td width="45%"><?php _e('Icon','awsm-team');?></td>
			<td width="42%"><?php _e('Link','awsm-team');?></td>
			<td width="10%"></td>
		</tr>
	</thead>
	<tbody>
		<?php if ( $awsm_social ) : 
		foreach ( $awsm_social as $field ) { ?>
		<tr>
			<td><span class="dashicons dashicons-move"></span></td>
			<td>
				<?php $this->selectbuilder('awsm-team-icon[]',$socialicons,$field['icon'],__('Select icon','awsm-team'),'widefat awsm-icon-select');?>
			</td>
			<td><input type="text" placeholder="<?php _e('ex: http://www.twitter.com/awsmin','awsm-team');?>" class="widefat" name="awsm-team-link[]" value="<?php if(isset($field['link'])) echo esc_attr( $field['link'] ); ?>"/></td>
			<td><a class="button remove-row" href="#"><?php _e('Remove','awsm-team');?></a></td>
		</tr>	
		<?php } else: ?> 
		<tr>
			<td><span class="dashicons dashicons-move"></span></td>
			<td>
				<?php $this->selectbuilder('awsm-team-icon[]',$socialicons,'',__('Select icon','awsm-team'),'widefat awsm-icon-select');?>
			</td>
			<td><input type="text" placeholder="<?php _e('ex: http://www.twitter.com/awsmin','awsm-team');?>" class="widefat" name="awsm-team-link[]" value=""/></td>
			<td><a class="button remove-row" href="#"><?php _e('Remove','awsm-team');?></a></td>
		</tr>	
		<?php endif; ?>
		<tr class="empty-row screen-reader-text">
			<td><span class="dashicons dashicons-move"></span></td>
			<td>
				<?php $this->selectbuilder('awsm-team-icon[]',$socialicons,'',__('Select icon','awsm-team'),'widefat');?>
			</td>
			<td><input type="text" placeholder="<?php _e('ex: http://www.twitter.com/awsmin','awsm-team');?>" class="widefat" name="awsm-team-link[]" value=""/></td>
			<td><a class="button remove-row" href="#"><?php _e('Remove','awsm-team');?></a></td>
		</tr>
	</tbody>
</table>
<p><a class="button awsm-add-row" href="#" data-table="repeatable-fieldset-two"><?php _e('Add row','awsm-team');?></a></p>
</div>