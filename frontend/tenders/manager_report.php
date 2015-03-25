<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<html>
<head>
       <title>Отчет по работе менеджеров с тендерами</title>
</head>
<body style="font-family:Arial; font-size:12px;">
<?php
error_reporting(0);
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
mssql_connect('192.168.1.103','www_user','Axm6FpWEY4');
$results = Array("--НЕТ--","В работе","Не допустили","Проигран - Цена","Не успели","Выигран","Не прошли по цене","Закуп не дал цены вовремя","Не проходим по условиям");   //Не менять порядок!!!
if(isset($sub))
{
    $datb = substr($dat1,0,4).substr($dat1,5,2).substr($dat1,8,2);
    $date = substr($dat2,0,4).substr($dat2,5,2).substr($dat2,8,2);
    $rs = mssql_query("
    SELECT
	MAX(FIO),
	MANAGER,
	RESULT,
	count(DATE_USE),
	sum(SUM_START)
  	FROM
	[etriline].[TRILINE\dmitry].[TENDER] tt
  	left join etriline.[TRILINE\dmitry].TENDER_USER tu on tt.MANAGER=tu.ID
  	where
	DATE_USE>='".$datb."'
	AND DATE_USE<='".$date."'
	AND MANAGER>0
	AND RESULT>=0
  	group by MANAGER,RESULT
  	order by MAX(FIO)");
	$a = Array();
	$str = "";
	$i = 0;
	while($row = mssql_fetch_row($rs))
    {
		if($row[0]!=$a[$i][10])
		{
		    $i++;
		    $a[$i][10] = $row[0];
		    $a[$i][11]+= $row[3];
		    $a[$i][12]+= $row[4];
		    $a[$i][$row[2]][0] = $row[3];
		    $a[$i][$row[2]][1] = $row[4];
		}
		else
		{
		    $a[$i][11]+= $row[3];
		    $a[$i][12]+= $row[4];
			$a[$i][$row[2]][0] = $row[3];
		    $a[$i][$row[2]][1] = $row[4];
		}
    }
    echo '<b>Данные за период: '.$dat1.' - '.$dat2.'</b><br><br><table cellpadding="3" cellspacing="0" border="1" style="font-family:Arial; font-size:12px">
                <tr>
                     <td rowspan="2" align="center"><b>Менеджер</b></td>
                     <td colspan="2" align="center"><b>Итого</b></td>
                     <td colspan="2" align="center"><b>Выиграно</b></td>
                     <td colspan="2" align="center"><b>Не допустили</b></td>
                     <td colspan="2" align="center"><b>Проигран - Цена</b></td>
                     <td colspan="2" align="center"><b>Не успели</b></td>
                     <td colspan="2" align="center"><b>Не прошли по цене</b></td>
                     <td colspan="2" align="center"><b>Закуп не дал цены вовремя</b></td>
                     <td colspan="2" align="center"><b>Не проходим по условиям</b></td>
                     <td colspan="2" align="center"><b>В работе</b></td>
                     <td colspan="2" align="center"><b>Отправлено</b></td>
				</tr>
				<tr>
                     <td align="center">Кол-во</td>
                     <td align="center">Сумма</td>
                     <td align="center">Кол-во</td>
                     <td align="center">Сумма</td>
                     <td align="center">Кол-во</td>
                     <td align="center">Сумма</td>
                     <td align="center">Кол-во</td>
                     <td align="center">Сумма</td>
                     <td align="center">Кол-во</td>
                     <td align="center">Сумма</td>
                     <td align="center">Кол-во</td>
                     <td align="center">Сумма</td>
                     <td align="center">Кол-во</td>
                     <td align="center">Сумма</td>
                     <td align="center">Кол-во</td>
                     <td align="center">Сумма</td>
                     <td align="center">Кол-во</td>
                     <td align="center">Сумма</td>
                     <td align="center">Кол-во</td>
                     <td align="center">Сумма</td>
                 </tr>';
    for($i=1;$i<count($a)+1;$i++)
    {
        echo '<tr>
                  <td>'.$a[$i][10].'</td>
                  <td align="right">'.$a[$i][11].'</td>
                  <td align="right">'.money($a[$i][12]).'</td>
                  <td align="right">'.$a[$i][5][0].'</td>
                  <td align="right">'.money($a[$i][5][1]).'</td>
                  <td align="right">'.$a[$i][2][0].'</td>
                  <td align="right">'.money($a[$i][2][1]).'</td>
                  <td align="right">'.$a[$i][3][0].'</td>
                  <td align="right">'.money($a[$i][3][1]).'</td>
                  <td align="right">'.$a[$i][4][0].'</td>
                  <td align="right">'.money($a[$i][4][1]).'</td>
                  <td align="right">'.$a[$i][6][0].'</td>
                  <td align="right">'.money($a[$i][6][1]).'</td>
                  <td align="right">'.$a[$i][7][0].'</td>
                  <td align="right">'.money($a[$i][7][1]).'</td>
                  <td align="right">'.$a[$i][8][0].'</td>
                  <td align="right">'.money($a[$i][8][1]).'</td>
                  <td align="right">'.($a[$i][1][0]+$a[$i][0][0]).'</td>
                  <td align="right">'.money($a[$i][1][1]+$a[$i][0][1]).'</td>
                  <td align="right">'.$a[$i][9][0].'</td>
                  <td align="right">'.money($a[$i][9][1]).'</td>
        </tr>';
        $sum1+= $a[$i][11];
        $sum2+= $a[$i][12];
        $sum3+= $a[$i][5][0];
        $sum4+= $a[$i][5][1];
        $sum5+= $a[$i][2][0];
        $sum6+= $a[$i][2][1];
        $sum7+= $a[$i][3][0];
        $sum8+= $a[$i][3][1];
        $sum9+= $a[$i][4][0];
        $sum10+= $a[$i][4][1];
        $sum11+= $a[$i][6][0];
        $sum12+= $a[$i][6][1];
        $sum13+= $a[$i][7][0];
        $sum14+= $a[$i][7][1];
        $sum15+= $a[$i][8][0];
        $sum16+= $a[$i][8][1];
        $sum17+= $a[$i][1][0]+$a[$i][0][0];
        $sum18+= $a[$i][1][1]+$a[$i][0][1];
        $sum19+= $a[$i][9][0];
        $sum20+= $a[$i][9][1];
    }
	$rs1 = mssql_query("
	SELECT count(DATE_ADD),sum(SUM_START)
  	FROM [etriline].[TRILINE\dmitry].[TENDER] tt
  	left join etriline.[TRILINE\dmitry].TENDER_USER tu on tt.MANAGER=tu.ID
  	where DATE_ADD>='".$datb."'
	AND DATE_ADD<='".$date."'
  	and MANAGER=0
  	group by MANAGER
  	order by MAX(FIO)");
  	$row1 = mssql_fetch_row($rs1);
  	
	echo '<tr style="background-color:#FF0000; color:#FFFFFF; font-weight:bold;">
			<td>Итого</td>
			<td align="right">'.$sum1.'</td>
			<td align="right">'.money($sum2).'</td>
			<td align="right">'.$sum3.'</td>
			<td align="right">'.money($sum4).'</td>
			<td align="right">'.$sum5.'</td>
			<td align="right">'.money($sum6).'</td>
			<td align="right">'.$sum7.'</td>
			<td align="right">'.money($sum8).'</td>
			<td align="right">'.$sum9.'</td>
			<td align="right">'.money($sum10).'</td>
			<td align="right">'.$sum11.'</td>
			<td align="right">'.money($sum12).'</td>
			<td align="right">'.$sum13.'</td>
			<td align="right">'.money($sum14).'</td>
			<td align="right">'.$sum15.'</td>
			<td align="right">'.money($sum16).'</td>
			<td align="right">'.$sum17.'</td>
			<td align="right">'.money($sum18).'</td>
			<td align="right">'.$sum19.'</td>
			<td align="right">'.money($sum20).'</td>
		</tr>
		<tr style="background-color:#FFFF00; color:#000000; font-weight:bold;">
			<td>Не взято в работу:</td>
			<td align="right">'.$row1[0].'</td>
			<td align="right">'.money($row1[1]).'</td>
			<td colspan="18"></td>
		</tr></table><br><a href="javascript:history.back()">Назад</a>';
}
else
{
    echo '<form action="'.$PHP_SELF.'" method="GET">
    Начало периода:
    <input type="text" name="dat1" id="dat1" value="'.date("Y.m.d").'"><br>
    Конец периода:
    <input type="text" name="dat2" id="dat2" value="'.date("Y.m.d").'"><br>
    <input type="submit" name="sub" id="sub" value="Получить">';
}
?>
</form>
</body>
</html>
