<?php
session_start();
require "function.php";

$name = $_POST['name'];
$job = $_POST['job'];
$tel = $_POST['tel'];
$address = $_POST['address'];

$email = $_POST['email'];
$password = $_POST['password'];
$status = $_POST['status'];

$vk_link = $_POST['vk_link'];
$tg_link = $_POST['tg_link'];
$inst_link = $_POST['inst_link'];

//var_dump($_POST);
if ($email != "" && $password != "") {

	//get_user_by_email($email);
	if (!empty(get_user_by_email($email))) {

		set_flash_message('warning', 'Такой email уже существует, введите другой Email!');

		redirect_to('create_user.php');

	}else{

		$user_id = add_user($email, $password);

		edit_user_basic_inf($name, $job, $tel, $address, $user_id);

		set_user_status($status, $user_id);

		upload_user_avatar('img_src', $user_id);

		add_social_links($vk_link, $tg_link, $inst_link, $user_id);

		set_flash_message('success', 'Пользователь успешно добавлен!');

		redirect_to('users.php');

	}

}else{

	set_flash_message('warning', 'Поля Email и Пароль обязательны для заполнения!');
	redirect_to('create_user.php');

}


