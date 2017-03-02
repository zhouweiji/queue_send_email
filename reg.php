<?php
if ( $_POST ) {
	if ( !empty( $_POST['email'] ) && !empty( $_POST['password'] ) ) {
		$conn = @mysql_connect('localhost','root','root');
		@mysql_select_db('test');
		@mysql_query('set names utf8');

		$sql = 'SELECT * FROM user WHERE email=\''.$_POST['email'].'\'';
		$result = @mysql_query($sql);
		if (mysql_fetch_assoc($result)) {
			exit('用户已存在');
		} else {
			$sql = 'INSERT INTO user (email,password,create_at,update_at) VALUES (\''.$_POST['email'].'\','.$_POST['password'].','.time().','.time().')';
			//echo $sql;
			$res = @mysql_query($sql);
			if ($res) {
				$sql = 'INSERT INTO queue (email,status,create_at,update_at) VALUES (\''.$_POST['email'].'\',0,'.time().','.time().')';
				$res = @mysql_query($sql);
				mysql_close($conn);
				exit($res);
			} else {
				exit('注册失败');
			}
		}
		mysql_close($conn);
	}
	

}