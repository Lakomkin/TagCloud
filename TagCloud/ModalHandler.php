<?php 

	$user = 'q925524g_tag'; // пользователь
	$password = '2091Golf'; // пароль
	$db = 'q925524g_tag'; // название бд
	$host = 'localhost'; // хост
	$charset = 'utf8'; // кодировка

	// Создаём подключение

	$connect = new PDO("mysql:host=$host;dbname=$db;cahrset=$charset", $user, $password);

 	$result = $connect -> query("SELECT * FROM Cards WHERE CardId = '".$_POST['query']."' ");

	$row = $result->fetch();

	echo '
	    	<img src="img/LeftArrow.svg" alt="" class="arrow" id="LeftArrow">
	    	<img src="img/RightArrow.svg" alt="" class="arrow" id="RightArrow">
	    	<img src="'.$row["CardImage"].'" alt="">
	    	<h3>Тэги</h3>
	    	<p>';


    $TagList = explode(" ", $row["CardTags"]);

    for ($i = 0; $i < count($TagList); $i++) {

        $TagNames= $connect->query("SELECT TagName FROM Tags WHERE TagId= '".$TagList[ $i ]."' ");
        $NameTagRow = $TagNames->fetch();
        echo '# '.$NameTagRow['TagName'].' ';     
    }


    echo '	</p>
	    	<h3>Описание</h3>
	    	<p style="padding-bottom: 60px;">'.$row["CardDescription"].'</p>';


 ?>