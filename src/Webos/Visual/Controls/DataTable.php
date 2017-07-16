<?php
namespace Webos\Visual\Controls;
use \Exception;
use \Webos\Visual\Controls\DataTable\Column;
class DataTable extends \Webos\Visual\Control {
	public $rowIndex = null;
	public function initialize() {
		$this->rows = array();
		$this->columns = new \Webos\Collection();
		$this->rowIndex = null;
		$this->columInded = null;
	}

	public function getInitialAttributes(): array {
		return array(
			'top'        => 0,
			'bottom'     => 0,
			'left'       => 0,
			'right'      => 0,
			'scrollTop'  => 0,
			'scrollLeft' => 0,
		);
	}

	public function addColumn(string $fieldName, string $label, int $width=100, bool $allowOrder=false, bool $linkable=false, string $align = 'left'): Column {
		// $column = new ColumnDataTable();
		$column = new Column($label, $fieldName);
		$column->width      = $width;
		$column->allowOrder = $allowOrder;
		$column->linkable   = $linkable;
		$column->align      = $align;
		$this->columns->add($column);
		return $column;
	}

	public function getActiveRowData(string $fieldName = null) {
		if ($this->rowIndex !== null) {
			$rowData = $this->getRowData($this->rowIndex/1);
			if ($fieldName) {
				if (!array_key_exists($fieldName, $rowData)) {
					throw new \Exception("The '{$fieldName}' field does not exist.");
				}
				return $rowData[$fieldName];
			}
			return $rowData;
		}
		return null;
	}

	public function getRowData(int $rowIndex): array {
		$i = 0;
		foreach($this->rows as $row) {
			if ($i==$rowIndex) {
				return $row;
			}
			$i++;
		}
		throw new Exception('Requested row does not exists');
	}

	public function rowClick(array $params = array()) {
		if (!isset($params['row'])) {
			throw new Exception('The \'rowClick\' event needs a \'row\' parameter');
		}
		if (!isset($params['fieldName'])) {
			throw new Exception('The \'rowClick\' event needs a \'fieldName\' parameter');
		}
		if ($this->rowIndex !== null && $this->rowIndex == $params['row']) {
			// si clickea en una seleccionada, deselecciona
			$this->rowIndex = null;
		} else {
			// sino, selecciona.
			$this->rowIndex = $params['row']/1;
		}

		$row       = $params['row'];
		$fieldName = $params['fieldName'];
		$rowData   = $this->getRowData($row);
		$cellValue = &$rowData[$fieldName];
		
		$this->triggerEvent('rowClick', array(
			'row'       => $row,
			'fieldName' => $fieldName,
			'rowData'   => $rowData,
			'cellValue' => $cellValue,
		));
	}

	public function rowDoubleClick(array $params = array()) {
		if (!isset($params['row'])) {
			throw new Exception('The \'rowDoubleClick\' event needs a \'row\' parameter');
		}
		$this->rowIndex = $params['row'];
		$this->triggerEvent('rowDoubleClick', array('row'=>$params['row']));
	}

	public function scroll(array $params = array()) {
		//echo "hola";
		$this->scrollTop  = ifempty($params['top'], 0);
		$this->scrollLeft = ifempty($params['left'], 0);
	}

	public function getAllowedActions(): array {
		return array(
			'rowClick',
			'rowDoubleClick',
			'scroll'
		);
	}

	public function getAvailableEvents(): array {
		return array(
			'rowClick',
			'rowDoubleClick',
		);
	}
	
	public function onRowClick(callable $eventListener, bool $persistent = true, array $contextData = []) {
		$this->bind('rowClick', $eventListener, $persistent, $contextData);
	}
	
	public function onRowDoubleClick(callable $eventListener, bool $persistent = true, array $contextData = []) {
		$this->bind('rowDoubleClick', $eventListener, $persistent, $contextData);
	}
	
	public function render(): string {
		$objectID   = $this->getObjectID();

		$scrollTop  = empty($this->scrollTop ) ? 0 : $this->scrollTop ;
		$scrollLeft = empty($this->scrollLeft) ? 0 : $this->scrollLeft;
		$html = '<div id="'.$this->getObjectID().'" class="DataTable" '. $this->getInlineStyle() .'>';
		$rs = $this->rows;
		$bodyWidth = 0;
		foreach($this->columns as $column) {
			$bodyWidth += $column->width+8;
		}
		$html .= '<div class="DataTableHeaders" style="width:'.$bodyWidth.'px">';
		if (count($this->columns)) {			
			$html .= '<div class="DataTableRow">';
			foreach($this->columns as $column) {
				$html .= '<div class="DataTableCell" style="width:' . $column->width . 'px">' . $column->label . '</div>';
			}
			$html .= '</div>';
		} else {
			if (count($rs)) {
				$html .= '<div class="DataTableRow">';
				foreach($rs[0] as $columnName => $value) {
					$html .= '<div class="DataTableCell" style="width:' . $column->width . 'px">' . $columnName . '</div>';
				}
				$html .= '</div>';
			}
		}
		$html .= '</div>'; // end TataTableHeaders
		$html .= '<div class="DataTableHole">';
		$html .= '<div class="DataTableBody" style="width:'.$bodyWidth.'px">';
		foreach($rs as $i => $row) {
			$classSelected = '';
			if ($this->rowIndex!==null && $i == $this->rowIndex) {
				$classSelected = ' selected';
			}
			//$ondblClick = "alert($(this).closest('.DataTable').attr('class'));";
			//$ondblClick = "console.log($(this).closest('.DataTable'));";
			$html .= '<div class="DataTableRow' . $classSelected . '">';
			foreach($this->columns as $column) {
				// $column = (property_exists($column, 'fieldName'))? $column->fieldName : '';
				$onClick    = "__doAction('send', {actionName:'rowClick',objectId:\$(this).closest('.DataTable').attr('id'),row:{$i}, fieldName:'{$column->fieldName}'}); $(this).closest('.DataTableBody').find('.DataTableRow.selected').removeClass('selected'); $(this).closest('.DataTableRow').addClass('selected')";
				$ondblClick = "__doAction('send', {actionName:'rowDoubleClick',objectId:\$(this).closest('.DataTable').attr('id'),row:{$i}, fieldName:'{$column->fieldName}'});";
				$linkable = ($column->linkable) ? ' linkable' : '';
				if (empty($row[$column->fieldName])) {
					$value = '&nbsp;';
				} else {
					$value = $column->renderValue($row[$column->fieldName]);
				}
				$html .= '<div class="DataTableCell' . $linkable . '" style="width:'.$column->width.'px;text-align:'.$column->align.';" onclick="'.$onClick.'" ondblclick="'.$ondblClick.'">' . $value . '</div>';
			}
			$html .= '</div>'; // end DataTableRow
		}
		$html .= '</div>'; // end DataTableBody
		$html .= '</div>'; // end DataTableHole
		$html .= <<<HTML
		<script type="text/javascript">
			$(function() {
				$('#{$objectID} .DataTableHole').attr('disable-scroll-event', 'yes');
				$('#{$objectID} .DataTableHole').scrollTop({$scrollTop});
			 	$('#{$objectID} .DataTableHole').scrollLeft({$scrollLeft});
				setTimeout(function() {
					$('#{$objectID} .DataTableHole').attr('disable-scroll-event', 'NO');
				}, 600); // nunca entendí porqué hace falta esto...
			});
		</script>
HTML;
		$html .= '</div>'; // end DataTable

		return $html;
	}
}