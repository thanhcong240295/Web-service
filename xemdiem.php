<?php
	header('Content-type: text/html; charset=utf-8');
	$url= 'http://regis.agu.edu.vn/default.aspx?page=dangnhap';

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
				.'&'.$user.'=dpm135369'
				.'&'.$pass.'=dinhcong'
				.'&'.$login.'=Đăng Nhập';

	curl_setOpt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_URL, $url);   
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSSIE 5.01; Windows NT 5.0)");
	
	curl_exec($ch);
	curl_setopt($ch, CURLOPT_URL, 'http://regis.agu.edu.vn/Default.aspx?page=xemdiemthi');

	$data = curl_exec($ch);

	curl_setOpt($ch, CURLOPT_POST, false);    
	
	//echo $data;	
	$html=new DOMDocument();
	$data = '<?xml encoding="utf-8" ?>' . $data;
	libxml_use_internal_errors(true);
	$html->loadHTML($data);
	$finder = new DomXPath($html);

	curl_close($ch);
?>	