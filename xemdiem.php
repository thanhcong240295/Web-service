<?php
	//header('Content-type: text/html');
	header('Content-type: application/json');
	$mUser = $_POST["_user"];
	$mPass = $_POST["_pass"];
	$url= 'http://regis.agu.edu.vn/default.aspx?page=dangnhap';

	$user = 'ctl00$ContentPlaceHolder1$ctl00$txtTaiKhoa';
	$pass = 'ctl00$ContentPlaceHolder1$ctl00$txtMatKhau';
	$login = 'ctl00$ContentPlaceHolder1$ctl00$btnDangNhap';
	$all = 'ctl00$ContentPlaceHolder1$ctl00$txtChonHK';

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
				.'&'.$user.'='.$mUser
				.'&'.$pass.'='.$mPass
				.'&'.$login.'=Đăng Nhập';

	curl_setOpt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_URL, $url);   
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
	//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
	
	curl_exec($ch);
	curl_setOpt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_URL, 'http://regis.agu.edu.vn/Default.aspx?page=xemdiemthi');
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
	
	$data = curl_exec($ch);

	//echo $data;	
	
	curl_close($ch);
	

	$html=new DOMDocument();
	$data = '<?xml encoding="utf-8" ?>' . $data;
	libxml_use_internal_errors(true);
	$html->loadHTML($data);
	//echo $html->saveXML();
	$finder = new DomXPath($html);
	$arr=$finder->query("//*[contains(@class, 'view-table')]");
	$jsonArray=array();
	foreach($arr as $item){
		$cols =$item->getElementsByTagName("tr");
		foreach ($cols as $a) {
			$cols =$a->getElementsByTagName("span");
			if(!strcmp($cols->item(0)->nodeValue, 'STT') || empty($cols->item(1)->nodeValue))
			{
				
			}
			else
			{
				$row=array('MMH'=>$cols->item(1)->nodeValue,'TenMH'=>$cols->item(2)->nodeValue,'STC'=>$cols->item(3)->nodeValue,'KT'=>$cols->item(4)->nodeValue,'THI'=>$cols->item(5)->nodeValue,'DKT'=>$cols->item(7)->nodeValue,'THI1'=>$cols->item(8)->nodeValue,'THI2'=>$cols->item(9)->nodeValue,'THI3'=>$cols->item(10)->nodeValue,'TK10'=>$cols->item(11)->nodeValue,'TKCH'=>$cols->item(12)->nodeValue);
				$jsonArray[]=$row;
			}
			
		}
		
	}
	echo(json_encode($jsonArray));	
	
?>	