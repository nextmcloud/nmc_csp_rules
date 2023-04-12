<?php

namespace OCA\Nmccsprules\Tests\Unit\Controller;

use PHPUnit_Framework_TestCase;

use OCP\AppFramework\Http\TemplateResponse;

use OCA\Nmccsprules\Controller\PageController;


class PageControllerTest extends PHPUnit_Framework_TestCase {
	private $controller;
	private $userId = 'john';

	public function setUp() {
		$request = $this->getMockBuilder('OCP\IRequest')->getMock();

		$this->controller = new PageController(
			'nmc_csp_rules', $request, $this->userId
		);
	}
}
