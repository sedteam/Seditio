<!-- BEGIN: ADMIN_PLUG -->

<div class="title">
	<span><i class="ic-plug"></i></span>
	<h2>{ADMIN_PLUG_TITLE}</h2>
</div>


<!-- BEGIN: PLUG_DETAILS -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PLUG_DETAILS_NAME}</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-body resp-table-body">

				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title" style="width:33%;">
						{PHP.L.Code} :
					</div>
					<div class="table-td text-left resp-table-td">
						{PLUG_DETAILS_CODE}
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">
						{PHP.L.Description}:
					</div>
					<div class="table-td text-left resp-table-td">
						{PLUG_DETAILS_DESC}
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">
						{PHP.L.Version}:
					</div>
					<div class="table-td text-left resp-table-td">
						{PLUG_DETAILS_VERSION}
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">
						{PHP.L.Date}:
					</div>
					<div class="table-td text-left resp-table-td">
						{PLUG_DETAILS_DATE}
					</div>
				</div>
				<!-- BEGIN: PLUG_DETAILS_CONFIG -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">
						{PHP.L.Configuration}:
					</div>
					<div class="table-td text-left resp-table-td">
						<a href="{PLUG_DETAILS_CONFIG_URL}"><i class="ic-settings"></i></a>
					</div>
				</div>
				<!-- END: PLUG_DETAILS_CONFIG -->
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">
						{PHP.L.Rights}:
					</div>
					<div class="table-td text-left resp-table-td">
						<a href="{PLUG_DETAILS_RIGHTS_URL}"><i class="ic-lock"></i></a>
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">
						{PHP.L.adm_defauth_guests}:
					</div>
					<div class="table-td text-left resp-table-td">
						{PLUG_DETAILS_DEFAUTH_GUESTS}
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">
						{PHP.L.adm_deflock_guests}:
					</div>
					<div class="table-td text-left resp-table-td">
						{PLUG_DETAILS_DEFLOCK_GUESTS}
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">
						{PHP.L.adm_defauth_members}:
					</div>
					<div class="table-td text-left resp-table-td">
						{PLUG_DETAILS_DEFAUTH_MEMBERS}
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">
						{PHP.L.adm_deflock_members}:
					</div>
					<div class="table-td text-left resp-table-td">
						{PLUG_DETAILS_DEFLOCK_MEMBERS}
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">
						{PHP.L.Author}:
					</div>
					<div class="table-td text-left resp-table-td">
						{PLUG_DETAILS_AUTHOR}
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">
						{PHP.L.Copyright}:
					</div>
					<div class="table-td text-left resp-table-td">
						{PLUG_DETAILS_COPYRIGHT}
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td pl-title">
						{PHP.L.Notes}:
					</div>
					<div class="table-td text-left resp-table-td">
						{PLUG_DETAILS_NOTES}
					</div>
				</div>

			</div>

		</div>

	</div>

</div>

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Options}</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-body resp-table-body">

				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td" style="width:33%;">
						<a href="{PLUG_DETAILS_INSTALL_URL}" title="{PHP.L.adm_opt_installall}"><img src="system/img/admin/play.png" alt="" /> {PHP.L.adm_opt_installall}</a>
					</div>
					<div class="table-td text-left resp-table-td">
						{PHP.L.adm_opt_installall_explain}
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td">
						<a href="{PLUG_DETAILS_UNINSTALL_URL}" title="{PHP.L.adm_opt_uninstallall}"><img src="system/img/admin/stop.png" alt="" /> {PHP.L.adm_opt_uninstallall}</a>
					</div>
					<div class="table-td text-left resp-table-td">
						{PHP.L.adm_opt_uninstallall_explain}
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td">
						<a href="{PLUG_DETAILS_PAUSE_URL}" title="{PHP.L.adm_opt_pauseall}"><img src="system/img/admin/pause.png" alt="" /> {PHP.L.adm_opt_pauseall}</a>
					</div>
					<div class="table-td text-left resp-table-td">
						{PHP.L.adm_opt_pauseall_explain}
					</div>
				</div>
				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td">
						<a href="{PLUG_DETAILS_UNPAUSE_URL}" title="{PHP.L.adm_opt_unpauseall}"><img src="system/img/admin/forward.png" alt="" /> {PHP.L.adm_opt_unpauseall}</a>
					</div>
					<div class="table-td text-left resp-table-td">
						{PHP.L.adm_opt_unpauseall_explain}
					</div>
				</div>

			</div>

		</div>

	</div>

