<?php

	function get_user_by_email($email){

		$pdo = new PDO("mysql:host=localhost; dbname=marlin_lessons_part2_local; charset=utf8;", "root", "root");

		$sql = "SELECT * FROM register_form WHERE email=:email";

		$statement = $pdo->prepare($sql);
		$statement->execute(['email' => $email]);

		$registerArray = $statement->fetch(PDO::FETCH_ASSOC);

		return $registerArray;

	}

	function add_user($email, $password){

		$pdo = new PDO("mysql:host=localhost; dbname=marlin_lessons_part2_local; charset=utf8;", "root", "root");

		$sql = "INSERT INTO register_form (email, password, admin) VALUES (:email, :password, :admin)";

		$statement = $pdo->prepare($sql);
		$statement->execute(['email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT), 'admin' => 0]);

		$sql = "SELECT * FROM register_form WHERE email=:email";

		$statement = $pdo->prepare($sql);
		$statement->execute(['email' => $email]);

		$added_user = $statement->fetch(PDO::FETCH_ASSOC);

		return $added_user['id'];

		//$_SESSION['message'] = "<strong>Уведомление!</strong> Вы успешно зарегестрированы.";

	}

	function set_flash_message($name, $message){

		$_SESSION[$name] = $message;
	}

	function display_flash_message($name) {

		echo $_SESSION[$name];

		unset($_SESSION[$name]);

	}

	function redirect_to($path){

		header("Location: $path");

		exit;

	}

	function login($email, $password){

		$pdo = new PDO("mysql:host=localhost; dbname=marlin_lessons_part2_local; charset=utf8", "root", "root");

		$sql = "SELECT * FROM register_form WHERE email=:email";

		$statement = $pdo->prepare($sql);
		$statement->execute(['email' => $email]);

		$user = $statement->fetch(PDO::FETCH_ASSOC);

		if (empty($user)) {

			set_flash_message('key', 'Пользователь с таким email не существует');

			redirect_to('/page_login.php');

		} else {

			if (password_verify($password, $user['password'])) {

				$_SESSION['logged-in'] = $email;

				return true;

			}else return false;

		}
	}

	function is_not_logged_in($name){

		if (isset($_SESSION[$name])) {

			return false;

		}else return true;
	}

	function logout(){

		unset($_SESSION['logged-in']);

	}

	function is_admin($name){

		$pdo = new PDO("mysql:host=localhost; dbname=marlin_lessons_part2_local; charset=utf8", "root", "root");

		$sql = "SELECT * FROM register_form WHERE email=:email";

		$statement = $pdo->prepare($sql);
		$statement->execute(['email' => $name]);

		$admin = $statement->fetch(PDO::FETCH_ASSOC);

		return $admin['admin'];

	}

function get_all_users(){

	$pdo = new PDO("mysql:host=localhost; dbname=marlin_lessons_part2_local; charset=utf8;", "root", "root");

	$sql = "SELECT * FROM register_form";

	$statement = $pdo->prepare($sql);
	$statement->execute();

	$all_users = $statement->fetchAll(PDO::FETCH_ASSOC);

	return $all_users;

}

function edit_user_basic_inf($name, $job, $tel, $address, $user_id){

	$pdo = new PDO("mysql:host=localhost; dbname=marlin_lessons_part2_local; charset=utf8;", "root", "root");

	$sql = "UPDATE register_form SET name=:name, job=:job, tel=:tel, address=:address WHERE id=:user_id";

	$statement = $pdo->prepare($sql);
	$statement->execute(['name' => $name, 'job' => $job, 'tel' =>$tel, 'address' => $address, 'user_id' => $user_id]);

}

function set_user_status($status, $user_id){

	$pdo = new PDO("mysql:host=localhost; dbname=marlin_lessons_part2_local; charset=utf8;", "root", "root");

	$sql = "UPDATE register_form SET status=:status WHERE id=:user_id";

	$statement = $pdo->prepare($sql);
	$statement->execute(['status' => $status, 'user_id' => $user_id]);

}

function upload_user_avatar($img_src, $user_id){

	$pdo = new PDO("mysql:host=localhost; dbname=marlin_lessons_part2_local; charset=utf8;", "root", "root");

	$sql = "SELECT img_src FROM register_form WHERE id=:user_id";

	$statement = $pdo->prepare($sql);
	$statement->execute(['user_id' => $user_id]);

	$file = $statement->fetch(PDO::FETCH_ASSOC);

	if ($file['img_src'] != NULL) {

		unlink($file);

	}

	if ($_FILES && $_FILES[$img_src]['error'] == UPLOAD_ERR_OK) {

		$uploaddir = "img/demo/avatars/";
		$origin_name = $_FILES[$img_src]['name'];
		$ext = substr($origin_name, strpos($origin_name,'.'), strlen($origin_name)-1);
		$move_name = $uploaddir .uniqid('avatar-').$ext;
		move_uploaded_file($_FILES[$img_src]['tmp_name'], $move_name);

	    $sql = "UPDATE register_form SET img_src=:move_name WHERE id=:user_id";

	    $statement = $pdo->prepare($sql);
	    $statement->execute(['move_name' => $move_name, 'user_id' => $user_id]);

	}

}

function add_social_links($vk_link, $tg_link, $inst_link, $user_id){

	$pdo = new PDO("mysql:host=localhost; dbname=marlin_lessons_part2_local; charset=utf8;", "root", "root");

	$sql = "UPDATE register_form SET vk_link=:vk_link, tg_link=:tg_link, inst_link=:inst_link WHERE id=:user_id";

	$statement = $pdo->prepare($sql);
	$statement->execute(['vk_link' => $vk_link, 'tg_link' => $tg_link, 'inst_link' => $inst_link, 'user_id' => $user_id]);

}