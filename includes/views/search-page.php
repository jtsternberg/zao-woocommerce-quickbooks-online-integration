<style type="text/css">
	li.error {
		color: #dc3232;
	}
	.wp-core-ui .qb-import-button-wrap {
		margin: 10px 0 5px;
		display: block;
	}
</style>

<?php settings_errors( $this->admin_page_slug . '-notices' ); ?>

<div class="wrap qbo-search-wrap">
	<?php self::admin_page_title(); ?>

	<?php if ( self::$api && self::company_name() ) { ?>
		<p class="qb-company-name"><?php printf( __( 'Your company: <em>%s</em> ', 'zwqoi' ), self::company_name() ); ?></p>
	<?php } ?>

	<?php if ( ! function_exists( 'qbo_connect_ui' ) || ! qbo_connect_ui()->settings ) { ?>

		<p><?php _e( 'Something went wrong. We cannot find the Quickbooks Connect UI plugin.', 'zwqoi' ); ?></p>

	<?php } elseif ( ! self::$api || is_wp_error( self::$api->get_company_info() ) ) { ?>

		<p><?php echo Zao\WC_QBO_Integration\Admin\Settings::initation_required_message(); ?></p>

	<?php } else { ?>

		<form method="POST" id="qbo-search-form" action="<?php echo esc_url( $this->settings_url( $_GET ) ); ?>">
			<?php wp_nonce_field( $this->admin_page_slug, $this->admin_page_slug ); ?>
			<?php do_action( 'zwqoi_search_page_form', $this ); ?>
			<input class="large-text" placeholder="<?php echo esc_attr( $this->get_text( 'search_placeholder' ) ); ?>" type="text" name="search_term" value="<?php echo esc_attr( wp_unslash( self::_param( 'search_term' ) ) ); ?>">
			<p><?php _e( 'Search by:', 'zwqoi' ); ?>
				&nbsp;
				<label><input type="radio" name="search_type" value="name" <?php checked( ! isset( $_POST['search_type'] ) || self::_param_is( 'search_type', 'name' ) ); ?> /> <?php $this->get_text( 'object_single_name_name', true ); ?></label>
				&nbsp;
				<label><input type="radio" name="search_type" value="id" <?php checked( self::_param_is( 'search_type', 'id' ) ); ?>/> <?php $this->get_text( 'object_id_name', true ); ?></label>
				<?php do_action( 'zwqoi_search_page_form_search_types', $this ); ?>
			</p>
			<?php submit_button( $this->get_text( 'submit_button' ) ); ?>
		</form>

	<?php } ?>

	<?php if ( $this->has_search() ) { ?>
		<h3><?php printf( __( 'Search Results for &ldquo;%s&rdquo; (found <strong>%d</strong> result): ', 'zwqoi' ), esc_attr( wp_unslash( self::_param( 'search_term' ) ) ), $this->results_count ); ?></h3>
		<p class="description"><?php $this->get_text( 'search_help', true ); ?></p>
		<form method="POST" id="qbo-items-import" action="<?php echo esc_url( $this->settings_url( $_GET ) ); ?>">
			<?php wp_nonce_field( $this->import_query_var, $this->import_query_var ); ?>
			<?php wp_nonce_field( $this->admin_page_slug, 'nonce' ); ?>
			<?php do_action( 'zwqoi_search_page_import_results_form', $this ); ?>
			<table class="wp-list-table widefat fixed striped posts">
				<thead>
					<tr>
						<td class="manage-column column-cb check-column">
							<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
							<input id="cb-select-all-1" type="checkbox">
						</td>
						<th class="manage-column column-title column-primary">
							<span><?php _e( 'Name', 'zwqoi' ); ?></span>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $this->search_results as $result ) { ?>
						<?php $this->output_result_item( $result ); ?>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<td class="manage-column column-cb check-column">
							<label class="screen-reader-text" for="cb-select-all-2">Select All</label>
							<input id="cb-select-all-2" type="checkbox">
						</td>
						<th class="manage-column column-title column-primary">
							<span><?php _e( 'Name', 'zwqoi' ); ?></span>
						</th>
					</tr>
				</tfoot>
			</table>
			<?php submit_button( __( 'Import Item(s)', 'zwqoi' ) ); ?>
		</form>
	<?php } ?>

</div>
