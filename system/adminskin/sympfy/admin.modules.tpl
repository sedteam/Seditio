<!-- BEGIN: ADMIN_MODULES -->

<div class="title">
	<span><i class="ic-forums"></i></span>
	<h2>{ADMIN_MODULES_TITLE}</h2>
</div>

<!-- BEGIN: MODULE_DETAILS -->

<div class="content-box">
	<div class="content-box-header">
		<h3>{MODULE_DETAILS_NAME}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content content-table">
		<div class="table cells striped resp-table">
			<div class="table-body resp-table-body">
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title" style="width:33%;">{PHP.L.Code} :</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_CODE}</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.Description}:</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_DESC}</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.Version}:</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_VERSION}</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.Date}:</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_DATE}</div>
				</div>
				<!-- BEGIN: MODULE_DETAILS_CONFIG -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.Configuration}:</div>
					<div class="table-td text-left resp-table-td"><a href="{MODULE_DETAILS_CONFIG_URL}"><i class="ic-settings"></i></a></div>
				</div>
				<!-- END: MODULE_DETAILS_CONFIG -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.Rights}:</div>
					<div class="table-td text-left resp-table-td"><a href="{MODULE_DETAILS_RIGHTS_URL}"><i class="ic-lock"></i></a></div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.adm_defauth_guests}:</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_DEFAUTH_GUESTS}</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.adm_deflock_guests}:</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_DEFLOCK_GUESTS}</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.adm_defauth_members}:</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_DEFAUTH_MEMBERS}</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.adm_deflock_members}:</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_DEFLOCK_MEMBERS}</div>
				</div>
				<!-- BEGIN: MODULE_DETAILS_LOCKED -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.adm_locked}:</div>
					<div class="table-td text-left resp-table-td">{PHP.L.adm_module_locked}</div>
				</div>
				<!-- END: MODULE_DETAILS_LOCKED -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.Author}:</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_AUTHOR}</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.Copyright}:</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_COPYRIGHT}</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.Notes}:</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_NOTES}</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.Requires}:</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_REQUIRES}</div>
				</div>
				<!-- BEGIN: MODULE_DETAILS_DEPENDENT_PLUGINS -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">{PHP.L.adm_dependent_plugins}:</div>
					<div class="table-td text-left resp-table-td">{MODULE_DETAILS_DEPENDENT_PLUGINS}</div>
				</div>
				<!-- END: MODULE_DETAILS_DEPENDENT_PLUGINS -->
			</div>
		</div>
	</div>
</div>

<!-- BEGIN: MODULE_DETAILS_HELP -->
<div class="content-box">
	<div class="content-box-header">
		<h3>{PHP.L.Help}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
		<div class="block">{MODULE_DETAILS_HELP}</div>
	</div>
</div>
<!-- END: MODULE_DETAILS_HELP -->

<!-- BEGIN: MODULE_DETAILS_OPT_BOX -->
<div class="content-box">
	<div class="content-box-header">
		<h3>{PHP.L.Options}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content content-table">
		<div class="table cells striped resp-table">
			<div class="table-body resp-table-body">
				<!-- BEGIN: MODULE_DETAILS_OPT_INSTALL -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td" style="width:33%;">
						<a href="{MODULE_DETAILS_INSTALL_URL}" title="{PHP.L.adm_opt_installall}"><img src="system/img/admin/play.png" alt="" /> {PHP.L.adm_opt_installall}</a>
					</div>
					<div class="table-td text-left resp-table-td">{PHP.L.adm_opt_installall_explain}</div>
				</div>
				<!-- END: MODULE_DETAILS_OPT_INSTALL -->
				<!-- BEGIN: MODULE_DETAILS_OPT_UNINSTALL -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td">
						<a href="{MODULE_DETAILS_UNINSTALL_URL}" title="{PHP.L.adm_opt_uninstallall}"><img src="system/img/admin/stop.png" alt="" /> {PHP.L.adm_opt_uninstallall}</a>
					</div>
					<div class="table-td text-left resp-table-td">{PHP.L.adm_opt_uninstallall_explain}</div>
				</div>
				<!-- END: MODULE_DETAILS_OPT_UNINSTALL -->
				<!-- BEGIN: MODULE_DETAILS_OPT_PAUSE -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td">
						<a href="{MODULE_DETAILS_PAUSE_URL}" title="{PHP.L.adm_opt_pauseall}"><img src="system/img/admin/pause.png" alt="" /> {PHP.L.adm_opt_pauseall}</a>
					</div>
					<div class="table-td text-left resp-table-td">{PHP.L.adm_opt_pauseall_explain}</div>
				</div>
				<!-- END: MODULE_DETAILS_OPT_PAUSE -->
				<!-- BEGIN: MODULE_DETAILS_OPT_UNPAUSE -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td">
						<a href="{MODULE_DETAILS_UNPAUSE_URL}" title="{PHP.L.adm_opt_unpauseall}"><img src="system/img/admin/forward.png" alt="" /> {PHP.L.adm_opt_unpauseall}</a>
					</div>
					<div class="table-td text-left resp-table-td">{PHP.L.adm_opt_unpauseall_explain}</div>
				</div>
				<!-- END: MODULE_DETAILS_OPT_UNPAUSE -->
			</div>
		</div>
	</div>
