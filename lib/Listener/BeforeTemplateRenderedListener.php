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

use OC\Security\CSP\ContentSecurityPolicyNonceManager;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\IConfig;
use OCP\IRequest;

class BeforeTemplateRenderedListener implements IEventListener {
	private IConfig $config;
	private IRequest $request;
	private ContentSecurityPolicyNonceManager $nonceManager;
	private array $mobileUserAgents;

	public function __construct(
		IConfig $config,
		IRequest $request,
		ContentSecurityPolicyNonceManager $nonceManager
	) {
		$this->config = $config;
		$this->request = $request;
		$this->nonceManager = $nonceManager;
		$this->mobileUserAgents = $config->getSystemValue('nmc_marketing.mobile_user_agents', [
			'Nextcloud-android',
			'MagentaCLOUD-android',
			'Magenta-android',
			'Nextcloud-iOS',
			'MagentaCLOUD-iOS',
			'Nextcloud iOS',
			'MagentaCLOUD iOS',
			'Magenta-iOS',
			'(Android)',
			'(iOS)',
		]);
	}

	public function handle(Event $event): void {
		$response = $event->getResponse();
		$userAgent = $this->request->getHeader('USER_AGENT');

		// no consent layer for mobile clients
		if (!$this->isMobileUserAgent($userAgent)) {
			$marketing_config = $this->config->getSystemValue("nmc_marketing");
			$utagUrl = $marketing_config['url'];

			// the marketing tooling is controlled by CSP, so save nonce is mandatory
			$nonce = $this->nonceManager->getNonce();

			// we want to invalidate script url remotely with cachebuster
			$cacheBusterVal = $this->config->getAppValue('theming', 'cachebuster', '0');

			// add utag from external CDN
			\OCP\Util::addHeader("script", [ 'nonce' => $nonce, 'src' => $utagUrl . '?nmcv=' . $cacheBusterVal], ''); // the empty text is needed to generate HTML5 valid tags

			// add marketing tracking magic
			\OCP\Util::addScript("nmc_marketing", "consent");
		}
	}

	/**
	 * Check whether request comes from a mobile client
	 */
	private function isMobileUserAgent(string $userAgent): bool {

		foreach ($this->mobileUserAgents as $mobileUserAgent) {
			if (str_contains($userAgent, $mobileUserAgent)) {
				return true;
			}
		}

		return false;
	}
}
