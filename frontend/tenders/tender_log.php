<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
       <title>Действия по тендерам</title>
</head>
<body style="font-family:Arial; font-size:12px;">
<?php
mssql_connect('192.168.1.103','www_user','Axm6FpWEY4');
if(isset($sub))
{
    switch($tip)
    {
    	case "0":
    	$str1 = " order by tl.[DAT] desc,tu.FIO"; break;
    	case "1":
    	$str1 = " order by tu.FIO,tl.[DAT] desc"; break;
    	default:
    	$str1 = " order by tu.FIO,tl.[DAT] desc"; break;
    }
    if($man) $str = " and tu.ID=".$man." "; else $str = "";
    $rs = mssql_query("
    SELECT tl.[TXT]
    ,tl.[TENDER]
    ,tu.FIO
    ,tl.DAT
    FROM
    [etriline].[TRILINE\dmitry].[TENDER_LOG] tl
    left join [etriline].[TRILINE\dmitry].[TENDER_USER] tu on tl.MANAG=tu.ID
    where tl.MANAG<>0
    ".$str.$str1);
    echo '<table cellpadding="3" cellspacing="0" border="1" style="font-family:Arial; font-size:12px">
                 <tr>
                     <td>Менеджер</td>
                     <td>Дата</td>
                     <td>Действие</td>
                 </tr>';
    while($row = mssql_fetch_row($rs))
    {
		if($row[2])
		{
			echo '<tr>
                  <td>'.$row[2].'</td>
                  <td>'.$row[3].'</td>
                  <td>'.$row[0].' '.$row[1].'</td>
        	</tr>';
		}
    }
    echo '</table><br><a href="javascript:history.back()">Назад</a>';
}
else
{
    echo '<form action="'.$PHP_SELF.'" method="GET">
    Менеджер:
    <select name="man" id="man">
    <option value="0">--ВСЕ--</option>';
    $rs = mssql_query("SELECT ID,FIO FROM [etriline].[TRILINE\dmitry].[TENDER_USER] ORDER BY FIO");
    while($row = mssql_fetch_row($rs))
    {
        echo '<option value="'.$row[0].'">'.$row[1].'</option>';
    }
    echo '</select><br>
    <input type="radio" name="tip" value="0" checked="CHECKED">Сортировка по дням<br>
    <input type="radio" name="tip" value="1">Сортировка по менеджерам<br>
    <input type="submit" name="sub" id="sub" value="Получить"></form>';
}
?>
</body>
</html>
