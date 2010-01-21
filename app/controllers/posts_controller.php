<?php

class PostsController extends AppController {

	var $name = 'Posts';

	function beforeFilter() {
		$this->__validateAdminLogin();
	}

	function index() {
		$this->set('posts', $this->Post->find('all', array('order' => 'created DESC')));
	}

	function view($id) {
		$this->Post->id = $id;
		$this->set('post', $this->Post->read());
	}

	function rss() {
		$this->layout = 'rss';

		$this->set('posts', $this->Post->find('all', array('order' => 'created DESC')));
	}

	function admin_index() {
		$data['posts'] = $this->Post->find('all');

		$this->set($data);
	}

	function admin_add() {

		if(empty($this->data) == false) {

			$result = $this->Post->save($this->data);
			if(empty($result) == false) {
				$this->Session->setFlash('Post created.');
				$this->redirect('/admin/posts');
			}
			else {
				$this->Session->setFlash('Error.');
			}
		}

		if(!isset($this->data["Post"]["active"])) {
			$this->data["Post"]["active"] = 0;
		}
	}

	function admin_edit($id) {

		if(empty($this->data)) {
			$this->Post->id = $id;
			$this->data = $this->Post->read();
		}
		else {
			if(!isset($this->data["Post"]["active"])) {
				$this->data["Post"]["active"] = 0;
			}

			$result = $this->Post->save($this->data);
			if(empty($result) == false) {
				$this->Session->setFlash('Post saved.');
				$this->redirect('/admin/posts');
			}
			else {
				$this->Session->setFlash('Error.');
			}
		}
	}
}

?>
