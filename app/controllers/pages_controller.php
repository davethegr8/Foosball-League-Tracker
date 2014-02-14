<?php
class PagesController extends AppController {
	function display() {
		$params = func_get_args();
		$slug = implode("/", $params);

		$page = $this->Page->find(array('slug' => $slug));

		if ($page["Page"]["id"]) {
			$this->set('page', $page);
		} else {
			//404
			$this->layout = 'page-404';
		}
	}

	function admin_index() {
		$data['pages'] = $this->Page->find('all');

		$this->set($data);
	}
}
