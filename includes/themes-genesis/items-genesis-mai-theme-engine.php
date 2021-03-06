<?php

// includes/themes-genesis/items-genesis-mai-theme-engine

/**
 * Prevent direct access to this file.
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


add_filter( 'tbex_filter_items_theme_customizer_deep', 'ddw_tbex_themeitems_mai_theme_engine_customize' );
/**
 * Customize items for Genesis Child Theme:
 *   All themes of the "Mai" brand, based on the "Mai Theme Engine" plugin.
 *   - Mai Lifestyle Pro
 *   - Mai Law Pro
 *   All child themes: Premium, by Mike Hemberger, BizBudding Inc.
 *
 * @since 1.0.0
 * @since 1.4.0 Refactored and generalized, based on the "Mai Theme Engine"
 *              plugin instead of the specific Mai Theme. Now using filter/
 *              array declaration.
 *
 * @uses mai_get_cpt_settings_post_types() Gets all supported post types.
 *
 * @param array $items Existing array of params for creating Toolbar nodes.
 * @return array Tweaked array of params for creating Toolbar nodes.
 */
function ddw_tbex_themeitems_mai_theme_engine_customize( array $items ) {

	/** Bail early if we are not in a "Mai" context */
	if ( ! function_exists( 'mai_get_cpt_settings_post_types' ) ) {
		return $items;
	}

	/** Remove default item (to re-add at another position) */
	if ( isset( $items[ 'title_tagline' ] ) ) {
		$items[ 'title_tagline' ] = null;
	}

	/** Declare theme's items */
	$mte_items = array(
		'mai_header_footer' => array(
			'type'  => 'section',
			'title' => __( 'Mai Header &amp; Footer', 'toolbar-extras' ),
			'id'    => 'mtecmz-header-footer',
		),
		'mai_site_layout' => array(
			'type'  => 'section',
			'title' => __( 'Mai Site Layouts', 'toolbar-extras' ),
			'id'    => 'mtecmz-site-layouts',
		),
		'mai_banner_area' => array(
			'type'  => 'section',
			'title' => __( 'Mai Banner Area', 'toolbar-extras' ),
			'id'    => 'mtecmz-banner-area',
		),
		'mai_content_archives' => array(
			'type'  => 'section',
			'title' => __( 'Mai Content Archives', 'toolbar-extras' ),
			'id'    => 'mtecmz-content-archives',
		),
		'mai_content_singular' => array(
			'type'  => 'section',
			'title' => __( 'Mai Content Singular', 'toolbar-extras' ),
			'id'    => 'mtecmz-content-singular',
		),
	);

	/**
	 * Mai Content Types - for every supported post type
	 *   Note: This happens dynamically based on mai_get_cpt_settings_post_types()
	 *         and Genesis CPT archives settings.
	 */
	$post_types = mai_get_cpt_settings_post_types();

	foreach ( $post_types as $post_type => $object ) {

		$cpt_title = sprintf(
			/* translators: %s - Post type name (plural) */
			__( 'Mai Content Type: %s', 'toolbar-extras' ),
			$object->labels->name
		);

		$mte_items[ 'mai_' . $post_type . '_cpt_settings' ] = array(
			'type'        => 'section',
			'title'       => $cpt_title,
			'id'          => 'mtecmz-content-type-' . $post_type,
			'preview_url' => get_post_type_archive_link( $post_type ),
		);

	}  // end if

	/** Re-add here */
	$mte_engine[ 'title_tagline' ] = array(
		'type' => 'section',
	);

	/** Merge and return with all items */
	return array_merge( $items, $mte_items );

}  // end function


add_action( 'admin_bar_menu', 'ddw_tbex_aoitems_mai_demo_importer', 100 );
/**
 * Items for Add-On: Mai Demo Importer (free, by Mike Hemberger, BizBudding Inc.)
 *
 * @since 1.4.0
 *
 * @uses ddw_tbex_display_items_demo_import()
 * @uses ddw_tbex_item_title_with_settings_icon()
 *
 * @global mixed $GLOBALS[ 'wp_admin_bar' ]
 */
function ddw_tbex_aoitems_mai_demo_importer() {

	/** Bail early if no display of Demo Import items */
	if ( ! ddw_tbex_display_items_demo_import() || ! class_exists( 'Mai_Demo_Importer' ) ) {
		return;
	}

	$GLOBALS[ 'wp_admin_bar' ]->add_node(
		array(
			'id'     => ddw_tbex_id_sites_browser(),
			'parent' => 'group-demo-import',
			'title'  => ddw_tbex_item_title_with_settings_icon( esc_attr__( 'Import Demo Data', 'toolbar-extras' ), 'general', 'demo_import_icon' ),
			'href'   => esc_url( admin_url( 'admin.php?page=mai-demo-importer' ) ),
			'meta'   => array(
				'target' => ddw_tbex_meta_target(),
				'title'  => esc_attr__( 'Import Demo Data', 'toolbar-extras' )
			)
		)
	);

}  // end function
