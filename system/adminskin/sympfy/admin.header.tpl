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
	<link href="system/adminskin/{PHP.cfg.adminskin}/css/responsive.css" type="text/css" rel="stylesheet" />
	{HEADER_CSS}
</head>

<body>

	<div id="body-wrapper">

		<div id="primary-container">

			<aside id="sidebar">

				<div id="sidebar-wrapper">

					<div id="logo"><a href="{HEADER_ADMIN_URL}">{HEADER_USER_AVATAR}</a></div>

					<!-- BEGIN: HEADER_ADMIN_USER -->
					<div id="profile-links">
						{PHP.L.Hi}, <span class="user-login">{HEADER_USER_NAME}</span>
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

						<!-- BEGIN: MANAGE_MENU -->
						<li>
							<a href="{ADMIN_MENU_MANAGE_URL}" class="nav-top-item no-submenu {ADMIN_MENU_MANAGE_URL_CLASS}">
								<span class="nav-icon"><i class="ic-tool"></i></span>
								<span class="nav-title">{PHP.L.adm_manage}</span>
							</a>
						</li>
						<!-- END: MANAGE_MENU -->

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

			</aside>

			<div id="primary">

				<div class="topbar-menu">

					<div class="topbar-trigger">
						<button class="nav-trigger"><span></span></button>
					</div>

					<div class="topbar-menu-left">
						<a href="/" title="{PHP.L.Gotosite}"><i class="ic-external-link"></i> {PHP.L.Gotosite}</a>
					</div>

					<div class="topbar-menu-right">

						<div class="dropdown-menu">

							<button class="dropdown-btn"><i class="ic-directions"></i> {PHP.L.Mainmenu}</button>

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

				</div>

<!-- END: HEADER -->