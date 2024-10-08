<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class that handles database queries for the collections metadata.
 *
 */
Class SliceWP_Object_Meta_DB_Collections extends SliceWP_Object_Meta_DB {

	/**
	 * Construct.
	 *
	 */
	public function __construct() {

		global $wpdb;

		$this->table_name 		 = $wpdb->prefix . 'slicewp_collection_meta';
		$this->primary_key 		 = 'slicewp_collection_id';
		$this->context 	  		 = 'collection';

		parent::__construct();

	}


	/**
	 * Return the table columns.
	 *
	 */
	public function get_columns() {

		return array(
			'meta_id' 	    		=> '%d',
			'slicewp_collection_id' => '%d',
			'meta_key' 	   			=> '%s',
			'meta_value'   			=> '%s'
		);

	}


	/**
	 * Creates and updates the database table for the collection.
	 *
	 */
	public function create_table() {

		global $wpdb;

		$table_name 	 = $this->table_name;
		$charset_collate = $wpdb->get_charset_collate();

		$query = "CREATE TABLE {$table_name} (
			meta_id bigint(20) NOT NULL AUTO_INCREMENT,
			slicewp_collection_id bigint(20) NOT NULL DEFAULT '0',
			meta_key varchar(255) DEFAULT NULL,
			meta_value longtext,
			PRIMARY KEY  (meta_id),
			KEY slicewp_collection_id (slicewp_collection_id),
			KEY meta_key (meta_key(191))
		) {$charset_collate};";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $query );

	}

}