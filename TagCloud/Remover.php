<?php 

	$user = 'q925524g_tag'; // пользователь
	$password = '2091Golf'; // пароль
	$db = 'q925524g_tag'; // название бд
	$host = 'localhost'; // хост
	$charset = 'utf8'; // кодировка

	// Создаём подключение

	$connect = new PDO("mysql:host=$host;dbname=$db;cahrset=$charset", $user, $password);

 	$result = $connect -> query("DELETE FROM Cards WHERE CardId = '".$_POST['query']."' ");



 ?>