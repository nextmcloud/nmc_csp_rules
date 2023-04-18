<?php

namespace OCA\Nmcmarketing\AppInfo;

// use OCA\Richdocuments\Capabilities;
use OCA\Nmcmarketing\Listener\CSPListener;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Files\Template\FileCreatedFromTemplateEvent;
use OCP\Files\Template\ITemplateManager;
use OCP\Files\Template\TemplateFileCreator;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IPreview;
use OCP\Preview\BeforePreviewFetchedEvent;
use OCP\Security\CSP\AddContentSecurityPolicyEvent;

class Application extends App implements IBootstrap {
	public const APP_ID = 'nmc_marketing';
	public const APPNAME = 'nmc_marketing';

	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerEventListener(AddContentSecurityPolicyEvent::class, CSPListener::class);
	}

	public function boot(IBootContext $context): void {
		/*$csp = new ContentSecurityPolicy();
		$csp->addAllowedWorkerSrcDomain('\'self\'');
		$csp->addAllowedWorkerSrcDomain('blob:');
		$csp->useStrictDynamic(true);
		$csp->addAllowedFontDomain('https://ebs10.telekom.de');
		$cspManager = $context->getServerContainer()->query(IContentSecurityPolicyManager::class);
		$cspManager->addDefaultPolicy($csp);*/
	}
}
