<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
       <title>Отчеты по тендерам</title>
</head>
<body style="font-family:Arial; font-size:12px;">
<?php
mssql_connect('192.168.1.103','www_user','Axm6FpWEY4');
$results = Array("--НЕТ--","В работе","Не допустили","Проигран - Цена","Не успели","Выигран","Не прошли по цене","Закуп не дал цены вовремя","Не проходим по условиям","Не состоялся");   //Не менять порядок!!!
if(isset($sub))
{
    $datb = substr($dat1,0,4).substr($dat1,5,2).substr($dat1,8,2);
    $date = substr($dat2,0,4).substr($dat2,5,2).substr($dat2,8,2);
    //echo $datb.$date;
    if($man) $str = " AND tu.ID=".$man." ";
    else $str = "";
    switch($tip)
    {
    	case "0":
    	$str1 = ""; break;
    	case "1":
    	$str1 = " AND tt.result in (2,3,4,6,7,8)"; break;
    	case "2":
    	$str1 = " AND tt.result in (0,1)"; break;
    	case "3":
    	$str1 = " AND tt.result in (5)"; break;
    }
    $rs = mssql_query("
    SELECT tt.[ID]
    ,tt.[CUSTOMER]
    ,tt.[DATE_START]
    ,tt.[DATE_DOCS]
    ,tt.[DATE_ACT]
    ,tt.[THEME]
    ,tt.[SUM_START]
    ,tt.[SUM_TRL]
    ,tt.[SUM_WIN]
    ,tt.[RESULT]
    ,tt.[WINNER]
    ,tt.[TXT]
    ,tu.FIO
    ,tt.[SUM_ZERO]
    ,tt.[CHECK_NUM]
    FROM
    [etriline].[TRILINE\dmitry].[TENDER] tt
    left join [etriline].[TRILINE\dmitry].[TENDER_USER] tu on tt.MANAGER=tu.ID
    where tu.FIO is not null
    and tt.[DATE_ACT]>='".$datb."'
    and tt.[DATE_ACT]<='".$date."'
    ".$str.$str1."
    order by tu.FIO,tt.[DATE_ACT] desc");

    echo '<table cellpadding="3" cellspacing="0" border="1" style="font-family:Arial; font-size:12px">
                 <tr>
                     <td>Клиент</td>
                     <td>Объявление</td>
                     <td>Документы</td>
                     <td>Проведение</td>
                     <td>Суть</td>
                     <td>Начальная</td>
                     <td>Наша</td>
                     <td>Учетная</td>
                     <td>Финальная</td>
                     <td>Номер счета</td>
                     <td>Итог</td>
                     <td>Победитель</td>
                     <td>Примечание</td>
                     <td>Менеджер</td>
                 </tr>';
    while($row = mssql_fetch_row($rs))
    {
        $d1 = substr($row[2],6,2).".".substr($row[2],4,2).".".substr($row[2],0,4);
        $d2 = substr($row[3],6,2).".".substr($row[3],4,2).".".substr($row[3],0,4);
        $d3 = substr($row[4],6,2).".".substr($row[4],4,2).".".substr($row[4],0,4);
        echo '<tr>
                  <td>'.$row[1].'</td>
                  <td>'.$d1.'</td>
                  <td>'.$d2.'</td>
                  <td>'.$d3.'</td>
                  <td>'.$row[5].'</td>
                  <td>'.$row[6].'</td>
                  <td>'.$row[7].'</td>
                  <td>'.$row[13].'</td>
                  <td>'.$row[8].'</td>
                  <td>'.$row[14].'</td>
                  <td>'.$results[$row[9]].'</td>
                  <td>'.$row[10].'</td>
                  <td>'.$row[11].'</td>
                  <td>'.$row[12].'</td>
        </tr>';
    }
    echo '</table><br><a href="javascript:history.back()">Назад</a>';
}
else
{
    echo '<form action="'.$PHP_SELF.'" method="GET">
    Начало периода:
    <input type="text" name="dat1" id="dat1" value="'.date("Y.m.d").'"><br>
    Конец периода:
    <input type="text" name="dat2" id="dat2" value="'.date("Y.m.d").'"><br>
    Менеджер:
    <select name="man" id="man">
    <option value="0">--ВСЕ--</option>';

    $rs = mssql_query("SELECT ID,FIO FROM [etriline].[TRILINE\dmitry].[TENDER_USER] ORDER BY FIO");
    while($row = mssql_fetch_row($rs))
    {
        echo '<option value="'.$row[0].'">'.$row[1].'</option>';
    }

    echo '</select><br>
    <input type="radio" name="tip" value="0" checked="CHECKED">Все тендера<br>
    <input type="radio" name="tip" value="1">Завершенные<br>
    <input type="radio" name="tip" value="2">В работе<br>
    <input type="radio" name="tip" value="3">Выиграные<br>
    <input type="submit" name="sub" id="sub" value="Получить">';
}
?>
</form>
</body>
</html>
