<?php

use Webos\FrontEnd\CSS\StylesManager;

$sm = StylesManager::InstanceWithDefinitions();
?>
.DataTable { overflow:hidden; border:solid 1px #fff; }
.DataTable * {box-sizing:border-box !important;}
.DataTable .DataTableRow { height:24px; /*border-bottom:solid 1px #eee;*/ }
/*.DataTable .DataTableRow:hover .DataTableCell{background:#efefef;}*/
.DataTable .DataTableBody .DataTableRow { color:#000; }
.DataTable .DataTableBody .DataTableRow:nth-child(odd) {background:#f1f3f4;}
/*.DataTable .DataTableBody .DataTableRow:hover {background:#ddd;}*/
<?php
$sm->addRule('.DataTable .DataTableBody .DataTableRow:hover')->import('hover');
$sm->addRule('.DataTable .DataTableRow.selected')->import('hover');
$sm->addRule('.DataTable .DataTableRow.selected', [
	'background' => $sm->getPalette('blue') . ' !important',
	'color' => $sm->getPalette('white'),
]);
echo $sm->getStyles();
?>
.DataTable .DataTableHeaders {position:relative; /*border-bottom:solid 1px #ddd;*/ min-width: 100%;}
.DataTable .DataTableHole {position:absolute;top:26px;left:0;bottom:0;right:0;overflow-x:hidden;overflow-y:auto;}
/*.DataTable .DataTableHTrip{overflow:auto;top:0;bottom:0;position:absolute;}*/
.DataTableBody { overflow:auto; min-width: 100%;}
.DataTable .DataTableCell{float:left;overflow:hidden;padding:3px 6px;/*border-right:solid 1px #f6f6f6;*/height:100%;}
.DataTable .DataTableCell.linkable { text-decoration: underline; color: #00f; cursor: pointer; }
/*.DataTableHeaders .DataTableRow { background:#f6f6f6; }*/
.DataTableHeaders .DataTableRow .DataTableCell:hover { text-decoration: underline; }
.DataTableHeaders .DataTableRow:hover { cursor: pointer; }
.DataTableHeaders .DataTableRow .DataTableCell:hover { /*background: #f6f6f6;*/ cursor: pointer; }

.DataTableHeaders .DataTableCell {
	/* margin:0; */ 
	font-weight:bold;
	/*border-right:solid 1px #ddd;*/
}
.DataTableHeaders .DataTableRow, .DataTableHeaders .DataTableCell {
  height: 25px;
}

.DataTableFooters {
	position:absolute;
	bottom:0px;
	font-weight:bold;
}