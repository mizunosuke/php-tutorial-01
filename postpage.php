<?php


?>


<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>
        <body>
            <div class="form_container">
                <form method="POST" enctype="multipart/form-data" action="readpost.php">
                    <label for="">
                        画像をアップロード
                        <input type="file" name="imagefile">
                    </label>

                    <label for="">
                        魚種
                        <input type="text" name="kind">
                    </label>

                    <label for="">
                        サイズ
                        <input type="text" name="size">
                    </label>

                    <label for="">
                        場所
                        <input type="text" name="location">
                    </label>

                    <label for="">
                        ルアー
                        <input type="text" name="lure">
                    </label>

                    <label for="">
                        天気
                        <input type="text" name="weather">
                    </label>
                    
                    <label for="">
                        <input type="submit" value="投稿する">
                    </label>  
                </form>
            </div>
        </body>
    </html>