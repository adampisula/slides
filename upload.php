<?php

    $directory = "presentation/";
    $extensions = array("tar", "zip");

    if(isset($_FILES["presentation_file"])) {
        $errors = array();

        if(!is_writable($directory))
            $errors[] = "Main directory is unwritable. That's bad, but don't worry, it's on us.";

        $file_name = $_FILES["presentation_file"]["name"];
        $file_size = $_FILES["presentation_file"]["size"];
        $file_tmp = $_FILES["presentation_file"]["tmp_name"];
        $file_type = $_FILES["presentation_file"]["type"];
        $file_ext = strtolower(end(explode(".", $_FILES["presentation_file"]["name"])));

        if(in_array($file_ext, $extensions)=== false)
            $errors[] = "Now, that's some weird extension. We only support these beauties: ".implode(", ", $extensions);

        if($file_size > 24 * 1024 * 1024) // 24M
            $errors[] = "Your file's too big, sorry m8.";

        $id = substr(uniqid(), -5, 5);

        $oldmask = umask(0);
        mkdir($directory.$id, 0777, true);
        umask($oldmask);

        if(!is_writable($directory.$id))
            $errors[] = "Your presentation directory is not writable. What a pity!";

        if(empty($errors) == true) {
            move_uploaded_file($file_tmp, $directory.$id."/".$id.".".$file_ext);

            if($file_ext == "tar") {
                try {
                    $phar = new PharData($directory.$id."/".$id.".".$file_ext);
                    $phar->extractTo($directory.$id);
                } catch (Exception $e) {                
                    $errors[] = "Whoops, we couldn't unpack your presentation. Here's your error: 
".$e->getMessage()."<br>";
                }
            }

            else if($file_ext == "zip") {
                $zip = new ZipArchive;

                if($zip->open($directory.$id."/".$id.".".$file_ext) === TRUE) {
                    $zip->extractTo($directory.$id);
                    $zip->close();
                }

                else
                    $errors[] = "Whoops, we couldn't unpack your presentation.";
            }

            if(!copy("src/index_php.template", $directory.$id."/index.php"))
                $errors[] = "Oh man, it looks like we couldn't copy some template files :( That's lame<br>";

            unlink($directory.$id."/".$id.".".$file_ext);

            $files = array_diff(scandir($directory.$id."/slides"), array('..', '.'));

            if(count($files) > 1)
                $format = end(explode(".", $files[2]));

            else
                $errors[] = "It looks like your presentation's broken! ¯\_(ツ)_/¯";

            echo $id;
            header("Location: ".$directory.$id);

            if(!empty($errors)) {
                rmdir($directory.$id);
                echo "Look, we're really sorry, but you got these errors:<ul><li>".implode("</li><li>", $errors)."</li></ul>";    
            }
        }
        
        else {
            echo "Look, we're really sorry, but you got these errors:<ul><li>".implode("</li><li>", $errors)."</li></ul>";
        }
    }
?>
