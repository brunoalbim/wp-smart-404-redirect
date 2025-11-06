<?php
/**
 * Plugin Name: WP Smart 404 Redirect
 * Description: Plugin que intercepta erros 404 e redireciona automaticamente para URLs corretas baseadas no slug do post
 * Version: 1.0.0
 * Author: Bruno Albim
 * Author URI: https://bruno.art.br
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-smart-404-redirect
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Main plugin class
 */
class WP_Smart_404_Redirect {

    /**
     * Instance of this class
     *
     * @var WP_Smart_404_Redirect
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return WP_Smart_404_Redirect
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action( 'template_redirect', array( $this, 'handle_404_redirect' ), 1 );
    }

    /**
     * Handle 404 errors and attempt to redirect
     */
    public function handle_404_redirect() {
        // Only proceed if this is a 404 error
        if ( ! is_404() ) {
            return;
        }

        // Don't redirect admin, ajax or feed requests
        if ( is_admin() || wp_doing_ajax() || is_feed() ) {
            return;
        }

        // Extract slug from current URL
        $slug = $this->extract_slug_from_url();

        // If no slug found, return
        if ( empty( $slug ) ) {
            return;
        }

        // Try to find post by slug
        $post = $this->find_post_by_slug( $slug );

        // If post found, redirect to correct URL
        if ( $post ) {
            $this->redirect_to_correct_url( $post );
        }
    }

    /**
     * Extract slug from the current URL
     *
     * @return string|null
     */
    private function extract_slug_from_url() {
        // Get the current requested URL
        $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
        
        if ( empty( $request_uri ) ) {
            return null;
        }

        // Remove query string if present
        $request_uri = strtok( $request_uri, '?' );

        // Get the WordPress base path (handles installations in subdirectories)
        $home_path = trim( parse_url( home_url(), PHP_URL_PATH ), '/' );
        
        // Remove the WordPress base path from request URI
        if ( ! empty( $home_path ) ) {
            $request_uri = preg_replace( '#^' . preg_quote( $home_path, '#' ) . '/#', '', trim( $request_uri, '/' ) );
        }

        // Remove leading and trailing slashes
        $request_uri = trim( $request_uri, '/' );

        // Split by slash and get the first segment
        $segments = explode( '/', $request_uri );

        if ( empty( $segments[0] ) ) {
            return null;
        }

        $slug = $segments[0];

        // Remove any potential ID suffix (e.g., "slug-12345" becomes "slug")
        // Check if the slug ends with a dash followed by numbers
        if ( preg_match( '/^(.+)-(\d+)$/', $slug, $matches ) ) {
            $slug = $matches[1];
        }

        // Sanitize the slug
        $slug = sanitize_title( $slug );

        return $slug;
    }

    /**
     * Find post by slug
     *
     * @param string $slug
     * @return WP_Post|null
     */
    private function find_post_by_slug( $slug ) {
        // Validate slug
        if ( empty( $slug ) ) {
            return null;
        }

        // Query for posts with the given slug
        $args = array(
            'name'           => $slug,
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'no_found_rows'  => true,
            'orderby'        => 'ID',
            'order'          => 'ASC'
        );

        $query = new WP_Query( $args );

        // Return the first post found or null
        if ( $query->have_posts() ) {
            return $query->posts[0];
        }

        return null;
    }

    /**
     * Redirect to the correct URL
     *
     * @param WP_Post $post
     */
    private function redirect_to_correct_url( $post ) {
        // Validate post object
        if ( ! $post || ! isset( $post->ID ) || ! isset( $post->post_name ) ) {
            return;
        }

        // Build the correct URL: {site_url}/{slug}-{ID}/
        $correct_url = home_url( '/' . $post->post_name . '-' . $post->ID . '/' );

        // Get current URL for comparison
        $current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        // Avoid redirect loops - check if we're already on the correct URL
        if ( untrailingslashit( $current_url ) === untrailingslashit( $correct_url ) ) {
            return;
        }

        // Perform 301 permanent redirect
        wp_redirect( $correct_url, 301 );
        exit;
    }
}

/**
 * Initialize the plugin
 */
function wp_smart_404_redirect_init() {
    return WP_Smart_404_Redirect::get_instance();
}

// Start the plugin
add_action( 'plugins_loaded', 'wp_smart_404_redirect_init' );
