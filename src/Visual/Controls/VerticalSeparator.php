<?php
namespace Webos\Visual\Controls;

use Webos\Visual\Control;

class VerticalSeparator extends Control {
	
	public function initialize(array $params = []) {
		$this->top    = 0;
		$this->bottom = 0;
		$this->width  = 5;
	}
	
	/**
	 * Magic setter method for 'left' property
	 */
	public function __set_left($value) {
		$this->getPrevious()->left = 0;
		$this->getPrevious()->width = $value;
		try {
			$this->getNext()->right = 0;
			$this->getNext()->left = $value + $this->width;
			$this->getNext()->width = null;
		} catch (\Exception $e) {
			
		}
		return $value;
	}
	
	/**
	 * Magic setter method for 'right' proerty
	 */
	public function __set_right($value) {
		$this->getPrevious()->left = 0;
		$this->getPrevious()->right = $value + $this->width;
		try {
			$this->getNext()->right = 0;
			$this->getNext()->width = $value;
			$this->getNext()->left  = null;
		} catch (\Exception $e) {
			
		}
		return $value;
	}
	
	/**
	 * Drop user action hander method
	 */
	public function action_drop($params) {
		if (!$this->draggable) {
			return;
		}
		if (!isset($params['left'])) {
			throw new Exception('Missing left parameter');
		}
		$this->left = $params['left']/1;
		$this->triggerEvent('drop', $params);
	}
	
	public function render(): string {
		$style      = $this->getInlineStyle(true);
		$directives = $this->draggable ? 'webos drag-horizontal ondrag-horizontal ondrop': '';
		$class      = ($this->draggable ? 'resize-horizontal' : '') . ' Control VertialSeparator';
		return "<div class=\"{$class}\" id=\"{$this->getObjectID()}\" {$directives} {$style} >&nbsp;</div>";
	}
}