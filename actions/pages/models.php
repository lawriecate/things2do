<?php
if (!defined('LCMS')) exit;

class StaticPage extends Database {

	public function __construct() {
		parent::__construct();
	}

	public function addStatic($title) {

		// create a new magicpage for the article body
		$mp = new MagicPage();
		$pageId = $mp->createPage('statics',$title);

		// put into articles table
		$this->query('INSERT INTO statics (title,slug,pageref) VALUES (:title,:slug,:pageref)');
		$this->bind(':title', $title);
		$this->bind(':slug', toAscii($title));
		$this->bind(':pageref',$pageId);

		$created = $this->execute();
		if($created != false){
			return $this->lastInsertId();
		}
				
		return false;
	}

	public function getStatic($idt) {
		// load article record

		$this->query('SELECT id,title,slug,pageref FROM statics WHERE slug = :idt OR id = :idt');
		$this->bind(':idt', $idt);
		$static = $this->single(); // get the record

		if($this->rowCount() == 1) { // if the user exists
			
			$mp = new MagicPage();
			$html = $mp->renderPage($static['pageref']);
			$static['html'] = $html;
			$static['url'] = ROOT_URL . 'pages/' . $static['slug'];
			
			return $static;
		} else {
			return false;
		}
	}


}

 
?>