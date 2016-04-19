<!-- BEGIN: HEADER -->{HEADER_DOCTYPE}
<html>
<head>
<title>{HEADER_TITLE}</title>
{HEADER_METAS}
{HEADER_COMPOPUP}
<link rel="canonical" href="{HEADER_CANONICAL_URL}" />
<!-- Main Stylesheet -->
<link rel="stylesheet" href="skins/{PHP.skin}/admin/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="skins/{PHP.skin}/admin/css/font-awesome.css" type="text/css" media="screen" />		
<!-- jQuery -->
<script type="text/javascript" src="skins/{PHP.skin}/admin/scripts/jquery-1.3.2.min.js"></script>		
<!-- jQuery Configuration -->
<script type="text/javascript" src="skins/{PHP.skin}/admin/scripts/simpla.jquery.configuration.js"></script>
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
