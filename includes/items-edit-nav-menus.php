<?php

// includes/items-edit-nav-menus

/**
 * Prevent direct access to this file.
 *
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Sorry, you are not allowed to access this file directly.' );
}


add_action( 'admin_bar_menu', 'ddw_tbex_list_edit_nav_menus', 500 );
/**
 * Add edit Nav Menu items for all existing Nav Menus.
 *
 * @since 1.0.0
 *
 * @uses ddw_tbex_get_menu_id_from_menu_location()
 * @uses wp_get_nav_menus()
 *
 * @global mixed $GLOBALS[ 'wp_admin_bar' ]
 */
function ddw_tbex_list_edit_nav_menus() {

	/** Add sub group for site "Menus" item */
	$GLOBALS[ 'wp_admin_bar' ]->add_group(
		array(
			'parent' => 'wpmenus',
			'id'     => 'group-list-menus'
		)
	);

	$edit_menu_id_super_admin = ddw_tbex_get_menu_id_from_menu_location( 'tbex_menu' );

	$nav_menus = wp_get_nav_menus();

	foreach ( $nav_menus as $nav_menu ) {

		/** Check for menu + cap */
		if ( is_super_admin() /*current_user_can( 'edit_theme_options' )*/
			&& $edit_menu_id_super_admin !== $nav_menu->term_id
			/* && $edit_menu_id_admin != $nav_menu->term_id */
		) {

			$GLOBALS[ 'wp_admin_bar' ]->add_node(
				array(
					'id'     => 'tbex-edit-menu-' . absint( $nav_menu->term_id ),
					'parent' => 'group-list-menus',
					'title'  => esc_attr__( 'Edit Menu:', 'toolbar-extras' ) . ' ' . esc_html( $nav_menu->name ),
					'href'   => esc_url( admin_url( 'nav-menus.php?action=edit&menu=' . $nav_menu->term_id . '' ) ),
					'meta'   => array(
						'target' => '',
						'title'  => esc_attr__( 'Edit Menu:', 'toolbar-extras' ) . ' ' . esc_html( $nav_menu->name )
					)
				)
			);

		}  // end if

	}  // end foreach

}  // end function


add_action( 'admin_bar_menu', 'ddw_tbex_edit_custom_toolbar_menus', 1000 );
/**
 * Add edit Nav Menu item for our own custom Super Admin Toolbar Menu.
 *
 * @since 1.0.0
 *
 * @uses ddw_tbex_get_menu_id_from_menu_location()
 *
 * @global mixed $GLOBALS[ 'wp_admin_bar' ]
 */
function ddw_tbex_edit_custom_toolbar_menus() {

	/** Bail early if no super admin */
	if ( ! is_super_admin() ) {
		return;
	}

	/** Add sub group for site "Menus" item */
	$GLOBALS[ 'wp_admin_bar' ]->add_group(
		array(
			'parent' => 'wpmenus',
			'id'     => 'group-list-tbex-menus',
			'meta'   => array( 'class' => 'ab-sub-secondary' )
		)
	);

	/** Get menu IDs of our custom menus */
	$edit_menu_id_super_admin = ddw_tbex_get_menu_id_from_menu_location( 'tbex_menu' );
	$menu_obj_super_admin     = get_term( $edit_menu_id_super_admin, 'nav_menu' );

	/** Check for menu + cap */
	if ( ! empty( $edit_menu_id_super_admin ) ) {

		$GLOBALS[ 'wp_admin_bar' ]->add_node(
			array(
				'id'     => 'tbex-superadmin-menu',
				'parent' => 'group-list-tbex-menus',
				'title'  => esc_attr__( 'Toolbar Menu:', 'toolbar-extras' ) . ' ' . esc_html( $menu_obj_super_admin->name ),
				'href'   => esc_url( admin_url( 'nav-menus.php?action=edit&menu=' . $edit_menu_id_super_admin . '' ) ),
				'meta'   => array(
					'target' => '',
					'title'  => esc_attr__( 'Edit Super Admin Toolbar Menu:', 'toolbar-extras' ) . ' ' . esc_html( $menu_obj_super_admin->name )
				)
			)
		);

	}  // end if

}  // end function
