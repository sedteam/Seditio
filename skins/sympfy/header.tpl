<!-- BEGIN: HEADER -->{HEADER_DOCTYPE}
<html>

<head>
	<title>{HEADER_TITLE}</title>
	{HEADER_METAS}
	{HEADER_COMPOPUP}
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
	<div class="userpanel">
		<a href="javascript:void(0);" class="openuserpanel"><i class="ic-settings"></i></a>
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
					<a href="{PHP.sys.dir_uri}" alt="{HEADER_TITLE}"><img class="logo" src="skins/{PHP.skin}/img/seditio.svg" alt="{HEADER_TITLE}"></a>
				</div>

				<div class="menu-col">
					<div class="menu-wrapper">
						<div class="menu">
							{PHP.sed_menu.1.childrens}
						</div>
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