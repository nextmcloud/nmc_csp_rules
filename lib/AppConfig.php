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
	* Return trusted font urls array from server config
	*/
	public function getTrustedFontUrls(){
		return $this->config->getSystemValue('trusted_font_urls');
	}

	/*
	* Return trusted image urls array from server config
	*/
	public function getTrustedImageUrls(){
		return $this->config->getSystemValue('trusted_image_urls');
	}

}
