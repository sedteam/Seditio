<!-- BEGIN: HEADER -->{HEADER_DOCTYPE}
<html>

<head>
	<title>{HEADER_TITLE}</title>
	{HEADER_METAS}
	{HEADER_COMPOPUP}
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="canonical" href="{HEADER_CANONICAL_URL}" />
	<link href="system/adminskin/{PHP.cfg.adminskin}/css/fonts.css" type="text/css" rel="stylesheet" />
	<link href="system/adminskin/{PHP.cfg.adminskin}/css/framework.css" type="text/css" rel="stylesheet" />
	<link href="system/adminskin/{PHP.cfg.adminskin}/css/main.css" type="text/css" rel="stylesheet" />
	{HEADER_UPLOADER}
</head>

<body>

	<div id="body-wrapper">

		<div id="sidebar">

			<div id="sidebar-wrapper">
				<!-- Sidebar with logo and menu -->

				<div id="logo"><a href="{ADMIN_URL}"><img src="system/adminskin/{PHP.cfg.adminskin}/img/logo.png" /></a></div>

				<!-- BEGIN: HEADER_ADMIN_USER -->
				<div id="profile-links" style="margin-top:30px;">
					{PHP.L.Hi}, <span class="user-login">{HEADER_USER_NAME}</span><br /><a href="/" title="{PHP.L.Gotosite}">{PHP.L.Gotosite}</a> | {HEADER_USER_LOGINOUT}
				</div>
				<!-- END: HEADER_ADMIN_USER -->

				<!-- BEGIN: ADMIN_MENU -->

				<ul id="main-nav">
					<!-- Accordion Menu -->

					<li>
						<a href="{ADMIN_MENU_URL}" class="nav-top-item no-submenu {ADMIN_MENU_URL_CLASS}">
							<span class="nav-icon"><i class="ic-home"></i></span>
							<span class="nav-title">{PHP.L.Home}</span>
						</a>
					</li>

					<!-- BEGIN: CONFIG_MENU -->
					<li>
						<a href="{ADMIN_MENU_CONFIG_URL}" class="nav-top-item yes-submenu {ADMIN_MENU_CONFIG_URL_CLASS}">
							<span class="nav-icon"><i class="ic-settings"></i></span>
							<span class="nav-title">{PHP.L.Configuration}</span>
						</a>
						{ADMIN_MENU_CONFIG}
					</li>
					<!-- END: CONFIG_MENU -->

					<!-- BEGIN: PAGE_MENU -->
					<li>
						<a href="{ADMIN_MENU_PAGE_URL}" class="nav-top-item yes-submenu {ADMIN_MENU_PAGE_URL_CLASS}">
							<span class="nav-icon"><i class="ic-pages"></i></span>
							<span class="nav-title">{PHP.L.Pages}</span>
						</a>
						{ADMIN_MENU_PAGE}
					</li>
					<!-- END: PAGE_MENU -->

					<!-- BEGIN: FORUMS_MENU -->
					<li>
						<a href="{ADMIN_MENU_FORUMS_URL}" class="nav-top-item yes-submenu {ADMIN_MENU_FORUMS_URL_CLASS}">
							<span class="nav-icon"><i class="ic-forums"></i></span>
							<span class="nav-title">{PHP.L.Forums}</span>
						</a>
						{ADMIN_MENU_FORUMS}
					</li>
					<!-- END: FORUMS_MENU -->

					<!-- BEGIN: USERS_MENU -->
					<li>
						<a href="{ADMIN_MENU_USERS_URL}" class="nav-top-item no-submenu {ADMIN_MENU_USERS_URL_CLASS}">
							<span class="nav-icon"><i class="ic-users"></i></span>
							<span class="nav-title">{PHP.L.Users}</span>
						</a>
					</li>
					<!-- END: USERS_MENU -->

					<!-- BEGIN: TOOLS_MENU -->
					<li>
						<a href="{ADMIN_MENU_TOOLS_URL}" class="nav-top-item no-submenu {ADMIN_MENU_TOOLS_URL_CLASS}">
							<span class="nav-icon"><i class="ic-tool"></i></span>
							<span class="nav-title">{PHP.L.adm_manage}</span>
						</a>
					</li>
					<!-- END: TOOLS_MENU -->

					<!-- BEGIN: PLUGINS_MENU -->
					<li>
						<a href="{ADMIN_MENU_PLUGINS_URL}" class="nav-top-item no-submenu {ADMIN_MENU_PLUGINS_URL_CLASS}">
							<span class="nav-icon"><i class="ic-wand"></i></span>
							<span class="nav-title">{PHP.L.Plugins}</span>
						</a>
					</li>
					<!-- END: PLUGINS_MENU -->

					<!-- BEGIN: TRASHCAN_MENU -->
					<li>
						<a href="{ADMIN_MENU_TRASHCAN_URL}" class="nav-top-item no-submenu {ADMIN_MENU_TRASHCAN_URL_CLASS}">
							<span class="nav-icon"><i class="ic-trash"></i></span>
							<span class="nav-title">{PHP.L.Trashcan}</span>
						</a>
					</li>
					<!-- END: TRASHCAN_MENU -->

					<!-- BEGIN: LOG_MENU -->
					<li>
						<a href="{ADMIN_MENU_LOG_URL}" class="nav-top-item no-submenu {ADMIN_MENU_LOG_URL_CLASS}">
							<span class="nav-icon"><i class="ic-clock"></i></span>
							<span class="nav-title">{PHP.L.adm_log}</span>
						</a>
					</li>
					<!-- END: LOG_MENU -->

				</ul><!-- End #main-nav -->

				<!-- END: ADMIN_MENU -->

			</div>

		</div>

		<div id="main-content">
			<!-- Main Content Section with everything -->

			<div class="topbar-menu">

				<div class="topbar-menu-left">
					<a href="/" title="{PHP.L.Gotosite}"><i class="ic-external-link"></i> {PHP.L.Gotosite}</a>
				</div>

				<div class="topbar-menu-right">

					<!-- BEGIN: HEADER_USER_MENU -->

					<ul>
						<li>{HEADER_USERLIST}</li>
						<li>{HEADER_USER_PROFILE}</li>
						<li>{HEADER_USER_PFS}</li>
						<li>{HEADER_USER_PMREMINDER}</li>
						<li>{HEADER_USER_LOGINOUT}</li>
					</ul>

					<!-- END: HEADER_USER_MENU -->

				</div>

			</div>

<!-- END: HEADER -->