<?error_reporting(0);?>
<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
       <title>База данных по тендерам.</title>
</head>
<body>
<?php
mssql_connect('192.168.1.103','www_user','Axm6FpWEY4');

if(isset($sub1))
{
    $query = "
    INSERT INTO
    etriline.[TRILINE\dmitry].TENDER_USER
    (FIO,LOGIN,PASS,TIP)
    VALUES
    ('".$fio."','".$login."','".md5($pasw)."','".$tip."')
    ";
    mssql_query($query);
}
if(isset($sub2))
{
    $query = "
    UPDATE
    etriline.[TRILINE\dmitry].TENDER_USER
    SET
    FIO='".$fio."',
    LOGIN='".$login."',
    PASS='".md5($pasw)."',
    TIP='".$tip."'
    WHERE ID='".$fid."'
    ";
    mssql_query($query);
}
if($do==1)
{
    echo '<a href="'.$PHP_SELF.'" style="font-family:Arial; font-size:12px;">Вернуться к списку пользователей</a><br><br>
         <form action="'.$PHP_SELF.'" method="POST">
                <table cellpadding="5" cellspacing="0" border="1" style="font-family:Arial; font-size:12px;">
                       <tr><td><b>ФИО:</b></td><td><input type="text" name="fio" id="fio" size="50"></td></tr>
                       <tr><td><b>Логин:</b></td><td><input type="text" name="login" id="login" size="50"></td></tr>
                       <tr><td><b>Пароль:</b></td><td><input type="text" name="pasw" id="pasw" size="50"></td></tr>
                       <tr><td><b>Тип:</b></td><td><input type="radio" name="tip" id="tip" value="1">Администратор<br><input type="radio" name="tip" id="tip" value="0">Пользователь</td></tr>
                       <tr><td colspan="2" align="center"><input type="submit" name="sub1" id="sub1" value="Добавить пользователя"></td></tr>
                </table></form>';
}
elseif($do==2)
{
    mssql_quey("DELETE FROM etriline.[TRILINE\dmitry].TENDER_USER WHERE ID='".$fid."'");
    echo "<script>location.href='".$PHP_SELF."'</script>";
}
elseif($do==3)
{
    $query = "SELECT FIO,LOGIN,TIP FROM etriline.[TRILINE\dmitry].TENDER_USER WHERE ID='".$fid."'";
    $rs = mssql_query($query);
    if(mssql_result($rs,0,'TIP')==1)
    {
        $str1 = 'checked="CHECKED"';
        $str2 = '';
    }
    else
    {
        $str2 = 'checked="CHECKED"';
        $str1 = '';
    }
    echo '<a href="'.$PHP_SELF.'" style="font-family:Arial; font-size:12px;">Вернуться к списку пользователей</a><br><br>
         <form action="'.$PHP_SELF.'" method="POST">
                <table cellpadding="5" cellspacing="0" border="1" style="font-family:Arial; font-size:12px;">
                       <tr><td><b>ФИО:</b></td><td><input type="text" name="fio" id="fio" size="50" value="'.mssql_result($rs,0,'FIO').'"></td></tr>
                       <tr><td><b>Логин:</b></td><td><input type="text" name="login" id="login" size="50" value="'.mssql_result($rs,0,'LOGIN').'"></td></tr>
                       <tr><td><b>Пароль:</b></td><td><input type="text" name="pasw" id="pasw" size="50"></td></tr>
                       <tr><td><b>Тип:</b></td><td><input type="radio" name="tip" id="tip" value="1" '.$str1.'>Администратор<br><input type="radio" name="tip" id="tip" value="0" '.$str2.'>Пользователь</td></tr>
                       <tr><td colspan="2" align="center"><input type="submit" name="sub2" id="sub2" value="Обновить данные пользователя"><input type="hidden" name="fid" id="fid" value="'.$fid.'"></td></tr>
                </table></form>';
}
else
{
    $query = 'SELECT ID,FIO,LOGIN,TIP FROM etriline.[TRILINE\dmitry].TENDER_USER order by TIP,FIO';
    $rs = mssql_query($query);
    echo '<a href="'.$PHP_SELF.'?do=1" style="font-family:Arial; font-size:12px;">Добавить пользователя</a>';
    if(mssql_num_rows($rs))
    {
        echo '<table cellspacing="0" cellpadding="5" border="1" style="font-family:Arial; font-size:12px;">
                     <tr>
                         <td><b>Имя</b></td>
                         <td><b>Логин</b></td>
                         <td><b>Тип</b></td>
                         <td><b>Удалить</b></td>
                     </tr>';
        while($row = mssql_fetch_row($rs))
        {
            if($row[3]==1) $tip = 'Администратор';
            else $tip = 'Пользователь';
            echo '<tr>
                      <td><a href="'.$PHP_SELF.'?do=3&fid='.$row[0].'">'.$row[1].'</a></td>
                      <td>'.str_replace("###","<br>",$row[2]).'</td>
                      <td>'.$tip.'</td>
                      <td align="center"><a href="'.$PHP_SELF.'?do=2&fid='.$row[0].'">xxx</td>
            </tr>';
        }
        echo '</table>';
    }
}

?>
</body>
</html>
