<?php 

 session_start();

$user = 'q925524g_tag'; // пользователь
$password = '2091Golf'; // пароль
$db = 'q925524g_tag'; // название бд
$host = 'localhost'; // хост
$charset = 'utf8'; // кодировка

// Создаём подключение

$connect = new PDO("mysql:host=$host;dbname=$db;cahrset=$charset", $user, $password);
$base = new mysqli($host, $user, $password, $db);
// Создаём запрос

$Tags = $connect -> query('SELECT * FROM Tags ORDER BY TagId DESC');


if ((($_POST['login'] == 'simon')&&($_POST['password'] == '123')) || ($_SESSION['auth'] == true)){
   
    $_SESSION['auth'] = true;
}

if ($_SESSION['auth'] == false) {
    exit("Incorrect password ot login");

}

if ((isset($_FILES['image'])) && ($_SESSION['UploadedCard'] != $_FILES['image']['name'])) {
    $name = md5_file($_FILES['image']['tmp_name']);
    $extension = mb_substr($_FILES['image']['name'],stripos($_FILES['image']['name'], '.'),null,'UTF-8');

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/img/' . $name . $extension)) {
        die('/q925524g.beget.tech/public_html/img/' . $name . $extension);
    } else {
        $_SESSION['UploadedCard'] = $_FILES['image']['name'];

        $TagString = str_replace(" ", '', $_POST["TagString"])."#";
        $QuertTag ='';
        $TagName = '';
        for ($i = 1; $i < strlen($TagString)  ; $i++) { 

            if ($TagString[ $i ] <> '#') {
                $TagName = $TagName . $TagString[ $i ];
            } else {
                $SearchTagsLike = $connect -> query('SELECT * FROM Tags WHERE TagName = "'.$TagName.'"');

                if ($SearchTagsLike->rowCount() == 0) {

                    $connect -> query('INSERT INTO Tags (TagName) VALUES ("'.$TagName.'")');

                } 
                $UploadedTagId = $connect -> query('SELECT TagId FROM Tags WHERE TagName = "'.$TagName.'"');
                $row = $UploadedTagId->fetch(PDO::FETCH_ASSOC);

                if ($i != (strlen($TagString) - 1)) {
                    $QuertTag = $QuertTag . $row['TagId'] . ' ';
                } else {
                    $QuertTag = $QuertTag . $row['TagId'];
                }
                
                $TagName = '';
            }
            

        }

        $connect -> query('INSERT INTO Cards (CardImage, CardTags, CardDescription) VALUES ("img/'.$name.$extension.'", "'.$QuertTag.'", "'.$_POST["Desc"].'")');
    }

    
} 


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>TagCloud</title>
	<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.3.1/cerulean/bootstrap.min.css">-->

	<script src="js/jquery-3.6.0.min.js"></script>



	<link href="css/main.css" rel="stylesheet">
