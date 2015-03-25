<?
error_reporting(1);
session_start();
?>
<html>
<head>
       <title>База данных тендеровsdfgsdfgs</title>
</head>
<body style="font-family:Arial; font-size:12px;">
<?php
mssql_connect('192.168.1.103','www_user','Axm6FpWEY4');
session_start();
$types = Array("Запрос котировок","Открытый конкурс","Тендер","Закрытый конкурс","Электронные торги");          //Не менять порядок!!!
$results = Array("--НЕТ--","В работе","Не допустили","Проигран - Цена","Не успели","Выигран","Не прошли по цене","Закуп не дал цены вовремя","Не проходим по условиям","Отправлено","Не состоялся");   //Не менять порядок!!!
function money($a)
{
    $x = 0.0;
    $b = strrev($a);
    for($i=1;$i<strlen($b)+1;$i++)
    {
        $x = $i/3;
        if(round($x)*3!=round($x*3))
        {
            $str.=$b[$i-1];
        }
        else
        {
            $str.=$b[$i-1]." ";
        }
    }
    return strrev($str);
}
if(!isset($_SESSION["OTDEL"]) || $_SESSION["OTDEL"]!=1)
{
    if(isset($err)) echo '<center style="font-family:Arial; font-size:12px; color:#FF0000; font-weight:bold;">У вас нет прав доступа к этой странице</center>';
    if(!isset($sub))
    echo '<center><form action='.$PHP_SELF.' method="POST"><table><tr><td>Логин</td><td><input type="text" name="login" size="30"></td></tr><tr><td>Пароль</td><td><input type="password" name="pasw" size="30"></td></tr><tr><td align="center" colspan="2"><input type="submit" value="Вход" name="sub"></td></tr></form>';
    else
    {
        $rs = mssql_query("SELECT TIP FROM etriline.[TRILINE\dmitry].[TENDER_USER] WHERE LOGIN='".$login."' AND PASS='".md5($pasw)."'");
        $rec = mssql_fetch_array($rs);
        $_SESSION["OTDEL"] = $rec[0];
        if($_SESSION["OTDEL"]==1) echo '<script>location.href="'.$PHP_SELF.'"</script>';
        //else echo '<script>location.href="'.$PHP_SELF.'?err=1"</script>';

    }
}
elseif($_SESSION["OTDEL"]==1)
{
    if(isset($sub1))
    {
        if($d2msk) $date2msk = 1; else $date2msk = 0;
        if($d3msk) $date3msk = 1; else $date3msk = 0;
        $date1 = substr($d1,6,4).substr($d1,3,2).substr($d1,0,2);
        $date2 = substr($d2,6,4).substr($d2,3,2).substr($d2,0,2).substr($d2,11,2).substr($d2,14,2);
        $date3 = substr($d3,6,4).substr($d3,3,2).substr($d3,0,2).substr($d3,11,2).substr($d3,14,2);
        mssql_query("
        INSERT INTO [etriline].[TRILINE\dmitry].[TENDER]
        (CUSTOMER,CITY,ADDRESS,URL,DATE_START,DATE_DOCS,DATE_DOCS_MSK,DATE_ACT,DATE_ACT_MSK,THEME,SUM_START,TIP,RESULT,SUM_TRL,SUM_WIN,MANAGER,WINNER,TXT,SUM_ZERO,CHECK_NUM,DATE_ADD)
        VALUES
        ('".str_replace('\"','',str_replace("\'",'',$comp))."',
        '".str_replace('\"','',str_replace("\'",'',$city))."',
        '".str_replace('\"','',str_replace("\'",'',$addr))."',
        '".str_replace('\"','',str_replace("\'",'',$url))."',
        '".$date1."',
        '".$date2."',
        '".$date2msk."',
        '".$date3."',
        '".$date3msk."',
        '".str_replace('\"','',str_replace("\'",'',$theme))."',
        '".str_replace(" ","",str_replace("\'",'',$sum1))."',
        '".$tip."',
        '0',
        '0',
        '0',
        '0',
        '',
        '',
        '0',
        '',
        ".date("Ymd").")");
    }
    if(isset($sub2))
    {
        if($d2msk) $date2msk = 1; else $date2msk = 0;
        if($d3msk) $date3msk = 1; else $date3msk = 0;
        $date1 = substr($d1,6,4).substr($d1,3,2).substr($d1,0,2);
        $date2 = substr($d2,6,4).substr($d2,3,2).substr($d2,0,2).substr($d2,11,2).substr($d2,14,2);
        $date3 = substr($d3,6,4).substr($d3,3,2).substr($d3,0,2).substr($d3,11,2).substr($d3,14,2);
        mssql_query("
        UPDATE [etriline].[TRILINE\dmitry].[TENDER]
        SET
        CUSTOMER = '".str_replace('\"','',str_replace("\'",'',$comp))."',
        CITY = '".str_replace('\"','',str_replace("\'",'',$city))."',
        ADDRESS = '".str_replace('\"','',str_replace("\'",'',$addr))."',
        URL = '".str_replace('\"','',str_replace("\'",'',$url))."',
        DATE_START = '".$date1."',
        DATE_DOCS = '".$date2."',
        DATE_DOCS_MSK = '".$date2msk."',
        DATE_ACT = '".$date3."',
        DATE_ACT_MSK = '".$date3msk."',
        THEME = '".str_replace('\"','',str_replace("\'",'',$theme))."',
        SUM_START = '".str_replace(" ","",str_replace("\'",'',$sum1))."',
        TIP = '".$tip."',
        SUM_TRL = '".str_replace(" ","",str_replace("\'",'',$sum2))."',
        SUM_WIN = '".str_replace(" ","",str_replace("\'",'',$sum3))."',
        SUM_ZERO = '".str_replace(" ","",str_replace("\'",'',$sum4))."',
        CHECK_NUM = '".str_replace(" ","",str_replace("\'",'',$chn))."',
        MANAGER = '".$manag."',
        RESULT = '".$rez."',
        WINNER = '".str_replace('\"','',str_replace("\'",'',$winn))."',
        TXT = '".str_replace('\"','',str_replace("\'",'',$txt))."'
        WHERE ID='".$tid."'");
    }
    if(isset($do))
    {
        switch($do)
        {
            case 1:
            {
                echo '<a href="'.$PHP_SELF.'">Вернуться к списку</a><br><br>Добавление информации о новом тендере<form action="'.$PHP_SELF.'" method="POST">
                      <table cellpadding="3" cellspacing="0" border="1" style="font-family:Arial; font-size:12px">
                             <tr><td>Компания:</td><td><textarea name="comp" id="comp" cols="50" rows="5"></textarea></td></tr>
                             <tr><td>Город:</td><td><input type="text" name="city" id="city" size="65"></td></tr>
                             <tr><td>Адрес:</td><td><textarea name="addr" id="addr" cols="50" rows="5"></textarea></td></tr>
                             <tr><td>Ссылка на тендер:</td><td><input type="text" name="url" id="url" size="65"></td></tr>
                             <tr><td>Дата объявления (ДД/ММ/ГГГГ):</td><td><input type="text" name="d1" id="d1" size="65"></td></tr>
                             <tr><td>Окончание приема документов (ДД/ММ/ГГГГ/ЧЧ/ММ):</td><td><input type="text" name="d2" id="d2" size="65"></td></tr>
                             <tr><td>Время МСК?</td><td><input type="checkbox" name="d2msk" id="d2msk"></td></tr>
                             <tr><td>Дата проведения тендера (ДД/ММ/ГГГГ/ЧЧ/ММ):</td><td><input type="text" name="d3" id="d3" size="65"></td></tr>
                             <tr><td>Время МСК?</td><td><input type="checkbox" name="d3msk" id="d3msk"></td></tr>
                             <tr><td>Содержание тендера:</td><td><textarea name="theme" id="theme" cols="50" rows="5"></textarea></td></tr>
                             <tr><td>Сумма начальная:</td><td><input type="text" name="sum1" id="sum1" size="65"></td></tr>
                             <tr><td>Тип тендера:</td><td><select name="tip" id="tip" size="1">';
                             for($i=0;$i<count($types);$i++)
                             {
                                 echo '<option value="'.$i.'">'.$types[$i].'</option>';
                             }
                             echo '</select></td></tr>
                             <tr><td align="center" colspan="2"><input type="submit" name="sub1" id="sub1" value="Сохранить данные"></td></tr>
                </table>
                </form>';
                break;
            }
            case 2:
            {
                mssql_query("DELETE FROM [etriline].[TRILINE\dmitry].[TENDER] WHERE ID='".$tid."'");
                echo '<script>location.href="'.$PHP_SELF.'"</script>';
                break;
            }
            case 3:
            {
                $rs = mssql_query("SELECT * FROM [etriline].[TRILINE\dmitry].[TENDER] WHERE ID='".$tid."'");
                $rec = mssql_fetch_array($rs);
                $time1 = date("d.m.Y",mktime(0,0,0,substr($rec[5],4,2),substr($rec[5],6,2),substr($rec[5],0,4)));
                $time2 = date("d.m.Y H:i",mktime(substr($rec[6],8,2),substr($rec[6],10,2),0,substr($rec[6],4,2),substr($rec[6],6,2),substr($rec[6],0,4)));
                $time3 = date("d.m.Y H:i",mktime(substr($rec[8],8,2),substr($rec[8],10,2),0,substr($rec[8],4,2),substr($rec[8],6,2),substr($rec[8],0,4)));
                if($rec[7]) $str1 = 'checked="CHECKED"'; else $str1 = '';
                if($rec[9]) $str2 = 'checked="CHECKED"'; else $str2 = '';

                echo '<a href="'.$PHP_SELF.'">Вернуться к списку</a><br><br><form action="'.$PHP_SELF.'" method="POST">
                      <table cellpadding="3" cellspacing="0" border="1" style="font-family:Arial; font-size:12px">
                             <tr><td>Компания:</td><td><textarea name="comp" id="comp" cols="50" rows="5">'.$rec[1].'</textarea></td></tr>
                             <tr><td>Город:</td><td><input type="text" name="city" id="city" size="65" value="'.$rec[2].'"></td></tr>
                             <tr><td>Адрес:</td><td><textarea name="addr" id="addr" cols="50" rows="5">'.$rec[3].'</textarea></td></tr>
                             <tr><td>Ссылка на тендер:</td><td><input type="text" name="url" id="url" size="65" value="'.$rec[4].'"></td></tr>
                             <tr><td>Дата объявления (ДД/ММ/ГГГГ):</td><td><input type="text" name="d1" id="d1" size="65" value="'.$time1.'"></td></tr>
                             <tr><td>Окончание приема документов (ДД/ММ/ГГГГ/ЧЧ/ММ):</td><td><input type="text" name="d2" id="d2" size="65" value="'.$time2.'"></td></tr>
                             <tr><td>Время МСК?</td><td><input type="checkbox" name="d2msk" id="d2msk" '.$str1.'></td></tr>
                             <tr><td>Дата проведения тендера (ДД/ММ/ГГГГ/ЧЧ/ММ):</td><td><input type="text" name="d3" id="d3" size="65" value="'.$time3.'"></td></tr>
                             <tr><td>Время МСК?</td><td><input type="checkbox" name="d3msk" id="d3msk" '.$str2.'></td></tr>
                             <tr><td>Содержание тендера:</td><td><textarea name="theme" id="theme" cols="50" rows="5">'.$rec[10].'</textarea></td></tr>
                             <tr><td>Сумма начальная:</td><td><input type="text" name="sum1" id="sum1" size="65" value="'.$rec[11].'"></td></tr>
                             <tr><td>Сумма Трилайн:</td><td><input type="text" name="sum2" id="sum2" size="65" value="'.$rec[12].'"></td></tr>
                             <tr><td>Сумма победителя:</td><td><input type="text" name="sum3" id="sum3" size="65" value="'.$rec[13].'"></td></tr>
                             <tr><td>Сумма учетная:</td><td><input type="text" name="sum4" id="sum4" size="65" value="'.$rec[19].'"></td></tr>
                             <tr><td>Номер счета:</td><td><input type="text" name="chn" id="chn" size="65" value="'.$rec[20].'"></td></tr>
                             <tr><td>Тип тендера:</td><td><select name="tip" id="tip" size="1">';
                             for($i=0;$i<count($types);$i++)
                             {
                                 if($rec[14]==$i)
                                 echo '<option selected="SELECTED" value="'.$i.'">'.$types[$i].'</option>';
                                 else
                                 echo '<option value="'.$i.'">'.$types[$i].'</option>';
                             }
                             echo '</select></td></tr>
                             <tr><td>Результат:</td><td><select name="rez" id="rez" size="1">';
                             for($i=0;$i<count($results);$i++)
                             {
                                 if($rec[16]==$i)
                                 echo '<option selected="SELECTED" value="'.$i.'">'.$results[$i].'</option>';
                                 else
                                 echo '<option value="'.$i.'">'.$results[$i].'</option>';
                             }
                             echo '</select></td></tr>
                             <tr><td>Менеджер:</td><td><select name="manag" id="manag" size="1"><option value="0">--НЕТ--</option>';
                             $rs1 = mssql_query("SELECT * FROM [etriline].[TRILINE\dmitry].[TENDER_USER] ORDER BY FIO");
                             while($rec1=mssql_fetch_array($rs1))
                             {
                                 if($rec1[0]==$rec[15])
                                 echo '<option selected="SELECTED" value="'.$rec1[0].'">'.$rec1[3].'</option>';
                                 else
                                 echo '<option value="'.$rec1[0].'">'.$rec1[3].'</option>';
                             }
                             echo '</select></td></tr>
                             <tr><td>Победитель:</td><td><input type="text" name="winn" id="winn" size="65" value="'.$rec[17].'"></td></tr>
                             <tr><td>Примечание:</td><td><textarea name="txt" id="txt" cols="50" rows="5">'.$rec[18].'</textarea></td></tr>
                             <tr><td align="center" colspan="2"><input type="hidden" name="tid" id="tid" value="'.$tid.'"><input type="submit" name="sub2" id="sub2" value="Сохранить данные"></td></tr>
                </table>
                </form>';
                break;
            }
            case 4:
            {
                session_destroy();
                echo '<script>location.href="'.$PHP_SELF.'"</script>';
                break;
            }
        }
    }
    else
    {
        echo '<a href="'.$PHP_SELF.'?do=4">Выйти</a><br><br><a href="'.$PHP_SELF.'?do=1">Добавить тендер</a><br><br>';
        $rs = mssql_query("
        SELECT TOP 500
        ten.ID,
        ten.CUSTOMER,
        ten.CITY,
        ten.DATE_DOCS,
        ten.DATE_DOCS_MSK,
        ten.DATE_ACT,
        ten.DATE_ACT_MSK,
        ten.THEME,
        ten.SUM_START,
        ten.SUM_TRL,
        ten.SUM_WIN,
        ten.TIP,
        ten.MANAGER,
        ten.RESULT,
        ten.WINNER,
        tu.FIO,
        ten.SUM_ZERO,
        ten.CHECK_NUM
        FROM [etriline].[TRILINE\dmitry].[TENDER] ten
        left join [etriline].[TRILINE\dmitry].[TENDER_USER] tu on tu.id=ten.MANAGER
        --WHERE DATE_ACT>'".date("Ymd")."'
        ORDER BY ID DESC");
        echo '<table cellpadding="3" cellspacing="0" border="1" style="font-family:Arial;font-size:12px" frame="box">
                     <tr>
                         <td rowspan="2">Номер</td>
                         <td rowspan="2" align="center">Компания</td>
                         <td rowspan="2" align="center">Город</td>
                         <td colspan="2" align="center">Время</td>
                         <td rowspan="2" align="center">Предмет тендера</td>
                         <td rowspan="2" align="center">Вид</td>
                         <td colspan="4" align="center">Сумма</td>
                         <td rowspan="2">Менеджер</td>
                         <td rowspan="2">Номер счета</td>
                         <td rowspan="2">Результат</td>
                         <td rowspan="2">Победитель</td>
                         <td rowspan="2">Удалить</td>
                     </tr>
                     <tr>
                         <td align="center">Документы</td>
                         <td align="center">Проведение</td>
                         <td align="center">Начальная</td>
                         <td align="center">Трилайн</td>
                         <td align="center">Победитель</td>
                         <td align="center">Учетная</td>
                     </tr>';
        while($rec= mssql_fetch_array($rs))
        {
            if($rec[4]) $hour1 = 2; else $hour1 = 0;
            if($rec[6]) $hour2 = 2; else $hour2 = 0;
            $time1 = date("d.m.Y H:i",mktime((substr($rec[3],8,2)+$hour1),substr($rec[3],10,2),0,substr($rec[3],4,2),substr($rec[3],6,2),substr($rec[3],0,4)));
            $time2 = date("d.m.Y H:i",mktime((substr($rec[5],8,2)+$hour2),substr($rec[5],10,2),0,substr($rec[5],4,2),substr($rec[5],6,2),substr($rec[5],0,4)));
            if($rec[12]) $delstr = '';
            else $delstr = '<a href="'.$PHP_SELF.'?do=2&tid='.$rec[0].'">xxx</a>';
            echo '<tr>
                      <td><a href="'.$PHP_SELF.'?do=3&tid='.$rec[0].'">'.$rec[0].'</a></td>
                      <td>'.$rec[1].'</td>
                      <td>'.$rec[2].'</td>
                      <td>'.$time1.'</td>
                      <td>'.$time2.'</td>
                      <td>'.$rec[7].'</td>
                      <td>'.$types[$rec[11]].'</td>
                      <td align="right">'.money($rec[8]).'</td>
                      <td align="right">'.money($rec[9]).'</td>
                      <td align="right">'.money($rec[10]).'</td>
                      <td align="right">'.money($rec[16]).'</td>
                      <td>'.$rec[15].'</td>
                      <td>'.$rec[17].'</td>
                      <td>'.$results[$rec[13]].'</td>
                      <td>'.$rec[14].'</td>
                      <td align="center">'.$delstr.'</td>
            </tr>';
        }
        echo '</table>';
    }
}

?>
</body>
</html>
