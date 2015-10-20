<?php
$allowedExts = array("jpg", "jpeg", "gif", "png", "mp3", "mp4", "wma");
$extension = pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION);


if ($_FILES["uploaded_file"]["error"] > 0)
{
    echo "Return Code: " . $_FILES["uploaded_file"]["error"] . "<br/>";
}
else
{		
    if(move_uploaded_file($_FILES["uploaded_file"]["tmp_name"],
    "tmpFolder/" . $_FILES["uploaded_file"]["name"]))
    {
        echo "success";
    }
    else
    {
        echo "fail";
    }		
}

?>