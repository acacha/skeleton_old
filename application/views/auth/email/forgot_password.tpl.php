<html>
<body>
Per iniciar el procés de restabliment de la contrasenya del vostre compte ( <?php echo $identity;?> ) de l'aplicació <?php echo $app_name; ?> de <?php echo $organization; ?>, feu clic a l'enllaç següent:

<p>&nbsp;&nbsp;&nbsp;<?php echo anchor($forgotten_password_email_template. "/" .$forgotten_password_code, lang('email_forgot_password_link'));?></p>

<p>Si en fer clic a l'enllaç anterior no es produeix cap acció, copieu l'URL i enganxeu-lo en una finestra nova del navegador.</p>

<p>Si heu rebut aquest correu per equivocació, és possible que un altre usuari hagi introduït la vostra adreça electrònica per error en intentar restablir la seva contrasenya.</p>

<p>Si no havíeu iniciat la sol·licitud, no cal que dugueu a terme cap acció i podeu ignorar tranquil·lament aquest correu electrònic.</p>

<p>Atentament,<br/>
L'equip de manteniment de l'aplicació <?php echo $app_name; ?> de <?php echo $organization; ?></p>

<p>Nota: Aquesta adreça electrònica no pot acceptar respostes. Per solucionar un problema o obtenir més informació sobre el compte, consulteu al vostre tutor.</p>
</body>
</html>
