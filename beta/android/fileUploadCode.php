<?php
$allowedExts = array("jpg", "jpeg", "gif", "png", "mp3", "mp4", "wma");
$extension = pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION);

if ((($_FILES["uploaded_file"]["type"] == "video/mp4")
|| ($_FILES["uploaded_file"]["type"] == "audio/mp3")
|| ($_FILES["uploaded_file"]["type"] == "audio/wma")
|| ($_FILES["uploaded_file"]["type"] == "image/pjpeg")
|| ($_FILES["uploaded_file"]["type"] == "image/gif")
|| ($_FILES["uploaded_file"]["type"] == "image/jpeg"))
&& in_array($extension, $allowedExts))
{
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
}
else
{
    echo "Invalid file";
}
?>