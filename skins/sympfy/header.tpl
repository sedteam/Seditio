<!-- BEGIN: HEADER -->{HEADER_DOCTYPE}
<html>
	<head>
		<title>{HEADER_TITLE}</title>
		{HEADER_METAS}
		{HEADER_COMPOPUP}
		<link href="skins/{PHP.skin}/css/framework.css" type="text/css" rel="stylesheet" />
		<link href="skins/{PHP.skin}/css/fonts.css" type="text/css" rel="stylesheet" />
		<link href="skins/{PHP.skin}/css/plugins.css" type="text/css" rel="stylesheet" />
		<link href="skins/{PHP.skin}/css/cms.css" type="text/css" rel="stylesheet" />		
		<link href="skins/{PHP.skin}/css/sympfy.css" type="text/css" rel="stylesheet" />
		<link href="skins/{PHP.skin}/css/responsive.css" type="text/css" rel="stylesheet" />
		<link rel="canonical" href="{HEADER_CANONICAL_URL}" />
		{HEADER_UPLOADER}
	</head>
<body>

	<!-- BEGIN: USER -->
	<div class="admTools">
		<a href="javascript:void(0);" class="openTools"><i class="ic-settings"></i></a>
		<div id="user">
			<!-- BEGIN: HEADER_NOTICES -->
			<div class="notices">{HEADER_NOTICES}</div>
			<!-- END: HEADER_NOTICES -->			
			<ul> 
				<li><span>{HEADER_LOGSTATUS}</span></li>
				<li>{HEADER_USER_ADMINPANEL}</li>
				<li>{HEADER_USERLIST}</li>
				<li>{HEADER_USER_PROFILE}</li>
				<li>{HEADER_USER_PFS}</li>
				<li>{HEADER_USER_PMREMINDER}</li>
				<li>{HEADER_USER_PAGEADD}</li>
				<li>{HEADER_USER_LOGINOUT}</li>
			</ul>
		</div>
	</div>
	<!-- END: USER --> 

	<header id="header">

		<div class="container container-header">

			<div class="header-wrapper">

				<div class="logo-col">
					 <a href="/" alt="{HEADER_TITLE}"><img class="logo" src="skins/{PHP.skin}/img/seditio.svg" alt="{HEADER_TITLE}"></a>
				</div>

				<div class="menu-col">
					<div class="menu-wrapper">
						<ul id="menu">
							<li class="menu-item">
							  <a href="/">Home</a>
							</li>
							<li class="menu-item menu-item-has-children">
								<a href="/articles/">Articles</a>
								<ul class="sub-menu">					
									<li class="menu-item"><a href="/articles/sample1/">Sample subcategory 1</a></li>				              	
									<li class="menu-item"><a href="/articles/sample2/">Sample subcategory 2</a></li>								
								</ul>
							</li>								
							<li class="menu-item">
							  <a href="/gallery/">Galleries</a>
							</li>
							<li class="menu-item">
							  <a href="/forums/">Forums</a>
							</li>	
							<li class="menu-item">
							  <a href="/plug/contact">Contacts</a>
							</li>								
						</ul>
					</div>
				</div>
				
				<div class="social-col">		
						
					<ul class="socialmedia">
					  <li class="socialmedia-li">
						  <a title="Facebook" href="" class="socialmedia-a">
							  <span class="ic-facebook"></span>
						  </a>
					  </li>
					  <li class="socialmedia-li">
						  <a title="Vkontakte" href="" class="socialmedia-a">
							  <span class="ic-vk"></span>
						  </a>
					  </li>
					  <li class="socialmedia-li">
						  <a title="Instagram" href="" class="socialmedia-a">
							  <span class="ic-instagram"></span>
						  </a>
					  </li>
					</ul>						
						
				</div>
				
				<div class="trigger-col">
					<a href="#0" class="nav-trigger"><span></span></a>
				</div>

			</div>

			<div class="mobile-menu">
				<div class="js-box">
					<div class="js-menu"></div>
					<div class="js-social"></div>
				</div>
			</div>	
			
		</div>	
	
	</header>
<!-- END: HEADER -->