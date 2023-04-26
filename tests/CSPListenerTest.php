<?php

declare(strict_types=1);

/**
 *
 * @author Sangramsinh Desai <sangramsinh.desai@t-systems.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 */

use OC\Security\CSP\ContentSecurityPolicyManager;
use OCA\Nmcmarketing\AppConfig;
use OCA\Nmcmarketing\Listener\CSPListener;
use OCP\App\IAppManager;
use OCP\AppFramework\Http\ContentSecurityPolicy;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\IRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CSPListenerTest extends TestCase {

	/** @var IRequest|MockObject */
	private $request;
	/** @var AppConfig|MockObject */
	private $config;

	public function setUp(): void {
		parent::setUp();

		$this->request = $this->createMock(IRequest::class);
		$this->config = $this->createMock(AppConfig::class);
		$this->listener = new CSPListener(
			$this->request,
			$this->config
		);
	}

	private function getMergedPolicy(): ContentSecurityPolicy {
		$eventDispatcher = $this->createMock(IEventDispatcher::class);
		$eventDispatcher->expects(self::once())
			->method('dispatchTyped')
			->willReturnCallback(function ($event) {
				$this->listener->handle($event);
			});
		$manager = new ContentSecurityPolicyManager($eventDispatcher);

		return $manager->getDefaultPolicy();
	}

	private function checkFontDomain() {
		$policy = $this->getMergedPolicy();
		$expectedPolicy = "default-src 'none';base-uri 'none';manifest-src 'self';script-src 'self';style-src 'self' 'unsafe-inline';img-src 'self' data: blob:;font-src 'self' data: ebs10.telekom.de/opt-in;connect-src 'self';media-src 'self';frame-ancestors 'self';form-action 'self'";
		$policy->addAllowedFontDomain('ebs10.telekom.de/opt-in');
		$policy->useStrictDynamic(true);
		$this->assertSame($expectedPolicy, $policy->buildPolicy());
	}

	private function checkImageDomain() {
		$policy = $this->getMergedPolicy();
		$expectedPolicy = "default-src 'none';base-uri 'none';manifest-src 'self';script-src 'self';style-src 'self' 'unsafe-inline';img-src 'self' data: blob: pix.telekom.de fbc.wcfbc.net;font-src 'self' data:;connect-src 'self';media-src 'self';frame-ancestors 'self';form-action 'self'";
		$policy->addAllowedImageDomain('pix.telekom.de');
		$policy->addAllowedImageDomain('fbc.wcfbc.net');
		$policy->useStrictDynamic(true);
		$this->assertSame($expectedPolicy, $policy->buildPolicy());
	}

	public function testHandle() {
		$this->checkFontDomain();
		$this->checkImageDomain();
	}

}

