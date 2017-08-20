<?php
namespace Webos\Visual\Windows;
use \Webos\Visual\Window;
use \salodev\Utils;
use \Exception;
class Wait extends Window {

	public function initialize(array $params = array()) {
		$this->message = Utils::Ifnull($params['message'], 'Please, wait a moment...');
		$this->width   = Utils::Ifnull($params['width'  ], 350);
		$this->height  = Utils::Ifnull($params['height' ], 130);
		$this->showTitle = false;
	}

	public function  getInitialAttributes(): array {
		return array(
			'height' => 100,
			'width'  => 200
		);
	}
	
	/**
	 * Wait Window is an special case, because is both
	 * not closable by user and is auto closed once
	 * attached proccess finishes.
	 * Also is closed when Exceptions take place.
	 * @throws Exception
	 */
	public function ready() {
		try {
			parent::ready();
		} catch (Exception $e) {
			$this->close();
			throw $e;
		}
		$this->close();
	}
	
	public function render(): string {
		$template = $this->_getRenderTemplate();

		$content = new \Webos\StringChar(
			'<div style="text-align:center;">' .
				'<div style="margin:20px;font-weight:bold;">__MESSAGE__</div>' .
			'</div>'
		);
		
		$content->replace('__OBJECTID__',     $this->getObjectID());		
		$content->replace('__MESSAGE__',      $this->message);
		$template->replace('__TITLE__',       $this->title  );
		$template->replace('__CONTENT__',     $content      );
		
		return $template;
	}
}