</div>

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
					<div class="table-th coltop text-left">{PHP.L.Order}</div>
					<div class="table-th coltop text-left">{PHP.L.Status}</div>
					<div class="table-th coltop text-left">{PHP.L.Action}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: PLUG_PARTS_LIST -->

				<!-- BEGIN: PLUG_PARTS_ERROR -->

				<div class="table-row resp-table-row">

					<div class="table-td text-left resp-table-td plug-part" data-label="#">{PARTS_LIST_NUMBER}</div>
					<div class="table-td text-left resp-table-td plug-part" data-label="{PHP.L.Part}">-</div>
					<div class="table-td text-left resp-table-td plug-file" data-label="{PHP.L.File}">{PARTS_LIST_FILE}</div>
					<div class="table-td text-left resp-table-td plug-hooks" data-label="">-</div>
					<div class="table-td text-left resp-table-td plug-order" data-label="">-</div>
					<div class="table-td text-left resp-table-td plug-error" data-label="">{PARTS_LIST_ERROR}</div>
					<div class="table-td text-left resp-table-td plug-action" data-label=""></div>

				</div>

				<!-- END: PLUG_PARTS_ERROR -->

				<!-- BEGIN: PLUG_PARTS -->

				<div class="table-row resp-table-row">

					<div class="table-td text-left resp-table-td plug-part" data-label="#">#{PARTS_LIST_NUMBER}</div>
					<div class="table-td text-left resp-table-td plug-part" data-label="{PHP.L.Part}">{PARTS_LIST_PART}</div>
					<div class="table-td text-left resp-table-td plug-file" data-label="{PHP.L.File}">{PARTS_LIST_FILE}.php</div>
					<div class="table-td text-left resp-table-td plug-hooks" data-label="{PHP.L.Hooks}">{PARTS_LIST_HOOKS}</div>
					<div class="table-td text-left resp-table-td plug-order" data-label="{PHP.L.Order}">{PARTS_LIST_ORDER}</div>
					<div class="table-td text-left resp-table-td plug-status" data-label="{PHP.L.Status}">{PARTS_LIST_STATUS}</div>
					<div class="table-td text-left resp-table-td plug-action">{PARTS_LIST_ACTION}</div>

				</div>

				<!-- END: PLUG_PARTS -->

				<!-- END: PLUG_PARTS_LIST -->

			</div>

		</div>

	</div>

</div>

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Tags}</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left" style="width:20px">#</div>
					<div class="table-th coltop text-left" style="width:100px">{PHP.L.Part}</div>
					<div class="table-th coltop text-left">{PHP.L.Files} / {PHP.L.Tags}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: TAGS_LIST -->

				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td plug-part" data-label="#">#{TAGS_LIST_NUMBER}</div>
					<div class="table-td text-left resp-table-td plug-part" data-label="{PHP.L.Part}">{TAGS_LIST_PART}</div>
					<div class="table-td text-left resp-table-td plug-part" data-label="{PHP.L.Files} / {PHP.L.Tags}">{TAGS_LIST_BODY}</div>
				</div>

				<!-- END: TAGS_LIST -->

			</div>

		</div>

	</div>

</div>

<!-- END: PLUG_DETAILS -->

<!-- BEGIN: PLUG_LISTING -->

