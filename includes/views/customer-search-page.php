<style type="text/css">
	.qbo-customer-search-wrap .qb-company-name {
		margin: 0 0 1em;
	}
	li.error {
		color: #dc3232;
	}
	.wp-core-ui .button-secondary.update-user-from-qb {
		margin-top: 10px;
	}
</style>

<?php settings_errors( self::$admin_page_slug . '-notices' ); ?>

<div class="wrap qbo-customer-search-wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>
	<p class="qb-company-name"><?php printf( __( 'Your company: <em>%s</em> ', 'zwqoi' ), $this->company_name() ); ?></p>

	<?php if ( ! function_exists( 'qbo_connect_ui' ) || ! qbo_connect_ui()->settings ) { ?>

		<p><?php _e( 'Something went wrong. We cannot find the Quickbooks Connect UI plugin.', 'zwqoi' ); ?></p>

	<?php } elseif ( ! self::$api ) { ?>

		<p><?php printf( __( 'You need to <a href="%s">initate the Quickbooks connection</a>.', 'zwqoi' ), qbo_connect_ui()->settings->settings_url() ); ?></p>

	<?php } else { ?>

		<form method="POST" id="qbo-customer-search-form" action="<?php echo esc_url( self::settings_url() ); ?>">
			<?php wp_nonce_field( self::$admin_page_slug, self::$admin_page_slug ); ?>
			<input class="large-text" placeholder="<?php esc_attr_e( 'Company Name or Id', 'zwqoi' ); ?>" type="text" name="search_term" value="<?php echo esc_attr( self::_param( 'search_term' ) ); ?>">
			<p><?php _e( 'Search by:', 'zwqoi' ); ?>
				&nbsp;
				<label><input type="radio" name="search_type" value="company_name" <?php checked( ! isset( $_POST['search_type'] ) || self::_param_is( 'search_type', 'company_name' ) ); ?> /> <?php _e( 'Company Name', 'zwqoi' ); ?></label>
				&nbsp;
				<label><input type="radio" name="search_type" value="id" <?php checked( self::_param_is( 'search_type', 'id' ) ); ?>/> <?php _e( 'Company ID', 'zwqoi' ); ?></label>
			</p>
			<?php submit_button( __( 'Search for Company', 'zwqoi' ) ); ?>
		</form>

	<?php } ?>

	<?php if ( $this->has_search() ) { ?>
		<h3><?php printf( __( 'Search Results for &ldquo;%s&rdquo; (found <strong>%d</strong> result): ', 'zwqoi' ), esc_attr( wp_unslash( $_POST['search_term'] ) ), $this->results_count ); ?></h3>
		<p class="description"><?php _e( 'Click on one of the results to import the result as a WordPress user.', 'zwqoi' ); ?></p>
		<ul>
			<?php foreach ( $this->search_results as $result ) { ?>
				<?php if ( 'error' === $result['id'] ) { ?>
					<li class="error"><?php echo $result['name']; ?></li>
				<?php } else { ?>
					<li><span class="dashicons dashicons-download"></span> <a href="<?php echo esc_url( self::import_customer_url( $result['id'] ) ); ?>"><?php echo $result['name']; ?></a></li>
				<?php } ?>
			<?php } ?>
		</ul>
	<?php } ?>

</div>
