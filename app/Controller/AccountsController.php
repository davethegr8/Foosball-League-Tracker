<?php
class AccountsController extends AppController {

	function beforeFilter() {
		$this->__validateLoginStatus();
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
		$this->view();
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

		$this->set($data);
	}

	function admin_index() {
		$data['count'] = $this->Account->find('count');
		$data['accounts'] = $this->Account->find('all');

		$sql = "SELECT CAST(created as date) as `date`, COUNT(*) AS num FROM accounts GROUP BY `date`";
		$daily = $this->Account->query($sql);

		$endpoints = array('min' => '', 'max' => '');
		foreach ($daily as $day) {
			$day = $day[0];

			if ($day["date"] < $endpoints['min'] || $endpoints['min'] == '') {
				$endpoints['min'] = $day["date"];
			}

			if ($day["date"] > $endpoints['max'] || $endpoints['max'] == '') {
				$endpoints['max'] = $day["date"];
			}

			$temp[$day["date"]] = $day["num"];
		}

		$daily = $temp;

		$date = $endpoints['min'];
		$end = $endpoints['max'];

		while ($date <= $end) {
			if (isset($daily[$date])) {
				$range[(strtotime($date))] = $daily[$date];
			} else {
				$range[(strtotime($date))] = 0;
			}

			$date = date("Y-m-d", strtotime($date." +1 day"));
		}

		$data["range"] = $range;

		$this->set($data);
	}
}
