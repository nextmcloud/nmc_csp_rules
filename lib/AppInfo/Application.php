<?php

namespace OCA\Nmcmarketing\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\Security\IContentSecurityPolicyManager;

class Application extends App implements IBootstrap {
	public const APP_ID = 'nmc_marketing';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
	}

	public function boot(IBootContext $context): void {
		$csp = new ContentSecurityPolicy();
		$csp->addAllowedWorkerSrcDomain('\'self\'');
		$csp->addAllowedWorkerSrcDomain('blob:');
		$csp->useStrictDynamic(true);
		$csp->addAllowedFontDomain('https://ebs10.telekom.de');
		$cspManager = $context->getServerContainer()->query(IContentSecurityPolicyManager::class);
		$cspManager->addDefaultPolicy($csp);
	}
}
