<?php

namespace Webos\Service;
use Exception;
use Webos\SystemInterface;
use Webos\WorkSpaceHandlers\FileSystem AS FileSystemHanlder;

class ProductionInterface implements UserInterface {
	
	private $_client = null;
	private $_userName = null;
	private $_applicationName = null;
	
	public function __construct(string $userName, string $applicationName) {
		
		$this->_userName        = $userName;
		$this->_applicationName = $applicationName;
		
		$userName = $_SESSION['username' ] ?? $userName;
		$port     = $_SESSION['port' ] ?? null;
		$token    = $_SESSION['token'] ?? null;
		
		if (!$userName || !$port || !$token) {
			$this->_getService();
		}
		$this->_client = new Client($_SESSION['token'], '127.0.0.1', $_SESSION['port']);
	}
	
	private function _getService() {
		$client = new Client('root', '127.0.0.1', 3000);
		$ret = $client->call('create', [
			'userName'        => $this->_userName,
			'applicationName' => $this->_applicationName,
		]);
		$_SESSION['port' ] = $ret['port'];
		$_SESSION['token'] = $ret['token'];
	}
	
	public function renderAll(): string {
		return $this->_call('renderAll');
	}
	
	public function action(string $name, string $objectID, array $parameters, bool $ignoreUpdateObject = false): array {
		$ret = $this->_client->call('action', [
			'name'       => $name,
			'objectID'   => $objectID,
			'parameters' => $parameters,
			'ignoreUpdateObject' => $ignoreUpdateObject,
		]);
		
		foreach($ret['events'] as $event) {
			if ($event['name']=='authUser') {
				session_destroy();
			}
		}
		
		return $ret;
	}
	
	public function debug():void {
		$html = $this->_client->call('debug', [
			'path' => $_REQUEST['__path'] ?? null,
		]);
		echo $html; die();
	}
	
	private function _call(string $actionName, array $params = []) {
		try {
			return $this->_client->call($actionName, $params);
		} catch (Exception $e){
			$this->_getService();
			$this->_client = new Client($_SESSION['token'], '127.0.0.1', $_SESSION['port']);
			return $this->_client->call($actionName, $params);
		}
	}
}