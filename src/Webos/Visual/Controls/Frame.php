<?php

namespace Webos\Visual\Controls;
use \Webos\Visual\FormContainer;
use \Webos\ObjectsCollection;
class Frame extends \Webos\Visual\Control {
	use FormContainer;

	public function setInitialAttributes(array $userAttrs = array()){
		$attrs = array(
			'width' => 400,
			'height' => 100,
		);

		$this->_attributes = array_merge($attrs, $userAttrs);
	}

	public function getControls(): ObjectsCollection {
		return $this->_childObjects;
	}

	public function getAllowedActions(): array {
		return array();
	}

	public function getAvailableEvents():array  {
		return array();
	}
	
	public function render(): string {
		$html = new \Webos\StringChar('<div class="FrameControl"__style__>__content__</div>');

		$html->replace('__style__',  $this->getInlineStyle(true));
		$html->replace('__content__', $this->controls()->render());
		return $html;
	}
}