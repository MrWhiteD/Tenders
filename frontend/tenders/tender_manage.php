<?
error_reporting(1);
session_start();
?>
<html>
<head>
       <title>Работа с тендерами</title>
</head>
<body style="font-family:Arial; font-size:12px;">
<?php
mssql_connect('192.168.1.103','www_user','Axm6FpWEY4');
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

if(!isset($_SESSION["MANAG"]))
{
    if(isset($err)) echo '<center style="font-family:Arial; font-size:12px; color:#FF0000; font-weight:bold;">У вас нет прав доступа к этой странице</center>';
    if(!isset($sub))
    {
        echo '<center><form action='.$PHP_SELF.' method="GET"><table><tr><td>Логин</td><td><input type="text" name="login" size="30"></td></tr><tr><td>Пароль</td><td><input type="password" name="pasw" size="30"></td></tr><tr><td align="center" colspan="2"><input type="submit" value="Вход" name="sub"></td></tr></form>';
    }
    else
    {
        $rs = mssql_query("SELECT TIP,ID FROM etriline.[TRILINE\dmitry].[TENDER_USER] WHERE LOGIN='".$login."' AND PASS='".md5($pasw)."'");
        $rec = mssql_fetch_array($rs);
        if($rec[1])
        {
            $_SESSION["OTDEL"] = $rec[0];
            $_SESSION["MANAG"] = $rec[1];
            echo '<script>location.href="'.$PHP_SELF.'"</script>';
        }
        else
        {
            echo '<script>location.href="'.$PHP_SELF.'?err=1"</script>';
        }
    }
}
elseif($_SESSION["MANAG"])
{
    if(isset($sub2))
    {
        $sum2 = ceil(str_replace(".",",",str_replace(" ","",$sum2)));
        $sum3 = ceil(str_replace(".",",",str_replace(" ","",$sum3)));
        $sum4 = ceil(str_replace(".",",",str_replace(" ","",$sum4)));
        if($d2msk) $date2msk = 1; else $date2msk = 0;
        if($d3msk) $date3msk = 1; else $date3msk = 0;
        $date1 = substr($d1,6,4).substr($d1,3,2).substr($d1,0,2);
        $date2 = substr($d2,6,4).substr($d2,3,2).substr($d2,0,2).substr($d2,11,2).substr($d2,14,2);
        $date3 = substr($d3,6,4).substr($d3,3,2).substr($d3,0,2).substr($d3,11,2).substr($d3,14,2);
        $rec = mssql_fetch_array(mssql_query("SELECT DATE_USE FROM [etriline].[TRILINE\dmitry].[TENDER] WHERE ID='".$tid."'"));
        $use = $rec[0];
        if($manag)
        {
        	if($use)
        	{
        		$struse = ' ';
        	}
        	else
        	{
        		$struse = ", DATE_USE='".date("YmdHi")."' ";
        		$d = date('Y.m.d H:i:s');
        		mssql_query("INSERT INTO [etriline].[TRILINE\dmitry].[TENDER_LOG] (MANAG,TXT,TENDER,DAT) VALUES ('".$manag."','Взят в работу тендер','".$tid."','".$d."')");
        	}
        }
        else
        {
        	$struse = ", DATE_USE='' ";
        	$mm = mssql_fetch_array(mssql_query("SELECT MANAGER FROM [etriline].[TRILINE\dmitry].[TENDER] WHERE ID=".$tid));
        	$d = date('Y.m.d H:i:s');
        	mssql_query("INSERT INTO [etriline].[TRILINE\dmitry].[TENDER_LOG] (MANAG,TXT,TENDER,DAT) VALUES ('".$mm[0]."','Отказ от тендера','".$tid."','".$d."')");
        }

        mssql_query("
        UPDATE [etriline].[TRILINE\dmitry].[TENDER]
        SET
        SUM_TRL = '".str_replace(" ","",str_replace("\'",'',$sum2))."',
        SUM_WIN = '".str_replace(" ","",str_replace("\'",'',$sum3))."',
        MANAGER = '".$manag."',
        RESULT = '".$rez."',
        WINNER = '".str_replace('\"','',str_replace("\'",'',$winn))."',
        TXT = '".str_replace('\"','',str_replace("\'",'',$txt))."',
        SUM_ZERO = '".str_replace(" ","",str_replace("\'",'',$sum4))."',
        CHECK_NUM = '".$chn."'".$struse."
        WHERE ID='".$tid."'");
    }
    if(isset($do))
    {
        switch($do)
        {
            case 3:
            {
                $rs = mssql_query("SELECT * FROM [etriline].[TRILINE\dmitry].[TENDER] WHERE ID='".$tid."'");
                $rec = mssql_fetch_array($rs);
                $time1 = date("d.m.Y",mktime(0,0,0,substr($rec[5],4,2),substr($rec[5],6,2),substr($rec[5],0,4)));
                $time2 = date("d.m.Y H:i",mktime(substr($rec[6],8,2),substr($rec[6],10,2),0,substr($rec[6],4,2),substr($rec[6],6,2),substr($rec[6],0,4)));
                $time3 = date("d.m.Y H:i",mktime(substr($rec[8],8,2),substr($rec[8],10,2),0,substr($rec[8],4,2),substr($rec[8],6,2),substr($rec[8],0,4)));
                if($rec[21]!="")
                $time4 = date("d.m.Y",mktime(0,0,0,substr($rec[21],4,2),substr($rec[21],6,2),substr($rec[21],0,4)));
                else
                $time4 = "";
                if($rec[7]) $str1 = ' МСК'; else $str1 = '';
                if($rec[9]) $str2 = ' МСК'; else $str2 = '';
                echo '<a href="'.$PHP_SELF.'">Вернуться к списку</a><br><br><form action="'.$PHP_SELF.'" method="POST">
                      <table cellpadding="3" cellspacing="0" border="1" style="font-family:Arial; font-size:12px">
                             <tr><td>Компания:</td><td>'.$rec[1].'</td></tr>
                             <tr><td>Город:</td><td>'.$rec[2].'</td></tr>
                             <tr><td>Адрес:</td><td>'.$rec[3].'</td></tr>
                             <tr><td>Ссылка на тендер:</td><td><a href="'.$rec[4].'" target="_blank">'.$rec[4].'</a></td></tr>
                             <tr><td>Дата объявления:</td><td>'.$time1.'</td></tr>
                             <tr><td>Дата добавления:</td><td>'.$time4.'</td></tr>
                             <tr><td>Окончание приема документов:</td><td>'.$time2.$str1.'</td></tr>
                             <tr><td>Дата проведения тендера:</td><td>'.$time3.$str2.'</td></tr>
                             <tr><td>Содержание тендера:</td><td>'.$rec[10].'</td></tr>
                             <tr><td>Сумма начальная:</td><td>'.money($rec[11]).'</td></tr>
                             <tr><td>Сумма Трилайн:</td><td><input type="text" name="sum2" id="sum2" size="65" value="'.money($rec[12]).'"></td></tr>
                             <tr><td>Сумма победителя:</td><td><input type="text" name="sum3" id="sum3" size="65" value="'.money($rec[13]).'"></td></tr>
                             <tr><td>Сумма учетная:</td><td><input type="text" name="sum4" id="sum4" size="65" value="'.money($rec[19]).'"></td></tr>
                             <tr><td>Номер счета:</td><td><input type="text" name="chn" id="chn" size="65" value="'.$rec[20].'"></td></tr>
                             <tr><td>Тип тендера:</td><td>'.$types[$rec[14]].'</td></tr>
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
                             if($_SESSION["MANAG"]==0)
                             $rs1 = mssql_query("SELECT * FROM [etriline].[TRILINE\dmitry].[TENDER_USER] WHERE ID='".$_SESSION["MANAG"]."'");
                             else
                             $rs1 = mssql_query("SELECT * FROM [etriline].[TRILINE\dmitry].[TENDER_USER] ORDER BY FIO");
                             while($rec1 = mssql_fetch_array($rs1))
                             {
                                 if($rec1[0]==$rec[15])
                                 echo '<option selected="SELECTED" value="'.$rec1[0].'">'.$rec1[3].'</option>';
                                 else
                                 echo '<option value="'.$rec1[0].'">'.$rec1[3].'</option>';
                             }
                             echo '</select></td></tr>
                             <tr><td>Победитель:</td><td><input type="text" name="winn" id="winn" size="65" value="'.$rec[17].'"></td></tr>
                             <tr><td>Примечание:</td><td><textarea name="txt" id="txt" cols="50" rows="5">'.$rec[18].'</textarea></td></tr>';
                             if($rec[15]=='0' || $rec[15]==$_SESSION["MANAG"])
                             echo '<tr><td align="center" colspan="2">
                             <input type="hidden" name="tid" id="tid" value="'.$tid.'">
                             <input type="submit" name="sub2" id="sub2" value="Сохранить данные"></td></tr>';
                echo'
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
        echo '<a href="'.$PHP_SELF.'?do=4">Выйти</a><br><br>';
        echo '<a href="'.$PHP_SELF.'?showall=1">Посмотреть все</a><br><br>';
        //Если выбрано "показать все"
        if(!isset($showall)) $strtop = ' TOP 500 ';
        else $strtop = ' ';
        //Если выбрана сортировка
        switch($sort)
     	{
     		case 'num':
     		{
     			$sortstr = " ORDER BY ten.ID DESC";
     			break;
     		}
     		case 'kl':
     		{
     			$sortstr = " ORDER BY ten.CUSTOMER,ten.DATE_DOCS DESC,ten.DATE_ADD DESC";
     			break;
     		}
     		case 'city':
     		{
     			$sortstr = " ORDER BY ten.CITY,ten.DATE_DOCS DESC,ten.DATE_ADD DESC";
     			break;
     		}
     		case 'manag':
     		{
     			$sortstr = " ORDER BY tu.FIO,ten.DATE_DOCS DESC,ten.DATE_ADD DESC";
     			break;
     		}
     		default:
     		{
     			$sortstr = " ORDER BY ten.ID DESC";
     			break;
     		}
     	}
        //Если был наложен фильтр
        if(!isset($search))
        {
            $rs = mssql_query("
            SELECT TOP 1000
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
            ten.TXT,
            ten.SUM_ZERO,
            ten.CHECK_NUM,
            ten.DATE_ADD
            FROM [etriline].[TRILINE\dmitry].[TENDER] ten
            left join [etriline].[TRILINE\dmitry].[TENDER_USER] tu on tu.id=ten.MANAGER
            where ten.ID in (select".$strtop."id from [etriline].[TRILINE\dmitry].[TENDER] order by id desc )
            ".$sortstr);
        }
        else
        {
			
			$managers = implode(",",$manag);
			//echo $managers;
			if($col) $str = " TOP ".$col." "; else $str = " ";
            $zapros = "
            SELECT".$str."
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
            ten.TXT,
            ten.SUM_ZERO,
            ten.CHECK_NUM,
            ten.DATE_ADD
            FROM [etriline].[TRILINE\dmitry].[TENDER] ten
            left join [etriline].[TRILINE\dmitry].[TENDER_USER] tu on tu.id=ten.MANAGER
            where ten.MANAGER IN (".$managers.") ORDER BY ten.ID DESC";
            $rs = mssql_query($zapros);
        }
        echo '<form action="'.$PHP_SELF.'" method="POST">Фильтр по менеджеру:&nbsp;<select multiple="MULTIPLE" name="manag[]" id="manag[]" size="10"><option value="0">--НЕТ--</option>';
        $rm = mssql_query("SELECT ID,FIO FROM [etriline].[TRILINE\dmitry].[TENDER_USER] ORDER BY FIO");
        while($recm = mssql_fetch_array($rm))
        {
            echo '<option value="'.$recm[0].'">'.$recm[1].'</option>';
        }
        echo '</select>Кол-во записей:&nbsp;<select name="col" id="col"><option value="0">Все</option><option value="100">100 последних</option><option value="50">50 последних</option><option value="10">10 последних</option></select><input type="submit" name="search" id="search" value="Поиск"><a href="'.$PHP_SELF.'">Сбросить фильтр</a></form>';
        echo '<table cellpadding="3" cellspacing="0" border="1" style="font-family:Arial;font-size:12px" frame="box">';
                     /*
                     echo '<form action="'.$PHP_SELF.'" method="POST">
                     <tr>
                         <td colspan="2"><b>Выберите условия поиска:</b></td>
                         <td><select name="s_city" id="s_city" size="1"><option value="0">--Любой--</option>';
                         $rc = mssql_query("SELECT DISTINCT CITY FROM [etriline].[TRILINE\dmitry].[TENDER] ORDER BY CITY");
                         while($recc = mssql_fetch_array($rc))
                         {
                             echo '<option value="'.$recc[0].'">'.$recc[0].'</option>';
                         }
                         $dat1 = date("d.m.Y",mktime(0,0,0,date("m"),date("d"),date("Y")-1));
                         $dat2 = date("d.m.Y",mktime(0,0,0,date("m"),date("d"),date("Y")+1));
                         echo '</select></td>
                         <td colspan="2">с:&nbsp;&nbsp;<input type="text" name="s_dat_b" id="s_dat_b" size="8" value="'.$dat1.'">&nbsp;&nbsp;по:&nbsp;&nbsp;<input type="text" name="s_dat_e" id="s_dat_e" size="8" value="'.$dat2.'"></td>
                         <td></td>
                         <td></td>

                         <td><select name="tip" id="tip" size="1"><option value="100">--Любой--</option>';
                         for($i=0;$i<count($types);$i++)
                         {
                             echo '<option value="'.$i.'">'.$types[$i].'</option>';
                         }
                         echo '</select></td>
                         <td colspan="4"></td>
                         <td><select name="manag" id="manag" size="1"><option value="1000">--Любой--</option><option value="0">--Не выбран--</option>';
                         $rm = mssql_query("SELECT ID,FIO FROM [etriline].[TRILINE\dmitry].[TENDER_USER] ORDER BY FIO");
                         while($recm = mssql_fetch_array($rm))
                         {
                             echo '<option value="'.$recm[0].'">'.$recm[1].'</option>';
                         }
                         echo '</select></td>
                         <td colspan="3" align="center"><input type="submit" name="search" id="search" value="Поиск"></td>
                     </tr>
                     </form>';*/
         echo       '<tr>
                         <td rowspan="2"><a href="'.$PHP_SELF.'?sort=num">Код</a></td>
                         <td rowspan="2" align="center"><a href="'.$PHP_SELF.'?sort=kl">Компания</a></td>
                         <td rowspan="2" align="center"><a href="'.$PHP_SELF.'?sort=city">Город</a></td>
                         <td colspan="3" align="center">Время</td>
                         <td rowspan="2" align="center">Предмет тендера</td>
                         <td rowspan="2" align="center">Примечание</td>
                         <td rowspan="2" align="center">Вид</td>
                         <td colspan="4" align="center">Сумма</td>
                         <td rowspan="2" align="center"><a href="'.$PHP_SELF.'?sort=manag">Менеджер</a></td>
                         <td rowspan="2" align="center">Номер счета</td>
                         <td rowspan="2" align="center">Результат</td>
                         <td rowspan="2" align="center">Победитель</td>
                     </tr>
                     <tr>
                         <td align="center" title="Добавлено">Add</td>
                         <td align="center" title="Документы">Doc</td>
                         <td align="center" title="Проведение">Act</td>
                         <td align="center">Начальная</td>
                         <td align="center">Трилайн</td>
                         <td align="center">Победитель</td>
                         <td align="center">Учетная</td>
                     </tr>';
        while($rec = mssql_fetch_array($rs))
        {
            if($rec[4]) $hour1 = 2; else $hour1 = 0;
            if($rec[6]) $hour2 = 2; else $hour2 = 0;
            $time1 = date("d.m.Y H:i",mktime((substr($rec[3],8,2)+$hour1),substr($rec[3],10,2),0,substr($rec[3],4,2),substr($rec[3],6,2),substr($rec[3],0,4)));
            $time2 = date("d.m.Y H:i",mktime((substr($rec[5],8,2)+$hour2),substr($rec[5],10,2),0,substr($rec[5],4,2),substr($rec[5],6,2),substr($rec[5],0,4)));
            //Укороченный вариант даты
            $time3 = date("d.M",mktime((substr($rec[3],8,2)+$hour1),substr($rec[3],10,2),0,substr($rec[3],4,2),substr($rec[3],6,2),substr($rec[3],0,4)));
            $time4 = date("d.M",mktime((substr($rec[5],8,2)+$hour2),substr($rec[5],10,2),0,substr($rec[5],4,2),substr($rec[5],6,2),substr($rec[5],0,4)));
            if($rec[19]!="")
            $time5 = date("d.M",mktime(substr($rec[19],8,2),substr($rec[19],10,2),0,substr($rec[19],4,2),substr($rec[19],6,2),substr($rec[19],0,4)));
            else
            $time5 = "";
            //Раскрашиваем табличку
            $col = "#FFFFFF";
            $col1 = "#000000";
            if($rec[15]) {$col = "#DDDDDD"; $col1 = "#000000";}
            if($rec[13]==5) {$col = "#00DD00"; $col1 = "#FFFFFF";}
            if($rec[13]==6) {$col = "#DD0000"; $col1 = "#FFFFFF";}
            if($rec[13]==4) {$col = "#DD0000"; $col1 = "#FFFFFF";}
            if($rec[13]==3) {$col = "#DD0000"; $col1 = "#FFFFFF";}
            if($rec[13]==2) {$col = "#DD0000"; $col1 = "#FFFFFF";}
            if($rec[13]==7) {$col = "#DD0000"; $col1 = "#FFFFFF";}
            if($rec[13]==8) {$col = "#DD0000"; $col1 = "#FFFFFF";}
            if($rec[13]==9) {$col = "#0000FF"; $col1 = "#FFFFFF";}
            if(substr($rec[3],0,8)<date("Ymd") && !$rec[15]) $col="#FFFF00";
            echo '<tr style="background-color:'.$col.'; color:'.$col1.';">
                      <td><a href="'.$PHP_SELF.'?do=3&tid='.$rec[0].'">'.$rec[0].'</a></td>
                      <td>'.$rec[1].'</td>
                      <td>'.$rec[2].'</td>
                      <td>'.$time5.'</td>
                      <td>'.$time3.'</td>
                      <td>'.$time4.'</td>
                      <td>'.$rec[7].'</td>
                      <td>'.$rec[16].'</td>
                      <td>'.$types[$rec[11]].'</td>
                      <td align="right">'.money($rec[8]).'</td>
                      <td align="right">'.money($rec[9]).'</td>
                      <td align="right">'.money($rec[10]).'</td>
                      <td align="right">'.money($rec[17]).'</td>
                      <td>'.$rec[15].'</td>
                      <td>'.$rec[18].'</td>
                      <td>'.$results[$rec[13]].'</td>
                      <td>'.$rec[14].'</td>
            </tr>';
        }
        echo '</table>';
    }
}

?>
</body>
</html>
