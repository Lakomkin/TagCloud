<?php 

echo '
            <div class="uploader">
                <h1>Загрузить картинку</h1>
                <form action="admin.php" method="post" enctype="multipart/form-data">

                    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
 
                    <div class="example-2">
                      <div class="form-group">
                        <input type="file" name="image" id="file" class="input-file">
                        <label for="file" class="btn btn-tertiary js-labelFile">
                          <i class="icon fa fa-check"></i>
                          <span class="js-fileName">Выбрать картинку</span>
                        </label>
                      </div>
                     </div>

                    <h2 >Тэги</h2>
                    <input type="text" name="TagString"><br>
                    <h2 >Описание</h2>
                    <textarea name="Desc" rows="8"></textarea><br>
                    <button type="submit">Загрузить</button>

                </form>
            </div>
'; 

?>