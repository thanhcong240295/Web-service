<?php
	//header('Content-type: application/json');
header('Content-type: text');
	$url= 'http://regis.agu.edu.vn/default.aspx?page=dangnhap';
	$mUser = $_POST["_user"];
	$mPass = $_POST["_pass"];
	$mChude = $_POST["_chude"];
	$mNoidung = $_POST["_noidung"];
	$user = 'ctl00$ContentPlaceHolder1$ctl00$txtTaiKhoa';
	$pass = 'ctl00$ContentPlaceHolder1$ctl00$txtMatKhau';
	$login = 'ctl00$ContentPlaceHolder1$ctl00$btnDangNhap';
	$chude = 'ctl00$ContentPlaceHolder1$ctl00$txtSubject';
	$noidung = 'ctl00$ContentPlaceHolder1$ctl00$txtContent';
	$gui = 'ctl00$ContentPlaceHolder1$ctl00$btnSave';

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
	curl_setopt($ch, CURLOPT_URL, 'http://regis.agu.edu.vn/Default.aspx?page=ykiensinhvien');

	$data = curl_exec($ch);

	$postData1 = '__VIEWSTATE='.rawurlencode($viewstate)
				.'&'.$chude.'='.$mChude
				.'&'.$noidung.'='.$mNoidung
				.'&'.$gui.'=Gửi';

	curl_setOpt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData1);
	curl_setopt($ch, CURLOPT_URL, 'http://regis.agu.edu.vn/Default.aspx?page=ykiensinhvien');
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
	$data = curl_exec($ch);

	echo $data;	
	curl_close($ch);
?>	