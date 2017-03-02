<?php
$dir_root = dirname(__FILE__);
require $dir_root.'/PHPMailer/PHPMailerAutoload.php';

function send_email($host, $fromEmail, $fromPassword, $fromName, $toEmail, $toName, $subject, $content) {
	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = $host;  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;   // Enable SMTP authentication
	$mail->CharSet = 'UTF-8'; //设置邮件内容的编码
	$mail->Username = $fromEmail;                 // SMTP username
	$mail->Password = $fromPassword;                           // SMTP password

	$mail->From = $fromEmail;
	$mail->FromName = $fromName;
	$mail->addAddress($toEmail, $toName);
	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = $subject;
	$mail->msgHTML($content);

	return $mail->send();
}

@mysql_connect('localhost','root','root');
@mysql_select_db('test');
@mysql_query('set names utf8');

while (true) {
	$sql = "SELECT * FROM queue WHERE status=0 ORDER BY id ASC LIMIT 5";
	$res = @mysql_query($sql);
	$list = array();
	if ( $row = @mysql_fetch_assoc($res) ) {
		$list[] = $row;
	}
	if ( empty($list) ) {
		break;
	} else {
		foreach ( $list as $v ) {
			
			if (send_email('smtp.aliyun.com', 'zhouweiji@aliyun.com', 'zhouweiji000.', 'aliyun', $v['email'], 'sina', '从数据库中获取邮件进行队列发送'.$v['id'], file_get_contents( $dir_root.'/reg.html' ))) {
				$sql = 'UPDATE queue SET status=1,update_at='.time().' WHERE id='.$v['id'];
				@mysql_query($sql);
			}
			sleep(3);
		}
	}
}
echo 'done';