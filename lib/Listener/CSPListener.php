<?php

declare(strict_types=1);

namespace OCA\Nmcmarketing\Listener;

use OCA\Nmcmarketing\AppInfo\Application;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Util;

use OCA\Nmcmarketing\AppConfig;
use OCP\App\IAppManager;
use OCP\AppFramework\Http\EmptyContentSecurityPolicy;
use OCP\GlobalScale\IConfig as GlobalScaleConfig;
use OCP\IRequest;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;

class CSPListener implements IEventListener {

	private IRequest $request;
	private AppConfig $config;
	private IAppManager $appManager;
	private GlobalScaleConfig $globalScaleConfig;

	public function __construct(IRequest $request, AppConfig $config, IAppManager $appManager, GlobalScaleConfig $globalScaleConfig) {
		$this->request = $request;
		$this->config = $config;
		$this->appManager = $appManager;
		$this->globalScaleConfig = $globalScaleConfig;
	}

	public function handle(Event $event): void {
		if (!$event instanceof AddContentSecurityPolicyEvent) {
			return;
		}

		if (!$this->isPageLoad()) {
			return;
		}

		$urls = array_merge(
			[ $this->domainOnly($this->config->getnmcMarketingUrlPublic()) ],
			$this->getGSDomains()
		);

		$urls = array_filter($urls);

		$policy = new EmptyContentSecurityPolicy();
		$policy->addAllowedFrameDomain("'self'");
		$policy->useStrictDynamic(true);

		foreach ($urls as $url) {
			$policy->addAllowedFrameDomain($url);
			$policy->addAllowedFormActionDomain($url);
			$policy->addAllowedFrameAncestorDomain($url);
			$policy->addAllowedImageDomain($url);
			$policy->addAllowedFontDomain($url);
		}

		$event->addPolicy($policy);
	}

	private function isPageLoad(): bool {
		$scriptNameParts = explode('/', $this->request->getScriptName());
		return end($scriptNameParts) === 'index.php';
	}

	private function getGSDomains(): array {
		if (!$this->globalScaleConfig->isGlobalScaleEnabled()) {
			return [];
		}

		return $this->config->getGlobalScaleTrustedHosts();
	}


	/**
	 * Strips the path and query parameters from the URL.
	 */
	private function domainOnly(string $url): string {
		$parsedUrl = parse_url($url);
		$scheme = isset($parsedUrl['scheme']) ? $parsedUrl['scheme'] . '://' : '';
		$host = $parsedUrl['host'] ?? '';
		$port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
		return "$scheme$host$port";

	}

}
