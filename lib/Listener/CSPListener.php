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

	public function __construct(IRequest $request, AppConfig $config) {
		$this->request = $request;
		$this->config = $config;
	}

	public function handle(Event $event): void {
		if (!$event instanceof AddContentSecurityPolicyEvent) {
			return;
		}

		if (!$this->isPageLoad()) {
			return;
		}

		$configValues = $this->config->getConfigValues();
		$policy = new EmptyContentSecurityPolicy();
		$policy->addAllowedFrameDomain("'self'");
		$policy->useStrictDynamic(true);
		
		foreach ($configValues['trusted_font_urls'] as $trusted_url) {
			$policy->addAllowedFontDomain($trusted_url);
		}
		foreach ($configValues['trusted_image_urls'] as $image_url) {
			$policy->addAllowedImageDomain($image_url);
		}
		$event->addPolicy($policy);
	}

	private function isPageLoad(): bool {
		$scriptNameParts = explode('/', $this->request->getScriptName());
		return end($scriptNameParts) === 'index.php';
	}
}