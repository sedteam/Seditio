<!-- BEGIN: HEADER -->{HEADER_DOCTYPE}
<html>
<head>
<title>{HEADER_TITLE}</title>
{HEADER_METAS}
{HEADER_COMPOPUP}
<link rel="canonical" href="{HEADER_CANONICAL_URL}" />
<link href="skins/{PHP.skin}/admin/css/framework.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/admin/css/main.css" type="text/css" rel="stylesheet" />	
<link href="skins/{PHP.skin}/admin/css/fonts.css" type="text/css" rel="stylesheet" />	
{HEADER_UPLOADER}
</head>
<body>
	<div id="body-wrapper"><!-- Wrapper for the radial gradient background --> 
	
		<div id="user">
		
		<!-- BEGIN: USER -->

		<ul> 
			<li><span>{HEADER_LOGSTATUS}</span></li>
			<li>{HEADER_USER_ADMINPANEL}</li>
			<li>{HEADER_USERLIST}</li>
			<li>{HEADER_USER_PROFILE}</li>
			<li>{HEADER_USER_PFS}</li>
			<li>{HEADER_USER_PMREMINDER}</li>
			<li>{HEADER_USER_LOGINOUT}</li>
		</ul>
		
		<!-- END: USER -->

		<!-- BEGIN: GUEST -->
		
		<ul>
		  <li><a href="{PHP.out.auth_link}">{PHP.skinlang.header.Login}</a></li>
		  <li><a href="{PHP.out.register_link}">{PHP.skinlang.header.Register}</a></li>
		</ul>
		
		<!-- END: GUEST -->  
		
		</div> 	
		
<!-- END: HEADER -->