</head>
<body>
    <header>
    	<div class="head-hold">
    		<div class="HeadContainer">

    		  
                <h1>TagCloud</h1>
	    		<input type="text" placeholder="Поиск по описанию" style="color:white;" id="tag_searcher">
	    		<div class="settings">
	    			<img src="img/grid.svg" alt="" onclick="TurnToGrid();">
	    			<hr noshade width="3" size="40">
	    			<img src="img/roll.svg" alt="" onclick="TurnToRoll();">
	    		</div>
    		</div>
    	</div>
    </header>

    <!--_________Modal_________-->
	<div id="myModal" class="modal" style="z-index: 9999;">

	  <!-- Модальное содержание -->
	  <div class="modal-content">
	    <span class="close">&times;</span>
	    <div class="ModalHolder">
	    	<img src="img/LeftArrow.svg" alt="" class="arrow" id="LeftArrow">
	    	<img src="img/RightArrow.svg" alt="" class="arrow" id="RightArrow">
	    	<img src="img/CardBg.png" alt="">
	    	<h3>Тэги</h3>
	    	<p>

            # Хэштэг # Хэштэг # Хэштэг # Хэштэг # Хэштэг # Хэштэг
            </p>
	    	<h3>Описание</h3>
	    	<p style="padding-bottom: 60px;">Каждый из нас понимает очевидную вещь: выбранный нами инновационный путь позволяет выполнить важные задания по разработке ...</p>
	    </div>
	  </div>

	</div>
    <!--_________Modal_________-->

    <section id="tags">
      	<h1>Актуальные тэги</h1>
      	<div class="TagsHolder">
            <?php 
                echo ' <a  style="cursor: pointer;background-color: #0E51A7; border:1px solid #0E51A7;" onclick="ClearTagSearchList();">Очистить</a>';
                while ($row = $Tags->fetch(PDO::FETCH_ASSOC)) {

                    echo '<a style="cursor: pointer;" id="tag'.$row["TagId"].'" onclick="SelectTag('.$row["TagId"].');">#'.$row["TagName"].'</a>';

                }
             ?>

      	</div>
    </section>

    <div><h2 class="UploadBtn" onclick="OpenPoster()">Загрузить картику</h2></div>

    <section id="cards" class="roll">
        <?php 
            $n=0;
            $i=0;
            $count = $connect -> query('SELECT * FROM Cards');

            while ($row = $count->fetch(PDO::FETCH_ASSOC)) {
                $n++;
            }
            $Cards = $connect -> query('SELECT * FROM Cards ORDER BY CardId DESC');
            
            echo '<div class="container" style="position: relative;z-index: '.($n+1).';">'; 
            
            while ($row = $Cards->fetch(PDO::FETCH_ASSOC)) {

                echo '
                    <div class="Card">
                        <div class="stopper">
                            <img src="'.$row["CardImage"].'" alt="" onclick="OpenModal('.$row["CardId"].');">
                            <img src="img/bin.svg" alt="" onclick="DeleteCard('.$row["CardId"].');">
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

                    <div class="container" style="position: relative;z-index: '.($n+1-$i/4).';">
                    ';
                }

             

            }
            echo '</div>';

            

         ?>

    </section>

    <footer style="padding-top: 200px;background-color: #438EFF;margin-top: 100px;">
    </footer>


	<script>

        jQuery(document).ready(function() {

            $("#tag_searcher").keyup(function(){
                var search = $("#tag_searcher").val();
                $.ajax({
                    url : "/search_handler.php",
                    type: "POST",
                    dataType: "html",
                    data:({query : search})
                })
                .done(function(html){
                    $("#cards").empty().append(html);
                        
                });
            });
        });

		var modal = document.getElementById("myModal");
		var span = document.getElementsByClassName("close")[0];
        var i = 0;
        let SelectedTags = new Array();

        function ClearTagSearchList(){
            $.ajax({
                url : "/ClearTagSearchList.php",
                type: "POST",
                dataType: "html"
            })
            .done(function(html){
                $("#cards").empty().append(html);
                        
            });
            $(".TagsHolder a").removeClass("selected");
        }
        function SelectTag(id){

            if ( $("a#tag"+id).hasClass("selected") ) {

                SelectedTags.splice( SelectedTags.indexOf( id ) , 1);
                i = i - 1;

                if (SelectedTags.length > 0)  {
                    TagsString = SelectedTags.join(' '); 

                    $.ajax({
                        url : "/SearchByTagHandler.php",
                        type: "POST",
                        dataType: "html",
                        data:({query : TagsString})
                    })
                    .done(function(html){
                        $("#cards").empty().append(html);
                                
                    });
                } else {ClearTagSearchList();}


                $("a#tag"+id).removeClass("selected");

            } else {
                SelectedTags[i] = id; 
                i = i + 1;
                TagsString = SelectedTags.join(' '); 

                $.ajax({
                    url : "/SearchByTagHandler.php",
                    type: "POST",
                    dataType: "html",
                    data:({query : TagsString})
                })
                .done(function(html){
                    $("#cards").empty().append(html);
                            
                });
                $("a#tag"+id).addClass("selected");
            }
        }

		function OpenModal(id) {
            $.ajax({
                url : "/ModalHandler.php",
                type: "POST",
                dataType: "html",
                data:({query : id})
            })
            .done(function(html){
                $(".ModalHolder").empty().append(html);   
        
            });
		    modal.style.display = "block";

		}
        function OpenPoster() {
            $.ajax({
                url : "/PosterHandler.php",
                type: "POST",
                dataType: "html"
            })
            .done(function(html){
                $(".ModalHolder").empty().append(html);   
                (function() {
                   
                  'use strict';
                 
                  $('.input-file').each(function() {
                    var $input = $(this),
                        $label = $input.next('.js-labelFile'),
                        labelVal = $label.html();
                     
                   $input.on('change', function(element) {
                      var fileName = '';
                      if (element.target.value) fileName = element.target.value.split('\\').pop();
                      fileName ? $label.addClass('has-file').find('.js-fileName').html(fileName) : $label.removeClass('has-file').html(labelVal);
                   });
                  });
                 
                })();
            });
            modal.style.display = "block";

        }
		span.onclick = function() {
		  modal.style.display = "none";
		}

		window.onclick = function(event) {
		  if (event.target == modal) {
		    modal.style.display = "none";
		  }
		}

        function DeleteCard(id){
            $.ajax({
                url : "/Remover.php",
                type: "POST",
                dataType: "html",
                data:({query : id})
            })
            .done(function(html){
                $('img[onclick="OpenModal('+id+');"').addClass("deleted"); 
        
            });
        }
        function PickNext(id) {
        }

		function TurnToRoll() {
			$("section#cards").removeClass("grid").addClass("roll");

		}
		function TurnToGrid() {
			$("section#cards").removeClass("roll").addClass("grid");
		}
		$(function(){
		  if ( $(window).width() < 540 ) {
		  	$("section#cards").removeClass("grid").addClass("roll");
		  }else $("section#cards").removeClass("roll").addClass("grid");
		});


	</script>
</body>
</html>

