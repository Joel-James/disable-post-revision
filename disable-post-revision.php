<?php

/**
 * Plugin Name:     Disable Post Revision
 * Plugin URI:      https://wordpress.org/plugins/disable-post-revision/
 * Description:     Light weight plugin to disable post revisions for any post type to reduce database load. You can disable revisions by post type.
 * Version:         1.0.1
 * Author:          Joel James
 * Author URI:      https://thefoxe.com/
 * Donate link:     https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XUVWY8HUBUXY4
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     disable-post-revision
 * Domain Path:     /languages
 *
 * Disable Post Revision is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Disable Post Revision is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Disable Post Revision. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category Core
 * @package  JJ4T3
 * @author   Joel James <me@joelsays.com>
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) or exit;

if ( ! class_exists( 'Disable_Post_Revision' ) ) :

    /**
     * The public-facing functionality of the plugin.
     *
     * This class contains the public side functionalities like,
     * logging, redirecting etc.
     *
     * @category   Core
     * @package    DPR
     * @subpackage Public
     * @author     Joel James <me@joelsays.com>
     * @license    http://www.gnu.org/licenses/ GNU General Public License
     * @link       https://thefoxe.com/products/disable-post-revision
     */
    class Disable_Post_Revision {

        /**
         * Initialize the class and set its properties.
         *
         * @since  1.0.0
         * @access public
         *
         * @return void
         */
        public function __construct() {

            add_action( 'admin_init', array( $this, 'disable_revisions' ) );
            add_action( 'admin_init', array( $this, 'options_page' ) );
        }

        /**
         * Remove post revision support for the selected types.
         *
         * @since  1.0.0
         * @access public
         * @uses   get_option()               To get the option value.
         * @uses   remove_post_type_support() To remove the revision feature.
         *
         * @return void
         */
        public function disable_revisions() {

            $post_types = get_option( 'dpr_disabled_types', array() );

            if ( !is_array( $post_types ) || empty( $post_types ) ) {
                return;
            }

            foreach ( $post_types as $post_type ) {

                remove_post_type_support( $post_type, 'revisions' );
            }
        }

        /**
         * Create new options field to the writing settings page.
         *
         * @since  1.0.0
         * @access public
         * @uses   register_setting()   To register new setting.
         * @uses   add_settings_field() To add new field to for the setting.
         *
         * @return void
         */
        public function options_page() {

            register_setting( 'writing', 'dpr_disabled_types' );

            // If options not found, set an empty array.
            if ( !get_option( 'dpr_disabled_types' ) ) {
                update_option( 'dpr_disabled_types', array() );
            }

            add_settings_field(
                'dpr_label', '<label for="dpr">' . __( 'Disable Post Revisions', 'disable-post-revision' ) . '</label>', array( &$this, 'fields' ), 'writing'
            );
        }

        /**
         * Create new options field to the writing settings page.
         *
         * @since  1.0.0
         * @access public
         * @uses   get_option()     To get the option value.
         * @uses   get_post_types() To get the available post types.
         *
         * @return void
         */
        public function fields() {

            // Get settings value.
            $value = get_option( 'dpr_disabled_types', '' );
            // Get available post types.
            $post_types = get_post_types( array(), 'objects' );

            echo '<select id="dpr" name="dpr_disabled_types[]" multiple="multiple">';

            foreach ( $post_types as $post_type => $post ) {
                echo '<option value="' . esc_attr( $post_type ) . '" ' . selected( true, in_array( $post_type, $value ), false ) . ' />' . esc_attr( $post->label ) . '</option>';
            }

            echo '</select>';
            echo '<p class="description">' . __( 'To select multiple post types, hold ctrl key while selecting', 'disable-post-revision' ) . '</p>';
            echo '<p class="description"><strong>' . __( 'Note', '' ) . ':</strong> ' . __( 'Do not select a post type if you are not sure about it', 'disable-post-revision' ) . '</p>';
        }

    }

    // Begins execution of the plugin.
    new Disable_Post_Revision();

endif;

// *** Thank you for your interest in Disable Post Revisions - Developed and managed by Joel James *** //
