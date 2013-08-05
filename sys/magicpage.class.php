<?php
if (!defined('LCMS')) exit;

class MagicPage extends Database {

	public function __construct() {
		parent::__construct();
	}

	public function createPage($type,$title=NULL) {
		// create a new page, and return its id
		$defaultPage = '<h1>'.htmlentities($title).'</h1>
		<p>This is some example text, click edit to change it!</p>';

		$this->query('INSERT INTO pages (type,html) VALUES (:type,:html)');
		$this->bind(':type', $type);
		$this->bind(':html', $defaultPage);

		$created = $this->execute();
		$id = $this->lastInsertId();
		if($created != false){
			return $id;
		}
				
		return false;
	}

	public function updatePage($id,$html) {
		// load page record

		$this->query('SELECT id,html FROM pages WHERE id = :id');
		$this->bind(':id', $id);
		$page = $this->single(); // get the record

		if($this->rowCount() == 1) { // if the user exists

			$this->query('UPDATE pages SET html=:html WHERE id=:id');
			$this->bind(':id', $id);
			$this->bind(':html', $html);
			$this->execute();

		} else {
			return false; // no page = false
		}
	}

	public function renderPage($id) {
		// load page record
		$auth = new Auth();
		$html = '';

		$includeEditing = false;

		$this->query('SELECT id,html FROM pages WHERE id = :id');
		$this->bind(':id', $id);
		$page = $this->single(); // get the record

		if($this->rowCount() == 1) { // if the user exists

			$includeEditing = false;
			if($auth->isAdmin()) {
				$includeEditing = true;
				$html .= '<div class="mp_page" id="mppage_'.$page['id'].'" data-mercury="full">';
			}

			$html .= $page['html'];

			if($includeEditing) {
				$html .= '</div>';
			}
			return $html; // send back full page


		} else {
			return false; // no page = false
		}
	}

}

 
?>