<?php 
	$user = 'q925524g_tag'; // пользователь
	$password = '2091Golf'; // пароль
	$db = 'q925524g_tag'; // название бд
	$host = 'localhost'; // хост
	$charset = 'utf8'; // кодировка

	// Создаём подключение
    session_start();

	$connect = new PDO("mysql:host=$host;dbname=$db;cahrset=$charset", $user, $password);

 	$result = $connect -> query("SELECT * FROM Cards ORDER BY CardId DESC");


	$i = 0;

	echo '<div class="container" style="position: relative;z-index: 9997;">'; 
 	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $bin = '';
 	    if ($_SESSION['auth'] == true) {
            $bin = '<img src="img/bin.svg" alt="" onclick="DeleteCard('.$row["CardId"].');">';
        }
        echo '
                    <div class="Card">
                        <div class="stopper">
                            <img src="'.$row["CardImage"].'" alt="" onclick="OpenModal('.$row["CardId"].');">
                            '.$bin.'
                            <h3>Тэги</h3>
                            <p class="CardTags">';


        $TagList = explode(" ", $row["CardTags"]);

        for ($j = 0; $j < count($TagList); $j++) {
            $TagNames= $connect->query("SELECT TagName FROM Tags WHERE TagId= '".$TagList[ $j ]."' ");
            $NameTagRow = $TagNames->fetch();
            echo '<a href="" class=""># '.$NameTagRow['TagName'].' </a>';   
        }
                                
        echo '</p>
                            <h3>Описание</h3>
                            <p class="CardDescription">
                            '.$row["CardDescription"].'
                            </p>
                        </div>
                    </div>';

        $i++;

        if ($i % 4 == 0) {
            echo '
                    </div>

                    <div class="container" style="position: relative;z-index: '.(9997-$i/4).';">
                    ';
        }
    }
    echo '</div>';

 ?>
