<?php
class PagesController extends AppController {
	function display() {
		$params = func_get_args();
		$slug = implode("/", $params);

		$page = $this->Page->findBySlug($slug);

		if ($page["Page"]["id"]) {
			$this->set('page', $page);
		} else {
			//404
			$this->layout = 'page-404';
		}
	}
}
