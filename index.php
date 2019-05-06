<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Slydes!</title>

        <link href="css/background.css" rel="stylesheet">
        <link href="css/home.css" rel="stylesheet">
        <link href="css/fancy.css" rel="stylesheet">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <h1 class="title">Slydes!</h1>

        <div class="goto">
            <h1>Go to presentation...</h1>
            <input type="text" class="fancy" placeholder="PID..." id="goto_id">
            <button class="fancy" onclick="window.location = 'presentation/' + document.getElementById('goto_id').value;">Go!</button>
        </div>

        <div class="upload">
            <h1>...or upload your own!</h1>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <label class="file-upload fancy">
                    Your file (.tar)...
                    <input type="file" name="presentation_file">
                </label>
                <button type="submit" class="fancy">Upload!</button>
            </form>
        </div>

        <p class="authors">Brought to <i>you</i> by <a href="https://github.com/Pobulus">Paweł Chmielewski</a> and <a href="https://github.com/adampisula">Adam Pisula</a></p>
    </body>
    <script>

    </script>
</html>