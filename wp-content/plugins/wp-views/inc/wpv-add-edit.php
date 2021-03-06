<?php
/**
* Added extra files to have the old and new editors working together
* TODO Once we are done, those extra files will be merged with the old ones after cleaning no longer needed functions
*/

/** General TODOs
* TODO Create extra files to make this screen modular. STATUS: 90%
*/

// Filter section files
require_once WPV_PATH . '/inc/redesign/wpv-section-query-type.php';
require_once WPV_PATH . '/inc/redesign/wpv-section-query-options.php';
require_once WPV_PATH . '/inc/redesign/wpv-section-ordering.php';
require_once WPV_PATH . '/inc/redesign/wpv-section-limit-offset.php';
require_once WPV_PATH . '/inc/redesign/wpv-section-filters.php';
require_once WPV_PATH . '/inc/redesign/wpv-section-pagination.php';
require_once WPV_PATH . '/inc/redesign/wpv-section-filter-extra.php';
// Layout section files
require_once WPV_PATH . '/inc/redesign/wpv-section-layout-template.php';
require_once WPV_PATH . '/inc/redesign/wpv-section-layout-extra.php';
require_once WPV_PATH . '/inc/redesign/wpv-section-layout-extra-js.php';
// Extra section files
require_once WPV_PATH . '/inc/redesign/wpv-section-content.php';

/**
 * View edit screen
 */
