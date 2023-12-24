<!-- BEGIN: MAIN -->
<html>

<head>	
	<base href="{PHP.sys.abs_url}" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>#{MESSAGE_CODE} {MESSAGE_TITLE}</title>
	{MESSAGE_REDIRECT}
	<link href="skins/{PHP.skin}/css/service.message.css" type="text/css" rel="stylesheet">
</head>

<body>

	<main id="system">

		<div class="container">

			<div class="message-block">

				<h1 class="message-code">{MESSAGE_CODE}</h1>
				<h2 class="message-title">{MESSAGE_TITLE}</h2>

				<div class="message-body">
					{MESSAGE_BODY}
				</div>

				<div class="message-go-home">
					<a href="/" class="main-btn">{PHP.skinlang.message.gohome}</a>
				</div>

			</div>

		</div>

	</main>

</body>

</html>

<!-- END: MAIN -->