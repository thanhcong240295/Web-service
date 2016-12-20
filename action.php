<?php
	header('Content-type: application/json');
	//$id=$_GET["id"];
	$url= 'http://regis.agu.edu.vn/default.aspx?page=thoikhoabieu&sta=1&id=DPM135370';

	$eventTarget='__EVENTTARGET';       
	$namHocHocKy='ctl00$ContentPlaceHolder1$ctl00$ddlChonNHHK';       
	$loai='ctl00$ContentPlaceHolder1$ctl00$ddlLoai';          
	$thuTiet ='ctl00$ContentPlaceHolder1$ctl00$rad_ThuTiet';  
	$monHoc ='ctl00$ContentPlaceHolder1$ctl00$rad_MonHoc';    


	$cookies = 'cookie.txt';

	$regexViewstate = '/__VIEWSTATE\" value=\"(.*)\"/i';


	function regexExtract($text, $regex, $regs, $nthValue)
	{
		if (preg_match($regex, $text, $regs)) {
			$result = $regs[$nthValue];
		}
		else {
			$result = "";
		}
		return $result;
	}



	$ch = curl_init();


	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$data=curl_exec($ch);

	$regs="";
	$viewstate = regexExtract($data,$regexViewstate,$regs,1);

	$postData = '__VIEWSTATE='.rawurlencode($viewstate)          
	          .'&'.$eventTarget.'=ctl00$ContentPlaceHolder1$ctl00$rad_ThuTiet'
	          .'&'.$namHocHocKy.'=20142'
	          .'&'.$loai.'=1'
			  .'&'.$thuTiet.'=rad_ThuTiet';
			  //.'&'.$monHoc.'=rad_MonHoc';
			  

	curl_setOpt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_URL, $url);   
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 

	$data = curl_exec($ch);

	curl_setOpt($ch, CURLOPT_POST, FALSE);    

	$data = curl_exec($ch);

	curl_close($ch);

	$html=new DOMDocument();
	$data = '<?xml encoding="utf-8" ?>' . $data;
	libxml_use_internal_errors(true);
	$html->loadHTML($data);
	//echo $html->saveXML();
	$finder = new DomXPath($html);
	$arr=$finder->query("//*[contains(@class, 'body-table')]");
	$stringDate=$finder->query("//*[contains(@id, 'ctl00_ContentPlaceHolder1_ctl00_lblNote')]");
	preg_match("/\d{1,2}\/\d{1,2}\/\d{4}/",$stringDate->item(0)->nodeValue,$match);
	if($match==null){
		exit("Error");
	}
	$beginDate=date_create_from_format('d/m/Y',$match[0]);
	$jsonArray=array();
	$arrTime=array('11:30','7:00','7:50','8:50','9:40','10:35','13:00','13:50','14:50','15:35','16:35','17:30');
	foreach($arr as $item){
		$cols =$item->getElementsByTagName("td");
		$row=array('MMH'=>$cols->item(0)->nodeValue,'TenMH'=>$cols->item(1)->nodeValue,'NMH'=>$cols->item(2)->nodeValue,'STC'=>$cols->item(3)->nodeValue,
		'NTH'=>$cols->item(7)->nodeValue,'Phong'=>$cols->item(11)->nodeValue);
		$tietBD=intval($cols->item(9)->nodeValue);
		$startTime=$arrTime[$tietBD];
		$soTiet=intval($cols->item(10)->nodeValue);
		$ketThuc=$tietBD+$soTiet;
		if($ketThuc==6) $ketThuc=0;
		if($ketThuc==11)$ketThuc=11;
		$endTime=$arrTime[$ketThuc];
		$row=array_merge($row,array('batDau'=>$startTime,'ketThuc'=>$endTime));
		$thu=$cols->item(8)->nodeValue;	
		$thu=intval($thu)-2;
		$cals=$cols->item(13)->nodeValue;
		if($cals == '')
		{
			$cals ='0';
		}
		$dates=array();
		for($i=0;$i<strlen($cals);$i++){
			if($cals[$i]!='-'){
				$date=clone $beginDate;
				if($thu >= 0)
				{
					$date->modify('+ '.$i.' weeks + '.$thu.' days');
					array_push($dates,$date->format('d-m-Y'));
				}		
				elseif ($thu < 0 || $cals == '0') {
					$dates = array('1-1-1990');
				}
			}
		$row=array_merge($row,array('dates'=>$dates));
		}
		$jsonArray[]=$row;				
	}
	//echo $data;
	echo(json_encode($jsonArray));	
?>