function views_redesign_html() {
	global $WP_Views, $post;
	
	if ( 
		isset( $_GET['view_id'] ) 
		&& is_numeric( $_GET['view_id'] ) 
	) {
		do_action('views_edit_screen');
		$view_id = (int) $_GET['view_id'];
		$view = get_post( $view_id, OBJECT, 'edit' );
		if ( null == $view ) {
			wpv_die_toolset_alert_error( __( 'You attempted to edit a View that doesn&#8217;t exist. Perhaps it was deleted?', 'wpv-views' ) );
		} elseif ( 'view'!= $view->post_type ) {
			wpv_die_toolset_alert_error( __( 'You attempted to edit a View that doesn&#8217;t exist. Perhaps it was deleted?', 'wpv-views' ) );
		} else {
			$view_settings = get_post_meta( $view_id, '_wpv_settings', true );
			
			/**
			* wpv_view_settings
			*
			* Internal filter to set some View settings that will overwrite the ones existing in the _wpv_settings postmeta
			* Only used to set default values that need to be there on the returned array, but may not be there for legacy reasons
			* Use wpv_filter_override_view_settings to override View settings - like on the Theme Frameworks integration
			*
			* @param $view_settings (array) Unserialized array of the _wpv_settings postmeta
			* @param $view_id (integer) The View ID
			*
			* @return $view_settings (array) The View settings
			*
			* @since unknown
			*/
			
			$view_settings = apply_filters( 'wpv_view_settings', $view_settings, $view_id );
			
			$view_layout_settings = get_post_meta( $view_id, '_wpv_layout_settings', true );
			
			/**
			* wpv_view_layout_settings
			*
			* Internal filter to set some View layout settings that will overwrite the ones existing in the _wpv_layout_settings postmeta
			* Only used to set default values that need to be there on the returned array,, but may not be there for legacy reasons
			* Use wpv_filter_override_view_layout_settings to override View layout settings
			*
			* @param $view_layout_settings (array) Unserialized array of the _wpv_layout_settings postmeta
			* @param $view_id (integer) The View ID
			*
			* @return $view_layout_settings (array) The View layout settings
			*
			* @since 1.8.0
			*/
			
			$view_layout_settings = apply_filters( 'wpv_view_layout_settings', $view_layout_settings, $view_id );
			
			if (
				isset( $view_settings['view-query-mode'] ) 
				&& ( 'normal' ==  $view_settings['view-query-mode'] )
			) {
				$post = $view;
				if ( get_post_status( $view_id ) == 'trash' ) {
					wpv_die_toolset_alert_error( __( 'You can’t edit this View because it is in the Trash. Please restore it and try again.', 'wpv-views' ) );
				}
			} else {
				wpv_die_toolset_alert_error( __('You attempted to edit a View that doesn&#8217;t exist. Perhaps it was deleted?', 'wpv-views' ) );
			}
		}
	} else {
		wpv_die_toolset_alert_error( __( 'You attempted to edit a View that doesn&#8217;t exist. Perhaps it was deleted?', 'wpv-views' ) );
	}
	?>
	<?php
	/**
	* Screen Options tab
	*/
	?>
	<div id="js-screen-meta-dup" class="metabox-prefs js-screen-meta-dup hidden">
		<div id="js-screen-options-wrap-dup">
			<h5><?php _e('Show on screen', 'wpv-views');?></h5>
			<p>
				<small><?php echo __('Note that those Screen Options are set per View.', 'wpv-views');?></small>
			</p>
			<?php
				$sections = array();
				$sections = apply_filters( 'wpv_sections_query_show_hide', $sections );
				if ( ! empty( $sections ) ) {
			?>
			<div class="wpv-show-hide-section wpv-show-hide-section-query js-wpv-show-hide-section" data-metasection="wpv-query-section">
				<h6><?php _e('Query section', 'wpv-views'); ?></h6>
				<span class="js-wpv-screen-pref">
				<?php 
				if ( 
					isset( $view_settings['metasections-hep-show-hide'] ) 
					&& isset( $view_settings['metasections-hep-show-hide']['wpv-query-help'] ) 
				) {
					$state = $view_settings['metasections-hep-show-hide']['wpv-query-help'];
				} else {
					$state = 'on';
				} ?>
				<label for="wpv-show-hide-query-help">
					<input type="checkbox" id="wpv-show-hide-query-help" data-metasection="query" class="js-wpv-show-hide-help js-wpv-show-hide-query-help" <?php checked( 'on', $state ); ?> autocomplete="off" />
					<?php echo __('Display Query section help', 'wpv-views'); ?>
				</label>
				<input name="wpv-query-help" type="hidden" class="js-wpv-show-hide-help-value js-wpv-show-hide-query-help-value" value="<?php echo esc_attr( $state ); ?>" autocomplete="off" />
				</span>
				<?php
					foreach ( $sections as $key => $values ) {
						if ( isset( $view_settings['sections-show-hide'] ) && isset( $view_settings['sections-show-hide'][$key] ) ) {
							$values['state'] = $view_settings['sections-show-hide'][$key];
						} else {
							$values['state'] = 'on';
						} ?>
						<span class="js-wpv-screen-pref">
						<label for="wpv-show-hide-<?php echo esc_attr( $key ); ?>">
							<input data-section="<?php echo esc_attr( $key ); ?>" type="checkbox" id="wpv-show-hide-<?php echo esc_attr( $key ); ?>" class="js-wpv-show-hide js-wpv-show-hide-<?php echo esc_attr( $key ); ?>" <?php checked( 'on', $values['state'] ); ?> autocomplete="off" />
							<?php echo $values['name']; ?>
						</label>
						<input data-section="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" class="js-wpv-show-hide-value" type="hidden" value="<?php echo esc_attr( $values['state'] ); ?>" autocomplete="off" />
						</span>
					<?php }
				?>
			</div>
			<?php } ?>
			<?php
				$sections = array();
				$sections = apply_filters( 'wpv_sections_filter_show_hide', $sections );
				if ( !empty( $sections ) ) {
			?>
			<div class="wpv-show-hide-section wpv-show-hide-section-filter js-wpv-show-hide-section" data-metasection="wpv-filter-section">
				<h6><?php _e('Filter section', 'wpv-views'); ?></h6>
				<span class="js-wpv-screen-pref">
				<?php 
				if ( 
					isset( $view_settings['metasections-hep-show-hide'] ) 
					&& isset( $view_settings['metasections-hep-show-hide']['wpv-filter-help'] ) 
				) {
					$state = $view_settings['metasections-hep-show-hide']['wpv-filter-help'];
				} else {
					$state = 'on';
				} ?>
				<label for="wpv-show-hide-filter-help">
					<input type="checkbox" id="wpv-show-hide-filter-help" data-metasection="filter" class="js-wpv-show-hide-help js-wpv-show-hide-filter-help" <?php checked( 'on', $state ); ?> autocomplete="off" />
					<?php echo __('Display Filter section help', 'wpv-views'); ?>
				</label>
				<input name="wpv-filter-help" type="hidden" class="js-wpv-show-hide-help-value js-wpv-show-hide-filter-help-value" value="<?php echo esc_attr( $state ); ?>" autocomplete="off" />
				</span>
				<?php
					foreach ( $sections as $key => $values ) {
						if ( 
							isset( $view_settings['sections-show-hide'] ) 
							&& isset( $view_settings['sections-show-hide'][$key] ) 
						) {
							$values['state'] = $view_settings['sections-show-hide'][$key];
						} else {
							$values['state'] = 'on';
						} ?>
						<span class="js-wpv-screen-pref">
						<label for="wpv-show-hide-<?php echo esc_attr( $key ); ?>">
							<input data-section="<?php echo esc_attr( $key ); ?>" type="checkbox" id="wpv-show-hide-<?php echo esc_attr( $key ); ?>" class="js-wpv-show-hide js-wpv-show-hide-<?php echo esc_attr( $key ); ?>" <?php checked( 'on', $values['state'] ); ?> autocomplete="off" />
							<?php echo $values['name']; ?>
						</label>
						<input data-section="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" class="js-wpv-show-hide-value" type="hidden" value="<?php echo esc_attr( $values['state'] ); ?>" autocomplete="off" />
						</span>
					<?php }
				?>
			</div>
			<?php } ?>
			<?php
				$sections = array();
				$sections = apply_filters( 'wpv_sections_layout_show_hide', $sections );
				$js = isset( $view_layout_settings['additional_js'] ) ? strval( $view_layout_settings['additional_js'] ) : '';
				if ( '' == $js && isset( $sections['layout-settings-extra-js'] ) ) {
					unset( $sections['layout-settings-extra-js'] );
				}
				if ( ! empty( $sections ) ) {
			?>
			<div class="wpv-show-hide-section wpv-show-hide-section-layout js-wpv-show-hide-section" data-metasection="wpv-layout-section">
				<h6><?php _e( 'Loop Output section', 'wpv-views' ); ?></h6>
				<span class="js-wpv-screen-pref">
				<?php
				if ( 
					isset( $view_settings['metasections-hep-show-hide'] ) 
					&& isset( $view_settings['metasections-hep-show-hide']['wpv-layout-help'] ) 
				) {
					$state = $view_settings['metasections-hep-show-hide']['wpv-layout-help'];
				} else {
					$state = 'on';
				} ?>
				<label for="wpv-show-hide-layout-help">
					<input type="checkbox" id="wpv-show-hide-layout-help" data-metasection="layout" class="js-wpv-show-hide-help js-wpv-show-hide-layout-help" <?php checked( 'on', $state ); ?> autocomplete="off" />
					<?php echo __( 'Display help for the Loop Output section', 'wpv-views'); ?>
				</label>
				<input name="wpv-layout-help" type="hidden" class="js-wpv-show-hide-help-value js-wpv-show-hide-layout-help-value" value="<?php echo esc_attr( $state ); ?>" autocomplete="off" />
				</span>
				<?php
					foreach ( $sections as $key => $values ) {
						if ( 
							isset( $view_settings['sections-show-hide'] ) 
							&& isset( $view_settings['sections-show-hide'][$key] ) 
						) {
							$values['state'] = $view_settings['sections-show-hide'][$key];
						} else {
							$values['state'] = 'on';
						}
						?>
						<span class="js-wpv-screen-pref">
						<label for="wpv-show-hide-<?php echo esc_attr( $key ); ?>">
							<input data-section="<?php echo esc_attr( $key ); ?>" type="checkbox" id="wpv-show-hide-<?php echo esc_attr( $key ); ?>" class="js-wpv-show-hide js-wpv-show-hide-<?php echo esc_attr( $key ); ?>" <?php checked( 'on', $values['state'] ); ?> autocomplete="off" />
							<?php echo $values['name']; ?>
						</label>
						<input data-section="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" class="js-wpv-show-hide-value" type="hidden" value="<?php echo esc_attr( $values['state'] ); ?>" autocomplete="off" />
						</span>
					<?php }
				?>
			</div>
			<?php } ?>
			<?php
			if ( 
				! isset( $view_settings['view_purpose'] ) 
				|| $view_settings['view_purpose'] == 'bootstrap-grid' // @note From 1.7, bootstrap-grid purpose is deprecated and hopefully removed, defaults to full
			) {
				$view_settings['view_purpose'] = 'full';
			}
			?>
			<p>
				<label for="wpv-view-purpose"><?php echo __('View purpose', 'wpv-views'); ?></label>
				<select id="wpv-view-purpose" class="js-view-purpose" autocomplete="off">
					<?php $purpose_options = array(
						'all' => __('Display all results', 'wpv-views'),
						'pagination' => __('Display the results with pagination', 'wpv-views'),
						'slider' => __('Display the results as a slider', 'wpv-views'),
						'parametric' => __('Display the results as a parametric search', 'wpv-views'),
						'full' => __('Full custom display mode', 'wpv-views')
					);
					foreach ( $purpose_options as $opt => $opt_name ) { ?>
						<option id="wpv-settings-query-type-posts" <?php selected( $view_settings['view_purpose'], $opt ); ?> value="<?php echo esc_attr( $opt ); ?>"><?php echo $opt_name; ?></option>
					<?php } ?>
				</select>
				<input type="hidden" data-nonce="<?php echo wp_create_nonce( 'wpv_view_show_hide_nonce' ); ?>" class="js-wpv-show-hide-update" autocomplete="off" />
			</p>
			<div class="js-wpv-toolset-messages"></div>
		</div>
	</div>
	<?php
	/**
	* Actual View edit page
	*/
	?>
	<div class="wrap toolset-views">
	<input id="post_ID" class="js-post_ID" type="hidden" value="<?php echo esc_attr( $view_id ); ?>" data-nonce="<?php echo wp_create_nonce( 'wpv_view_edit_general_nonce' ); ?>" />
    <input id="toolset-edit-data" type="hidden" value="<?php echo esc_attr( $view_id ); ?>" data-plugin="views" />
	<h1><?php echo __('Edit View','wpv-views'); ?></h1>
	<?php
	if ( isset( $_GET['in-iframe-for-layout'] ) ) {
		$in_iframe = 'yes';
	} else {
		$in_iframe = '';
	}
	$user_id = get_current_user_id();
	?>
	<input type="hidden" class="js-wpv-display-in-iframe" value="<?php echo esc_attr( $in_iframe ); ?>" />
		<div id="js-wpv-general-actions-bar" class="wpv-settings-save-all wpv-general-actions-bar wpv-setting-container js-wpv-no-lock js-wpv-general-actions-bar">
			<p class="update-button-wrap js-wpv-update-button-wrap">
				<?php
				if ( ! defined( 'WPDDL_VERSION' ) ) {
				?>
				<button class="button-secondary button button-large js-wpv-view-create-page" data-error="<?php _e( 'An error occurred, try again.', 'wpv-views' ); ?>">
					<?php _e( 'Create a page with this View', 'wpv-views' ); ?>
				</button>
				<?php
				}
				?>
				<button class="button-secondary button button-large js-wpv-view-save-all"
						disabled="disabled"
						data-success="<?php echo esc_attr( __('View saved', 'wpv-views') ); ?>"
						data-unsaved="<?php echo esc_attr( __('View not saved', 'wpv-views') ); ?>">
					<?php _e( 'Save all sections at once', 'wpv-views' ); ?>
				</button>
			</p>
			<span class="wpv-message-container js-wpv-message-container"></span>
		</div>
		<input type="hidden" name="_wpv_settings[view-query-mode]" value="normal" />
		<div class="wpv-title-section">
			<div class="wpv-setting-container wpv-settings-title-and-desc js-wpv-settings-title-and-desc js-wpv-no-lock wpv-setting-container-no-title">
				<div class="wpv-setting">
					<div id="titlediv">
						<div id="titlewrap" class="wpv-titlewrap js-wpv-titlewrap">
							<label class="screen-reader-text js-title-reader" id="title-prompt-text" for="title"><?php _e('Enter title here','wpv-views'); ?></label>
                            <input id="title" class="wpv-title js-title" type="text" name="post_title" size="30" value="<?php echo esc_attr( $view->post_title ); ?>" id="title" autocomplete="off">
							<span class="update-button-wrap js-wpv-update-button-wrap">
								<button
									class="button js-wpv-title-update button-secondary"
									data-nonce="<?php echo wp_create_nonce( 'wpv_view_title_nonce' ); ?>"
									data-success="<?php echo esc_attr( __('Title updated', 'wpv-views') ); ?>"
									data-unsaved="<?php echo esc_attr( __('Title not saved', 'wpv-views') ); ?>"
									disabled="disabled"
									style="height:38px;line-height:36px;"
								><?php _e('Update', 'wpv-views'); ?></button>
							</span>
						</div>
						<span class="wpv-message-container js-wpv-message-container"></span>
					</div>

					<div id="edit-slug-box" class="wpv-slug-container js-wpv-slug-container">
						<label for="wpv-slug"><?php _e('Slug:', 'wpv-views'); ?>
						<span id="editable-post-name" title="<?php _e('Click to edit WordPress Archive slug', 'wpv-views'); ?>" class="js-wpv-edit-slug-toggle"><?php echo esc_attr( $view->post_name ); ?></span>
                        <input id="wpv-slug" class="js-wpv-edit-slug-toggle js-wpv-slug" type="text" style="display:none" value="<?php echo esc_attr( $view->post_name ); ?>" />
						<span class="js-wpv-inline-edit">
							<span class="js-wpv-message-container-slug"></span>
							<button class="button-secondary js-wpv-edit-slug js-wpv-edit-slug-toggle"><?php _e('Edit', 'wpv-views'); ?></button>
                            <button
								data-nonce="<?php echo wp_create_nonce( 'wpv_view_change_post_name' ); ?>"
								data-state="edit"
								data-success="<?php echo esc_attr( __('Slug changed', 'wpv-views') ); ?>"
								data-unsaved="<?php echo esc_attr( __('Slug not changed', 'wpv-views') ); ?>"
								class="button-secondary js-wpv-edit-slug-update js-wpv-edit-slug-toggle"
								style="display:none"
                            >
							<?php _e('OK', 'wpv-views'); ?>
							</button>
							<a href="#" class="js-wpv-edit-slug-cancel js-wpv-edit-slug-toggle" style="display:none"><?php _e('Cancel', 'wpv-views'); ?></a>
							</button>
						</span>
						<span class="wpv-action-secondary">
							<button class="button-secondary js-wpv-change-view-status"
									data-statusto="trash"
									data-success="<?php echo esc_attr( __('View moved to trash', 'wpv-views') ); ?>"
									data-unsaved="<?php echo esc_attr( __('View not moved to trash', 'wpv-views') ); ?>"
									data-redirect="<?php echo admin_url( 'admin.php?page=views'); ?>"
									data-nonce="<?php echo wp_create_nonce( 'wpv_view_change_status' ); ?>">
								<i class="icon-trash fa fa-trash"></i> <?php _e('Move to trash', 'wpv-views'); ?>
							</button>
						</span>
						<?php
						$view_description = get_post_meta( $view_id, '_wpv_description', true );
						if (
							! isset( $view_description )
							|| empty( $view_description )
						) {
						?>
						<span class="wpv-action-secondary">
							<button class="js-wpv-description-toggle button-secondary" >
								<i class="icon-align-left fa fa-align-left"></i> <?php _e('Add description', 'wpv-views'); ?>
							</button>
						</span>
						<?php
						}
						?>
					</div>
					<div class="js-wpv-description-container wpv-description-container<?php echo ( isset( $view_description ) && !empty( $view_description ) ) ? '' : ' hidden'; ?>">
						<p>
							<label for="wpv-description"><?php _e('Describe this View', 'wpv-views' ) ?></label>
						</p>
						<p>
							<textarea id="wpv-description" class="js-wpv-description" name="_wpv_settings[view_description]" cols="72" rows="4" autocomplete="off"><?php echo ( isset( $view_description ) ) ? esc_html( $view_description ) : ''; ?></textarea>
						</p>

					<p class="update-button-wrap js-wpv-update-button-wrap">
						<button
							class="js-wpv-description-update button-secondary"
							data-nonce="<?php echo wp_create_nonce( 'wpv_view_description_nonce' ); ?>"
							data-success="<?php echo esc_attr( __('Title and description updated', 'wpv-views') ); ?>"
							data-unsaved="<?php echo esc_attr( __('Title and description not saved', 'wpv-views') ); ?>"
							disabled="disabled"
						><?php _e('Update', 'wpv-views'); ?></button>
					</p>
					</div>

				</div>
			</div>
		</div> <!-- .wpv-title-section -->

		<div class="wpv-query-section">
			<?php
			wpv_get_view_introduction_data();
			?>
			<span class="wpv-section-title"><?php _e('The Query section determines what content the View loads from the database','wpv-views') ?></span>
			<?php do_action('view-editor-section-query', $view_settings, $view_id, $user_id); ?>
		</div>

		<?php
		/*
		* Query type (content selection) - Priority 10
		* Query options - Priority 20
		* Ordering - Priority 30
		* Limit and offset - Priority 40
		* Filters - Priority 50
		*/
		?>

		<div class="wpv-filter-section">
			<span class="wpv-section-title"><?php _e('The Filter section lets you set up pagination and parametric search, which let visitors control the View query','wpv-views') ?></span>
			<?php
			wpv_get_view_filter_introduction_data();
			?>
			<?php do_action('view-editor-section-filter', $view_settings, $view_id, $user_id); ?>
		</div>

		<?php
		/*
		* Pagination TODO review this. https://icanlocalize.basecamphq.com/projects/7393061-toolset/todo_items/161787682/comments - Priority 50
		* Filters Meta HTML/CSS/JS TODO review this. https://icanlocalize.basecamphq.com/projects/7393061-toolset/todo_items/161787682/comments - Priority 80
		*/
		?>

		<div class="wpv-layout-section">
			<span class="wpv-section-title"><?php _e('The Loop Output section styles the View output on the page.','wpv-views') ?></span>
			<?php
			$data = wpv_get_view_layout_introduction_data();
			wpv_toolset_help_box($data);
			?>
			<?php do_action('view-editor-section-layout', $view_settings, $view_layout_settings, $view_id, $user_id); ?>
			<?php do_action('view-editor-section-extra', $view_settings, $view_id, $user_id); ?>
		</div>
		
        <?php
        $display_help = ( isset( $_GET['in-iframe-for-layout'] ) && $_GET['in-iframe-for-layout'] == 1 ) ? false : true;
        
        if ( $display_help === true ) { ?>
		<div class="wpv-help-section">
		<?php
			wpv_display_view_howto_help_box();
		?>
		</div>
        <?php } ?>

		<script type="text/javascript">
			jQuery( function( $ ) {
				jQuery('li.current a').attr('href',jQuery('li.current a').attr('href')+'&view_id=<?php echo esc_attr( $view_id ); ?>');
			});
		</script>
		<?php
		/*
		* Output (layout) type - TODO review this https://icanlocalize.basecamphq.com/projects/7393061-toolset/todo_items/162512599/comments - Priority 10 - To remove
		* Output fields TODO this has been reviewed and may be used as training - Priority 20 - To remove
		* Layout templates TODO insert here the new Content Templates editor. https://icanlocalize.basecamphq.com/projects/7393061-toolset/todo_items/161787695/comments - Priority 20 - To review
		* Layout Meta HTML/CSS/JS TODO this has been reviewed and needs some changes. https://icanlocalize.basecamphq.com/projects/7393061-toolset/todo_items/161787640/comments - Priority 40
		* Aditional Javascript files TODO move to its own file - Priority 50
		* Extra sections:
		* 1. Complete output (the_content)
		* 2. Module manager TODO needs to be added
		*/
		?>
	</div>
	<?php
	
	/**
		* view-editor-section-hidden
		*
		* Show hidden container for dialogs, pointers and messages that need to be taken by ColorBox from an existing HTML element
		*
		* @param $view_settings
		* @param $view_laqyout_settings
		* @param $view_id
		* @param $user_id
		*
		* @note that you can use the .popup-window-container classname to hide the containers added here
		*
		* @since 1.7
		*/
		
		do_action( 'view-editor-section-hidden', $view_settings, $view_layout_settings, $view_id, $user_id );
		
		if ( ! class_exists( '_WP_Editors' ) ) {
			require( ABSPATH . WPINC . '/class-wp-editor.php' );
		}
		_WP_Editors::wp_link_dialog();
	?>
<?php }


add_filter( 'icl_post_link', 'wpv_provide_edit_view_link', 10, 4 );

/**
 * Provide link for editing Views via icl_post_link.
 *
 * @param array|null|mixed $link
 * @param string $post_type
 * @param int $post_id
 * @param string $link_purpose
 * @return array|null|mixed Link data or $link.
 * @since 1.12
 */
function wpv_provide_edit_view_link( $link, $post_type, $post_id, $link_purpose ) {
	if( WPV_View_Base::POST_TYPE == $post_type && 'edit' == $link_purpose ) {
		$view = WPV_View_Base::get_instance( $post_id );
		if( null != $view && $view->is_a_view() ) {
			$link = array(
				'is_disabled' => false,
				'url' => esc_url_raw(
					add_query_arg(
						array( 'page' => 'views-editor', 'view_id' => $post_id ),
						admin_url( 'admin.php' )
					)
				)
			);
		}
	}
	return $link;
}
