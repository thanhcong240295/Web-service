<?php
	header('Content-type: application/json');
	$url= 'http://regis.agu.edu.vn/default.aspx?page=dangnhap';
	$mUser = $_POST["_user"];
	$mPass = $_POST["_pass"];
	$user = 'ctl00$ContentPlaceHolder1$ctl00$txtTaiKhoa';
	$pass = 'ctl00$ContentPlaceHolder1$ctl00$txtMatKhau';
	$login = 'ctl00$ContentPlaceHolder1$ctl00$btnDangNhap';
	$tk = 'ctl00_Header1_ucLogout_lblNguoiDung';

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
	curl_setopt($ch, CURLOPT_URL, 'http://regis.agu.edu.vn/Default.aspx?page=xemdiemthi');

	$data = curl_exec($ch);

	curl_setOpt($ch, CURLOPT_POST, false);    
	$data = curl_exec($ch);

	//echo $data;	
	curl_close($ch);

	$html=new DOMDocument();
	$data = '<?xml encoding="utf-8" ?>' . $data;
	libxml_use_internal_errors(true);
	$html->loadHTML($data);
	//echo $html->saveXML();
	$finder = new DomXPath($html);
	$arr=$finder->query("//*[contains(@class, 'center')]");
	$jsonArray=array();
	foreach($arr as $item){
		$cols =$item->getElementsByTagName("tr");
		foreach ($cols as $a) {
			$cols =$a->getElementsByTagName("span");
			
			{
				$row=array('TT'=>$cols->item(0)->nodeValue, 'CN'=>$cols->item(1)->nodeValue);
				
			}
			$jsonArray[]=$row;
		}
		
	}
	echo(json_encode($jsonArray));	
?>	