<div class="content-box">
	<div class="content-box-header">
		<h3>{PHP.L.Plugins} ({PLUG_LISTING_COUNT})</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left">{PHP.L.Plugins} {PHP.L.adm_clicktoedit}</div>
					<div class="table-th coltop text-left">{PHP.L.Code}</div>
					<div class="table-th coltop text-left">{PHP.L.Version}</div>
					<div class="table-th coltop text-left">{PHP.L.Status} ({PHP.L.Parts})</div>
					<div class="table-th coltop text-center" style="width:50px;">{PHP.L.Configuration}</div>
					<div class="table-th coltop text-center" style="width:50px;">{PHP.L.Rights}</div>
					<div class="table-th coltop text-center" style="width:50px;">{PHP.L.Open}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: PLUG_LIST -->

				<!-- BEGIN: PLUG_LIST_ERROR -->

				<div class="table-row resp-table-row">

					<div class="table-td text-left resp-table-td plug-name" data-label="{PHP.L.Code}">{PLUG_LIST_CODE}</div>
					<div class="table-td text-left resp-table-td plug-error" data-label="{PHP.L.Error}">{PLUG_LIST_ERROR}</div>
					<div class="table-td text-left resp-table-td plug-version" data-label=""></div>
					<div class="table-td text-left resp-table-td plug-status" data-label=""></div>
					<div class="table-td text-center resp-table-td plug-config" data-label=""></div>
					<div class="table-td text-center resp-table-td plug-right" data-label=""></div>
					<div class="table-td text-center resp-table-td plug-open"></div>

				</div>

				<!-- END: PLUG_LIST_ERROR -->

				<div class="table-row resp-table-row">

					<div class="table-td text-left resp-table-td plug-name" data-label="{PHP.L.Plugins} {PHP.L.adm_clicktoedit}">
						<a href="{PLUG_LIST_DETAILS_URL}"><span class="icon"><i class="ic-plug ic-{PLUG_LIST_CODE}"></i></span> {PLUG_LIST_NAME}</a>
					</div>
					<div class="table-td text-left resp-table-td plug-code" data-label="{PHP.L.Code}">{PLUG_LIST_CODE}</div>
					<div class="table-td text-left resp-table-td plug-version" data-label="{PHP.L.Version}">{PLUG_LIST_VERSION}</div>
					<div class="table-td text-left resp-table-td plug-status" data-label="{PHP.L.Status} ({PHP.L.Parts})">{PLUG_LIST_STATUS} {PLUG_LIST_PARTS_COUNT}</div>
					<div class="table-td text-center resp-table-td plug-config" data-label="{PHP.L.Configuration}">
						<!-- BEGIN: PLUG_LIST_CONFIG -->
						<a href="{PLUG_LIST_CONFIG_URL}"><i class="ic-settings"></i></a>
						<!-- END: PLUG_LIST_CONFIG -->
					</div>
					<div class="table-td text-center resp-table-td plug-right" data-label="{PHP.L.Rights}"><a href="{PLUG_LIST_RIGHTS_URL}"><i class="ic-lock"></i></a></div>
					<div class="table-td text-center resp-table-td plug-open" data-label="{PHP.L.Open}">
						<!-- BEGIN: PLUG_LIST_OPEN -->
						<a href="{PLUG_LIST_OPEN_URL}"><i class="ic-arrow-right"></i></a>
						<!-- END: PLUG_LIST_OPEN -->
					</div>

				</div>

				<!-- END: PLUG_LIST -->

			</div>

		</div>

	</div>

</div>


<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Hooks} ({HOOKS_COUNT})</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left">{PHP.L.Hooks}</div>
					<div class="table-th coltop text-left">{PHP.L.Plugin}</div>
					<div class="table-th coltop text-left">{PHP.L.File}</div>
					<div class="table-th coltop text-center" style="width:50px;">{PHP.L.Order}</div>
					<div class="table-th coltop text-center" style="width:50px;">{PHP.L.Active}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: HOOK_LIST -->

				<div class="table-row resp-table-row">

					<div class="table-td text-left resp-table-td plug-hook" data-label="{PHP.L.Hooks}">{HOOK_LIST_HOOK}</div>
					<div class="table-td text-left resp-table-td plug-name" data-label="{PHP.L.Plugin}">{HOOK_LIST_PLUG_TITLE} ({HOOK_LIST_PLUG_CODE})</div>
					<div class="table-td text-left resp-table-td plug-file" data-label="{PHP.L.File}">{HOOK_LIST_PLUG_FILE}</div>
					<div class="table-td text-center resp-table-td plug-order" data-label="{PHP.L.Order}">{HOOK_LIST_ORDER}</div>
					<div class="table-td text-center resp-table-td plug-active" data-label="{PHP.L.Active}">{HOOK_LIST_STATUS}</div>

				</div>

				<!-- END: HOOK_LIST -->

			</div>

		</div>

	</div>

</div>

<!-- END: PLUG_LISTING -->

<!-- BEGIN: PLUG_UN_INSTALL -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.Install} / {PHP.L.Uninstall}</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content">

		<p>{PLUG_UN_INSTALL_INFO}</p>
		<a href="{PLUG_UN_INSTALL_URL}" class="btn">Continue...</a>

	</div>

</div>

<!-- END: PLUG_UN_INSTALL -->


<!-- END: ADMIN_PLUG -->