<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow" />

<link rel="stylesheet" href="<?php echo BASE_URI_RESOURCE; ?>css/styles.css"/>

<title>Ауторизација</title>

</head>

<body>
	
	<div align="center" style="font-family:Arial">
		<form action="<?php echo BASE_URI_SERVICE . "users/authenticate/"; ?>" method="post">
		
			<table cellpadding="5">
			
				<tr>
					<td colspan="2" height="250"><img src="<?php echo BASE_URI_RESOURCE . "images/movie_reel_small.png"; ?>"/></td>
				</tr>
				
				<tr>
					<td colspan="2" style="color:#FF0000"><?php if( isset( $errorMessage ) ) echo $errorMessage;?></td>
				</tr>
				
				<tr>
					<td>Корисничко име: </td>
					<td><input type="text" name="movies_admin_username" /></td>
				</tr>
				<tr>
					<td>Шифра: </td>
					<td><input type="password" name="movies_admin_password" /></td>
				</tr>
				
				<tr>
					<td><input type="submit" name="movies_admin_submit_login" value="Ауторизација" class="lock-button controll-button"/></td>
				</tr>
			</table>
		</form>
	
	</div>

</body>
</html>
