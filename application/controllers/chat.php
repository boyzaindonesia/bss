<?php
include_once(APPPATH."libraries/AdminController.php");
class chat extends AdminController {

	function __construct()
	{
		parent::__construct();
	}


	function me()
	{
		if(isset($_GET['action'])){

			if ($_GET['action'] == "chatheartbeat") { $this->chatHeartbeat(); }
			if ($_GET['action'] == "sendchat") { $this->sendChat(); }
			if ($_GET['action'] == "closechat") { $this->closeChat(); }
			if ($_GET['action'] == "startchatsession") { $this->startChatSession(); }

			if (!isset($this->jCfg['chat'])) {
				$this->jCfg['chat']= array();
				$this->_releaseSession();
			}

			if (!isset($this->jCfg['chat']['chatHistory'])) {
				$this->jCfg['chat']['chatHistory'] = array();
				$this->_releaseSession();
			}

			if (!isset($this->jCfg['chat']['openChatBoxes'])) {
				$this->jCfg['chat']['openChatBoxes'] = array();
				$this->_releaseSession();
			}

		} else {
			redirect(base_url()."admin/me");
		}
	}

	function startChatSession() {
		$items = '';
		if (isset($this->jCfg['chat']['openChatBoxes']) &&
			!empty($this->jCfg['chat']['openChatBoxes'])) {
			foreach ($this->jCfg['chat']['openChatBoxes'] as $chatbox => $void) {
				$items .= $this->chatBoxSession($chatbox);
			}
		}


		if ($items != '') {
			$items = substr($items, 0, -1);
		}

		header('Content-type: application/json');
		?>
		{
				"username": "<?php echo $this->jCfg['user']['name'];?>",
				"items": [
					<?php echo $items;?>
				]
		}

		<?php


		exit(0);
	}


	function sanitize($text) {
		$text = htmlspecialchars($text, ENT_QUOTES);
		$text = str_replace("\n\r","\n",$text);
		$text = str_replace("\r\n","\n",$text);
		$text = str_replace("\n","<br>",$text);
		return $text;
	}

	function chatHeartbeat(){

		$sql = "select
					*
				from mt_app_chat
				where (mt_app_chat.chat_to = '".$this->jCfg['user']['name']."' AND chat_recd = 0)
				order by chat_id ASC";

		$query = $this->db->query($sql)->result_array();
		$items = '';
		$chatBoxes = array();

		foreach($query as $r) {
			if (!isset($this->jCfg['chat']['openChatBoxes'][$r['chat_from']]) &&
				isset($this->jCfg['chat']['chatHistory'][$r['chat_from']])) {
				$items = $this->jCfg['chat']['chatHistory'][$r['chat_from']];
			}

			$photo = get_image(base_url()."assets/collections/photo/thumb/".get_user_photo_chat($r['chat_from']));
			$timestamp = $r['chat_sent'];

			$r['chat_message'] = $this->sanitize($r['chat_message']);
			$items .= <<<EOD
					   {
			"s": "0",
			"f": "{$r['chat_from']}",
			"m": "{$r['chat_message']}",
			"i": "{$photo}",
			"t": "{$timestamp}"
	   },
EOD;

			if (!isset($this->jCfg['chat']['chatHistory'][$r['chat_from']])) {
				$this->jCfg['chat']['chatHistory'][$r['chat_from']] = '';
				$this->_releaseSession();
			}

			$this->jCfg['chat']['chatHistory'][$r['chat_from']] .= <<<EOD
						   {
			"s": "0",
			"f": "{$r['chat_from']}",
			"m": "{$r['chat_message']}",
			"i": "{$photo}",
			"t": "{$timestamp}"
	   },
EOD;

			if(isset( $this->jCfg['chat']['tsChatBoxes'][$r['chat_from']] )){
				unset($this->jCfg['chat']['tsChatBoxes'][$r['chat_from']]);
			}
			$this->jCfg['chat']['openChatBoxes'][$r['chat_from']] = $r['chat_sent'];
			$this->_releaseSession();
		}

		if (!empty($this->jCfg['chat']['openChatBoxes'])) {
			foreach ($this->jCfg['chat']['openChatBoxes'] as $chatbox => $time) {
				if (!isset($this->jCfg['chat']['tsChatBoxes'][$chatbox])) {
					$now = time()-strtotime($time);
					$time = date('d M, H:i', strtotime($time));

					$message = "Sent at $time";
					if ($now > 180) {
						$items .= <<<EOD
						{
						"s": "2",
						"f": "$chatbox",
						"m": "{$message}"
						},
EOD;

						if (!isset($this->jCfg['chat']['chatHistory'][$chatbox])) {
							$this->jCfg['chat']['chatHistory'][$chatbox] = '';
							$this->_releaseSession();
						}

						$this->jCfg['chat']['chatHistory'][$chatbox] .= <<<EOD
						{
						"s": "2",
						"f": "$chatbox",
						"m": "{$message}"
						},
EOD;
						$this->jCfg['chat']['tsChatBoxes'][$chatbox] = 1;
						$this->_releaseSession();
					}
				}
			}
		}

		$sql = "update mt_app_chat set chat_recd = 1
				where mt_app_chat.chat_to = '".$this->jCfg['user']['name']."'
				and chat_recd = 0";
			$query = $this->db->query($sql);

			if ($items != '') {
				$items = substr($items, 0, -1);
			}

		header('Content-type: application/json');
		?>
		{
				"items": [
					<?php echo $items;?>
				]
		}
		<?php
		exit(0);
	}

	function sendChat(){
		$from      = $this->jCfg['user']['name'];
		$to        = $_POST['to'];
		$message   = $_POST['message'];
		$photo     = get_image(base_url()."assets/collections/photo/thumb/".get_user_photo_chat($from));
		$timestamp = timestamp();

		$this->jCfg['chat']['openChatBoxes'][$_POST['to']] = date('Y-m-d H:i:s', time());
		$this->_releaseSession();

		if (!isset($this->jCfg['chat']['chatHistory'][$_POST['to']])) {
			$this->jCfg['chat']['chatHistory'][$_POST['to']] = '';
			$this->_releaseSession();
		}

		$messagesan = $this->sanitize($message);
		$this->jCfg['chat']['chatHistory'][$_POST['to']] .= <<<EOD
						   {
				"s": "1",
				"f": "{$to}",
				"m": "{$messagesan}",
				"i": "{$photo}",
				"t": "{$timestamp}"
		   },
EOD;

		unset($this->jCfg['chat']['tsChatBoxes'][$_POST['to']]);
		$this->_releaseSession();

		$sql = "insert into mt_app_chat (chat_from,chat_to,chat_message,chat_sent) values ('".$from."', '".$to."','".$message."','".$timestamp."')";
		$query = $this->db->query($sql);

		header('Content-type: application/json');
		?>
		{
				"photo": "<?php echo $photo ?>",
				"timestamp": "<?php echo $timestamp ?>"
		}
		<?php

		// echo "1";
		exit(0);
	}

	function closeChat(){
		unset($this->jCfg['chat']['openChatBoxes'][$_POST['chatbox']]);
		$this->_releaseSession();
		echo "1";
		exit(0);
	}

	function chatBoxSession($chatbox) {

		$items = '';

		if (isset($this->jCfg['chat']['chatHistory'][$chatbox])) {
			$items = $this->jCfg['chat']['chatHistory'][$chatbox];
			$this->_releaseSession();
		}

		return $items;
	}
}