</div>
<!-- END: MODULE_DETAILS_OPT_BOX -->

<div class="content-box">
	<div class="content-box-header">
		<h3>{PHP.L.Parts}</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content content-table">
		<div class="table cells striped resp-table">
			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left" style="width:20px">#</div>
					<div class="table-th coltop text-left" style="width:100px">{PHP.L.Part}</div>
					<div class="table-th coltop text-left">{PHP.L.File}</div>
					<div class="table-th coltop text-left">{PHP.L.Hooks}</div>
					<div class="table-th coltop text-left">{PHP.L.Status}</div>
					<div class="table-th coltop text-left">{PHP.L.Action}</div>
				</div>
			</div>
			<div class="table-body resp-table-body">
				<!-- BEGIN: MODULE_PARTS_ROW -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td plug-part" data-label="#">#{MODULE_PARTS_NUMBER}</div>
					<div class="table-td text-left resp-table-td plug-part" data-label="{PHP.L.Part}">{MODULE_PARTS_PART}</div>
					<div class="table-td text-left resp-table-td plug-file" data-label="{PHP.L.File}">{MODULE_PARTS_FILE}</div>
					<div class="table-td text-left resp-table-td plug-hooks" data-label="{PHP.L.Hooks}">{MODULE_PARTS_HOOKS}</div>
					<div class="table-td text-left resp-table-td plug-status" data-label="{PHP.L.Status}">{MODULE_PARTS_STATUS}</div>
					<div class="table-td text-left resp-table-td plug-action">{MODULE_PARTS_ACTION}</div>
				</div>
				<!-- END: MODULE_PARTS_ROW -->
			</div>
		</div>
	</div>
</div>

<!-- END: MODULE_DETAILS -->

<!-- BEGIN: MODULES_LISTING -->

<div class="content-box">
	<div class="content-box-header">
		<h3>{PHP.L.adm_modules} ({MODULES_LISTING_COUNT})</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content content-table">
		<div class="table cells striped resp-table">
			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left">{PHP.L.adm_modules} {PHP.L.adm_clicktoedit}</div>
					<div class="table-th coltop text-left">{PHP.L.Code}</div>
					<div class="table-th coltop text-left">{PHP.L.Version}</div>
					<div class="table-th coltop text-left">{PHP.L.Status}</div>
					<div class="table-th coltop text-center" style="width:50px;">{PHP.L.Configuration}</div>
					<div class="table-th coltop text-center" style="width:50px;">{PHP.L.Rights}</div>
					<div class="table-th coltop text-center" style="width:50px;">{PHP.L.Open}</div>
				</div>
			</div>
			<div class="table-body resp-table-body">
				<!-- BEGIN: MODULE_LIST -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td plug-name" data-label="{PHP.L.adm_modules} {PHP.L.adm_clicktoedit}">
						<a href="{MODULE_LIST_DETAILS_URL}"><span class="icon"><i class="ic-wand ic-{MODULE_LIST_CODE}"></i></span> {MODULE_LIST_NAME}</a>
					</div>
					<div class="table-td text-left resp-table-td plug-code" data-label="{PHP.L.Code}">{MODULE_LIST_CODE}</div>
					<div class="table-td text-left resp-table-td plug-version" data-label="{PHP.L.Version}">{MODULE_LIST_VERSION}</div>
					<div class="table-td text-left resp-table-td plug-status" data-label="{PHP.L.Status}">{MODULE_LIST_STATUS}</div>
					<div class="table-td text-center resp-table-td plug-config" data-label="{PHP.L.Configuration}">
						<!-- BEGIN: MODULE_LIST_CONFIG -->
						<a href="{MODULE_LIST_CONFIG_URL}"><i class="ic-settings"></i></a>
						<!-- END: MODULE_LIST_CONFIG -->
					</div>
					<div class="table-td text-center resp-table-td plug-right" data-label="{PHP.L.Rights}"><a href="{MODULE_LIST_RIGHTS_URL}"><i class="ic-lock"></i></a></div>
					<div class="table-td text-center resp-table-td plug-open" data-label="{PHP.L.Open}">
						<!-- BEGIN: MODULE_LIST_OPEN -->
						<a href="{MODULE_LIST_OPEN_URL}"><i class="ic-arrow-right"></i></a>
						<!-- END: MODULE_LIST_OPEN -->
					</div>
				</div>
				<!-- END: MODULE_LIST -->
			</div>
		</div>
	</div>
</div>

<!-- END: MODULES_LISTING -->

<!-- BEGIN: MODULE_ACTION -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Install} / {PHP.L.Uninstall}</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content">

		<p>{MODULE_ACTION_INFO}</p>
		<a href="{MODULE_ACTION_URL}" class="btn">Continue...</a>

	</div>

</div>

<!-- END: MODULE_ACTION -->

<!-- END: ADMIN_MODULES -->
