<?php


class AccountsController extends AppController {
	public $uses = array('Account', 'Game');

	function beforeFilter() {
		if(isset($this->params['admin']) && $this->params['admin']) {
			$this->__validateAdminLogin();
		} else {
			$this->__validateLoginStatus();
		}
	}

	function login() {
		if (empty($this->data) == false) {
			$account = $this->Account->validateLogin($this->data["Account"]);
			$account = $account["accounts"];

			if ((bool) $account) {
				$this->Session->write("Account", $account);
				$this->Session->setFlash("You've successfully logged in.");
			} else {
				$this->Session->setFlash("Invalid login.");
			}
		}

		$this->redirect('index');
		$this->exit();
	}

	function logout() {
		$this->Session->destroy('account');
		$this->Session->setFlash("You've successfully logged out.");
		$this->redirect('login');
	}

	function signup() {
		if (empty($this->data) == false) {
			//we have a post, validate and create account

			if (! ($this->data["Account"]["password"] && $this->data["Account"]["password"] == $this->data["Account"]["confirm"])) {
				$this->Session->setFlash("Please pick a password and confirm it.");
				unset($this->data["Account"]["password"]);
				unset($this->data["Account"]["confirm"]);
			} elseif ($this->Account->register($this->data["Account"])) {
				//valid submit, create entry, set login, and direct to league page
				$account = $this->Account->validateLogin($this->data["Account"]);

				$this->Session->write("Account", $account['accounts']);
				$this->Session->setFlash("You've successfully logged in.");
				$this->redirect('league');
			} else {
				//invalid submit, unset password and confirm and redisplay form
				unset($this->data["Account"]["password"]);
				unset($this->data["Account"]["confirm"]);
			}
		}

		//else just show the signup form
	}

	function index() {
		$account = $this->Session->read("Account");

		$this->set($account);
	}

	function view() {
		$account = $this->Session->read("Account");

		$this->set($account);
	}

	function save() {
		$data = $this->data;

		$this->Account->id = $this->Session->read('Account.id');

		if (empty($this->data) == false) {
			$account = $this->data["Account"];

			if ($account["email"] != $this->Session->read('Account.email')) {
				$update["email"] = $account["email"];
			} else {
				$update["email"] = $this->Session->read('Account.email');
			}

			if($account["password"] && $account["password"] == $account["confirm"]) {
				$update["password"] = md5($account["password"]);
 			}

			$data["Account"] = $update;
			if ($this->Account->save($data)) {
				$account["email"] = $update["email"];
				$account["id"] = $this->Session->read('Account.id');

				$this->Session->write("Account", $account);
				$this->Session->setFlash("Account settings updated.");
			} else {
				$this->Session->setFlash("An error occurred. Please try again.");
				$this->redirect('view');
				exit();
			}
		}

		$this->redirect('index');
	}

	//league overview: league.php
	function league() {
		$this->Account->id = $this->Session->read('Account.id');
		$data["players"] = array();

		$data["players"] = $this->Account->getLeague();
		$data['unranked'] = array();

		$data['games'] = array('min' => null, 'max' => null);

		$record = array();

		foreach($data['players'] as $player) {
			$record[] = array_sum($player['record']);
		}

		$data['games']['min'] = min($record);
		$data['games']['max'] = max($record);
		$data['games']['avg'] = array_sum($record) / count($record);

		foreach($data['players'] as $key => $player) {
			$played = array_sum($player['record']);

			if($played < $data['games']['avg'] / 10) {
				$data['unranked'][] = $player;
				unset($data['players'][$key]);
			}
		}

		$this->set($data);
	}

	function admin_index() {
		$data['count'] = $this->Account->find('count');
		$data['accounts'] = $this->Account->find('all');

		foreach($data['accounts'] as $key => $account) {
			$games = $this->Game->find('count', array(
				'conditions' => array(
					'account_id' => $account['Account']['id']
				)
			));
			$data['accounts'][$key]['games'] = $games;
		}

		$this->set($data);
	}

	function admin_league($id) {
		$this->Account->id = $id;

		$data['account'] = $this->Account->read();
		$data["players"] = array();

		$data["players"] = $this->Account->getLeague();
		$data['unranked'] = array();

		$data['games'] = array('min' => null, 'max' => null);

		$record = array();

		foreach($data['players'] as $player) {
			$record[] = array_sum($player['record']);
		}

		$data['games']['min'] = min($record);
		$data['games']['max'] = max($record);
		$data['games']['avg'] = array_sum($record) / count($record);

		foreach($data['players'] as $key => $player) {
			$played = array_sum($player['record']);

			if($played < $data['games']['avg'] / 10) {
				$data['unranked'][] = $player;
				unset($data['players'][$key]);
			}
		}

		$this->set($data);
	}
}
