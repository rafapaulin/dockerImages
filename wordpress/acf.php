<?php
// -- Auto import advanced custom fields ------------------------------------------------ //
	if (function_exists('acf_get_field_groups')) {
		/**
		 * Function that will update ACF fields via JSON file update
		 */
		function sync_acf_fields() {
			// vars
			$groups = acf_get_field_groups();
			$sync 	= array();

			// bail early if no field groups
			if( empty($groups) )
				return;

			// find JSON field groups which have not yet been imported
			foreach($groups as $group) {
				$local		=	acf_maybe_get($group, 'local', false);
				$modified	=	acf_maybe_get($group, 'modified', 0);
				$private	=	acf_maybe_get($group, 'private', false);

				// ignore DB / PHP / private field groups
				if( $local !== 'json' || $private ){}
				elseif(!$group['ID'])
					$sync[ $group[ 'key' ] ] = $group;
				elseif( $modified && $modified > get_post_modified_time( 'U', true, $group[ 'ID' ], true ) )
					$sync[ $group[ 'key' ] ]  = $group;
			}

			// bail if no sync needed
			if(empty($sync))
				return;

			if(!empty( $sync)) {
				$new_ids = [];

				foreach($sync as $key => $v) {
					if(acf_have_local_fields($key))
						$sync[ $key ][ 'fields' ] = acf_get_local_fields( $key );

					$field_group = acf_import_field_group( $sync[ $key ] );
				}
			}
		}

		add_action( 'admin_init', 'sync_acf_fields' );
	}
// ------------------------------------------------ Auto import advanced custom fields -- //