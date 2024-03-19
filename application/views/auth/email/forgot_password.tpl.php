<!-- <html>
<body>
	<h1><?php echo sprintf(lang('email_forgot_password_heading'), $identity);?></h1>
	<p><?php echo sprintf(lang('email_forgot_password_subheading'), anchor('auth/reset_password/'. $forgotten_password_code, lang('email_forgot_password_link')));?></p>
</body>
</html> -->
<html>
<body>
<table width="100%" border="0" cellpadding="4" cellspacing="0">
  <tr>
    <td bgcolor="#AE0000" style="font-size: 20px; color: #FFFFFF;"><div align="center"><strong>SISTEM INFORMASI E-SKM</strong></div></td>
  </tr>
  <tr>
    <td style="font-size: 16px;">
<br><br>
<p>Kepada Bapak/ Ibu<br />
Di Tempat</p>
<p>Berikut kami sampaikan link reset password untuk pengguna dengan username <?php echo $identity; ?></p>
<p>Silahkan klik link berikut ini untuk mengubah password anda <?php echo anchor('auth/reset_password/'. $forgotten_password_code, lang('email_forgot_password_link')); ?></p>
<p>Terima Kasih.</p>
<p><strong><u>Admin E-SKM</u></strong></p>
<br><br>

    </td>
  </tr>
  <tr>
    <td bgcolor="#CCCCCC" style="font-size: 12px;"><div align="center">View as a Web Page<br />
    Sistem Informasi E-SKM<br />
      survei-kepuasan.com
    </div></td>
  </tr>
</table>
</body>
</html>