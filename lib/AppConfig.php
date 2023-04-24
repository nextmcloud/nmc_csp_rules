<?php
/**
 * Nmcmarketing App
 *
 * @author sangramsinh desai
 * Email  sangramsinh.desai@t-systems.com
 * 
 */

namespace OCA\Nmcmarketing;

use OCA\Nmcmarketing\AppInfo\Application;
use \OCP\IConfig;

class AppConfig {

	/** @var IConfig */
	private $config;

	public function __construct(IConfig $config) {
		$this->config = $config;
	}

	/*
	*Get config values from main server config and create associative array and return that array 
	*/
	public function getConfigValues(){
		$config = \OC::$server->get(IConfig::class);
		$trustedFontUrls = $config->getSystemValue('trusted_font_urls');
		$trustedImageUrlConfig = $config->getSystemValue('trusted_image_urls');
		$trustedConfig = array('trusted_font_urls'=>$trustedFontUrls,'trusted_image_urls'=>$trustedImageUrlConfig);
		return $trustedConfig;
	}	
}
