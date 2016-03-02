<?php
echo'<html>
        <head> 
        <title>Android Apk Panel</title>
        </head>
        <body>
            <form action="login.php" method="POST">
                <center>
                    <div style="height:20%"></div>
                    <font FACE=Arial color=green>
                        <strong>
                            Enter Password
                        </strong>
                    </font><br><br>
                    <table style="border-width:1px;" border="1" rules="all">
                        <tr>  
                            <td>
                                User Id
                            </td>
                            <td>
                                &nbsp;&nbsp
                            </td>
                            <td>
                                <input type="text" name="userId" id="userId" size="20"/>
                            </td>
                        </tr>
                         <tr>  
                            <td>
                                Password
                            </td>
                            <td>
                                &nbsp;&nbsp
                            </td>
                            <td>
                                <input type="password" name="password" id="password" size="20"/>
                            </td>
                        </tr>
                    </table>  
                    <br><input type="submit" value="Submit"/>   
                </center>
            </form> 
        </body>
    </html>
';
?>