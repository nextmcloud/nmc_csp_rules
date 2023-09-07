<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2023 T-Systems International
 *
 * @author B. Rederlechner <bernd.rederlechner@t-systems.com>
 *
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\NmcMarketing\Listener;

use OC_App;
use OCP\AppFramework\Http;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IConfig;

use OC\Security\CSP\ContentSecurityPolicyNonceManager;

class BeforeTemplateRenderedListener implements IEventListener {
    private IConfig $config;
    private ContentSecurityPolicyNonceManager $nonceManager;

	public function __construct(
        IConfig $config,
        ContentSecurityPolicyNonceManager $nonceManager
	) {
        $this->config = $config;
        $this->nonceManager = $nonceManager;
	}

	public function handle(Event $event): void {
		$response = $event->getResponse();


        $marketing_config = $this->config->getSystemValue("nmc_marketing");
        $utagUrl = $marketing_config['url']; 
        // the marketing tooling is controlled by CSP, so save nonce is mandatory
        $nonce = $this->nonceManager->getNonce();
        // we want to invalidate script url remotely with cachebuster
        $cacheBusterVal = $this->config->getAppValue('theming', 'cachebuster', '0');

        // add utag from external CDN
		\OCP\Util::addHeader("script", 
                            [ 'nonce' => $nonce,
                              'src' =>  $utagUrl . '?nmcv=' . $cacheBusterVal], 
                              ''); // the empty text is needed to generate HTML5 valid tags
        
        // add marketing tracking magic
        \OCP\Util::addScript("nmc_marketing", "consent");
	}
}
