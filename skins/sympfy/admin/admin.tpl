<!-- BEGIN: MAIN -->

		<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
				  
		<!-- Logo (221px wide) -->
			<div id="logo"><a href="{ADMIN_URL}"><img src="skins/{PHP.skin}/admin/img/logo.png" /></a></div>
		  
			<!-- BEGIN: ADMIN_USER -->
			<div id="profile-links" style="margin-top:30px;">
				{PHP.L.Hi}, <span class="user-login">{ADMIN_USER_NAME}</span><br /><a href="/" title="{PHP.L.Gotosite}">{PHP.L.Gotosite}</a> | {ADMIN_USER_LOGINOUT}
			</div>        
			<!-- END: ADMIN_USER -->        
			
			{ADMIN_MENU}
			
		</div></div> <!-- End #sidebar -->
		
		<div id="main-content"> <!-- Main Content Section with everything -->
    
			<div>{ADMIN_BREADCRUMBS} </div>
			<div class="clear"></div>

			<div id="subtitle">
				 {ADMIN_SUBTITLE}
			</div>
      
			<!-- BEGIN: ADMIN_MESSAGE -->

			<div class="notification {ADMIN_MSG_CLASS} png_bg">
			<a href="" class="close" title="{PHP.L.Close}"></a>
			<div><strong>{ADMIN_MSG_TITLE}</strong><br />
				 {ADMIN_MSG_TEXT}
			</div>
			</div>

			<!-- END: ADMIN_MESSAGE -->
			
			{ADMIN_MAIN}
					
			<div class="clear"></div>


<!-- END: MAIN -->
