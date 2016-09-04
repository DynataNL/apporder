<?php
/**
 * @copyright Copyright (c) 2016 Julius Härtl <jus@bitgrid.net>
 *
 * @author Julius Härtl <jus@bitgrid.net>
 *
 * @license GNU AGPL version 3 or any later version
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\AppOrder\Tests\Unit\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http;
use \OCA\AppOrder\Service;
use \OCA\AppOrder\AppInfo\Application;
use \OCA\AppOrder\Controller;
use OCA\AppOrder\Controller\AppController;

class AppControllerTest extends \Test\TestCase {

	private $container;
	private $request;
	private $service;
	private $urlGenerator;
	private $userId;
	private $appName;
	/**
	 * @var AppController
	 */
	private $controller;
	private $config;
	private $util;

	public function setUp() {

		parent::setUp();

		$app = new \OCA\AppOrder\AppInfo\Application();
		$this->container = $app->getContainer();
		$this->request = $this->getMockBuilder('OCP\IRequest')
			->disableOriginalConstructor()
			->getMock();
		$this->config = $this->getMockBuilder('OCP\IConfig')
			->disableOriginalConstructor()
			->getMock();
		$this->service = $this->getMockBuilder('\OCA\AppOrder\Service\ConfigService')
			->disableOriginalConstructor()
			->getMock();
		$this->urlGenerator = \OC::$server->getURLGenerator();
		$this->util = $this->getMockBuilder('\OCA\AppOrder\Util')->disableOriginalConstructor()->getMock();

		$this->userId = 'admin';
		$this->appName = 'apporder';
		$this->controller = new AppController(
			$this->appName, $this->request, $this->service, $this->urlGenerator, $this->util,
			$this->userId
		);

	}

	public function testIndex() {
  		$this->util->expects($this->once())
			->method('getAppOrder')
			->willReturn(
				json_encode(['/index.php/foo/bar', '/index.php/bar/foo'])
			);
		$expected = new Http\RedirectResponse('/index.php/foo/bar');
		$result = $this->controller->index();
		$this->assertEquals($expected, $result);
	}

	public function testIndexEmpty() {
		$this->util->expects($this->once())
			->method('getAppOrder')
			->willReturn("");
		$result = $this->controller->index();

		$expected = new Http\RedirectResponse('/apps/files/');
		$this->assertEquals($expected, $result);
	}


}