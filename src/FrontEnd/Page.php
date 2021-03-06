<?php

namespace Webos\FrontEnd;

use \Webos\Webos;

class Page implements PageWrapper {
	
	private $body = null;

	public function setContent(string $html): PageWrapper {
		$this->body = $html;
		return $this;
	}

	public function show(): void {
		echo $this->renderTemplate($this->body);
	}
	
	public function getHTML(): string {
		return $this->renderTemplate($this->body);
	}

	private function renderTemplate(string $content): string {
		$title = Webos::GetTitle();
		$html =
			'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' .
			'<html xmlns="https://www.w3.org/1999/xhtml">' .
				'<head>' .
					'<meta name="viewport" content= "width=device-width, initial-scale=1.0, user-scalable=no" />' .
					'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>' .
					'<title>' . htmlentities($title) . '</title>';
					if (Webos::GetFavicon()) {
						$html .= '<link rel="shortcut icon" type="image/png" href="' . Webos::GetFavicon() . '"/>';
					}
					$html .=
					'<link rel="stylesheet" href="css/Window.css" />' .
					'<link rel="stylesheet" href="css/Toolbar.css" />' .
					'<link rel="stylesheet" href="css/styles.css" />' .
					'<link rel="stylesheet" href="css/Grid.css" />' .
					'<link rel="stylesheet" href="css/Desktop.css" />' .
					'<link rel="stylesheet" href="css/MultiTab.css" />' .
					'<link rel="stylesheet" href="css/TreeControl.css" />' .
					'<link rel="stylesheet" href="css/menu.css" />' .
					'<link rel="stylesheet" href="css/new.css" />' .
					'<link rel="stylesheet" href="css/DataTable.css" />' .
					// '<link rel="stylesheet" href="css/webos.css" />' .
				
					// '<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>' .
					'<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>' .
					'<script type="text/javascript" src="js/jquery.easydrag.js"></script>' .
					// '<script type="text/javascript" src="js/jquery.dragndrop.js"></script>' .
					'<script type="text/javascript" src="js/engines/actionEngine.js"></script>' .
					'<script type="text/javascript" src="js/engines/eventEngine.js"></script>' .
					'<script type="text/javascript" src="js/engines/directives.js"></script>' .
					'<script type="text/javascript" src="js/Queue.js"></script>' .
					'<script type="text/javascript" src="js/Webos.js"></script>' .
					'<script type="text/javascript" src="js/KeyboardDispatcher.js"></script>' .
					'<script type="text/javascript">Webos.endPoint=\''. Webos::GetUrl(). '\'</script>' .
					'<script type="text/javascript" src="js/custom.js"></script>' .
					'<script type="text/javascript" src="js/directives.js"></script>' .
				'</head>' .
				'<body>' . $content .
				'</body>' .
			'</html>';

		return $html;
	}
}