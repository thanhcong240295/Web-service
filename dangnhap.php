<?php
	header('Content-type: application/json');
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
				.'&'.$pass.'=123456'
				.'&'.$login.'=Đăng Nhập';

	curl_setOpt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_URL, $url);   
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
	//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSSIE 5.01; Windows NT 5.0)");
	
	curl_exec($ch);
	curl_setopt($ch, CURLOPT_URL, 'http://regis.agu.edu.vn/Default.aspx?page=xemdiemthi');

	$data = curl_exec($ch);

	curl_setOpt($ch, CURLOPT_POST, false);    
	$data = curl_exec($ch);
	if($data == '<html><head><title>Object moved</title></head><body>
<h2>Object moved to <a href="%2fDefault.aspx%3fpage%3ddangnhap">here</a>.</h2>
</body></html>')
	{
		echo "thành công";
	}
	else
	{
		echo "Thất Bại";
	}
	echo $data;	
	$html=new DOMDocument();
	$data = '<?xml encoding="utf-8" ?>' . $data;
	libxml_use_internal_errors(true);
	$html->loadHTML($data);
	$finder = new DomXPath($html);
	$arr=$finder->query("//*[contains(@body/h2, 'Object moved to here')]");
	if($aray== 'Object moved to here'){
		exit("Error");
	}
	curl_close($ch);
?>	