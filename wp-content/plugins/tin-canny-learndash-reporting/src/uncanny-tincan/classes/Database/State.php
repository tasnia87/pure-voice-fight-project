<?php
/**
 * Database\Completion
 *
 * @package    Tin Canny Reporting for LearnDash
 * @subpackage TinCan Module
 * @author     Uncanny Owl
 * @since      1.3.6
 */

namespace UCTINCAN\Database;

if ( !defined( "UO_ABS_PATH" ) ) {
	header( "Status: 403 Forbidden" );
	header( "HTTP/1.1 403 Forbidden" );
	exit();
}

class State extends \UCTINCAN\Database {
	use \UCTINCAN\Modules;

	/**
	 * Get State Data
	 *
	 * @access public
	 * @return string
	 * @since  1.3.6
	 */
	public function get_state( $url, $state_id ) {
		global $wpdb;

		if ( ! $this->is_table_exists() )
			return false;

		$module_id = $this->get_slide_id_from_url( $url );

		$query = sprintf( "
			SELECT `value` FROM %s%s
				WHERE
					`user_id`   = %s AND
					`module_id` = %s AND
					`state`     = '%s'
			LIMIT 1
			", $wpdb->prefix, self::TABLE_RESUME, self::$user_id, $module_id[1], $state_id );

		return $wpdb->get_var( $query );
	}

	/**
	 * Save State Data
	 *
	 * @access public
	 * @return void
	 * @since  1.3.6
	 */
	public function save_state( $url, $state_id, $content ) {
		global $wpdb;

		if ( ! $this->is_table_exists() )
			return false;

		if ( $this->get_state( $url, $state_id ) !== null )
			$this->update_state( $url, $state_id, $content );
		else
			$this->insert_state( $url, $state_id, $content );
	}

	/**
	 * Update State Data
	 *
	 * @access private
	 * @return void
	 * @since  1.3.6
	 */
	private function update_state( $url, $state_id, $content ) {
		global $wpdb;
		$module_id = $this->get_slide_id_from_url( $url );

		$query = sprintf( "
			UPDATE %s%s
				SET `value` = '%s'
				WHERE
					`user_id`   = %s AND
					`module_id` = %s AND
					`state`     = '%s'
		", $wpdb->prefix, self::TABLE_RESUME, $content, self::$user_id, $module_id[1], $state_id );

		$query = $wpdb->query( $query );
	}

	/**
	 * Insert State Data
	 *
	 * @access private
	 * @return void
	 * @since  1.3.6
	 */
	private function insert_state( $url, $state_id, $content ) {
		global $wpdb;
		$module_id = $this->get_slide_id_from_url( $url );

		$query = sprintf( "
			INSERT INTO %s%s
				( `user_id`, `module_id`, `state`, `value` )
				VALUES ( %s, %s, '%s', '%s' );
		", $wpdb->prefix, self::TABLE_RESUME, self::$user_id, $module_id[1], $state_id, $content );

		$query = $wpdb->query( $query );
		if ( $wpdb->last_error ) {
			if ( ! get_option( $wpdb->prefix . self::TABLE_RESUME . '_constraints' ) ) {
				self::update_constraints( self::TABLE_RESUME );
				$query = sprintf( "
						INSERT INTO %s%s
							( `user_id`, `module_id`, `state`, `value` )
							VALUES ( %s, %s, '%s', '%s' );
					", $wpdb->prefix, self::TABLE_RESUME, self::$user_id, $module_id[1], $state_id, $content );
				$query = $wpdb->query( $query );
				update_option( $wpdb->prefix . self::TABLE_RESUME . '_constraints', TRUE );
			}
		}
	}
}
