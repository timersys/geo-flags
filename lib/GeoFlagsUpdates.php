<?php
use GeotFunctions\GeotUpdates;

class GeoFlagsUpdates {
	/**
	 * Handle Licences and updates
	 * @since 1.0.0
	 */
	public static function handle_updates(){
		$opts = geot_settings();
		// Setup the updater
		return new GeotUpdates( GEOF_PLUGIN_FILE, [
				'version'   => GEOF_VERSION,
				'license'   => isset($opts['license']) ? $opts['license'] : ''
			]
		);
	}
}