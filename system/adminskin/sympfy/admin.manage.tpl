<!-- BEGIN: ADMIN_MANAGE -->

<div class="title">
	<span><i class="ic-forums"></i></span>
	<h2>{ADMIN_MANAGE_TITLE}</h2>
</div>

<div class="row row-flex">

	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

		<div class="content-box">

			<div class="content-box-header">
				<h3>{PHP.L.Modules}</h3>
			</div>

			<div class="content-box-content content-table">

				<div class="table cells striped">

					<div class="table-head">
						<div class="table-row modules-row">
							<div class="table-th coltop text-left">{PHP.L.adm_module_name}</div>
							<div class="table-th coltop text-center" style="width:80px;">{PHP.L.Rights}</div>
							<div class="table-th coltop text-center" style="width:128px;">{PHP.L.Configuration}</div>
						</div>
					</div>

					<div class="table-body">

						<!-- BEGIN: MODULES_LIST -->
						<div class="table-row">
							<div class="table-td text-left">
								<a href="{MODULES_LIST_URL}"><span class="icon"><i class="ic-{MODULES_LIST_CODE}"></i></span> {MODULES_LIST_TITLE}</a>
							</div>

							<div class="table-td text-center">
								<!-- BEGIN: MODULES_LIST_RIGHTS -->
								<a href="{MODULES_LIST_RIGHTS_URL}"><i class="ic-lock"></i></a>
								<!-- END: MODULES_LIST_RIGHTS -->
							</div>

							<div class="table-td text-center">
								<!-- BEGIN: MODULES_LIST_CONFIG -->
								<a href="{MODULES_LIST_CONFIG_URL}"><i class="ic-settings"></i></a>
								<!-- END: MODULES_LIST_CONFIG -->
							</div>
						</div>
						<!-- END: MODULES_LIST -->

						<!-- BEGIN: MODULES_LIST_BANLIST -->
						<div class="table-row">
							<div class="table-td text-left"><a href="{MODULES_LIST_BANLIST_URL}"><span class="icon"><i class="ic-banlist"></i></span> {PHP.L.Banlist}</a></div>
							<div class="table-td text-center"></div>
							<div class="table-td text-center"></div>
						</div>
						<!-- END: MODULES_LIST_BANLIST -->

						<!-- BEGIN: MODULES_LIST_CACHE -->
						<div class="table-row">
							<div class="table-td text-left"><a href="{MODULES_LIST_CACHE_URL}"><span class="icon"><i class="ic-cache"></i></span> {PHP.L.adm_internalcache}</a></div>
							<div class="table-td text-center"></div>
							<div class="table-td text-center"></div>
						</div>
						<!-- END: MODULES_LIST_CACHE -->

						<!-- BEGIN: MODULES_LIST_SMILIES -->
						<div class="table-row">
							<div class="table-td text-left"><a href="{MODULES_LIST_SMILIES_URL}"><span class="icon"><i class="ic-smilies"></i></span> {PHP.L.Smilies}</a></div>
							<div class="table-td text-center"></div>
							<div class="table-td text-center"></div>
						</div>
						<!-- END: MODULES_LIST_SMILIES -->

						<!-- BEGIN: MODULES_LIST_HITS -->
						<div class="table-row">
							<div class="table-td text-left"><a href="{MODULES_LIST_HITS_URL}"><span class="icon"><i class="ic-hits"></i></span> {PHP.L.Hits}</a></div>
							<div class="table-td text-center"></div>
							<div class="table-td text-center"></div>
						</div>
						<!-- END: MODULES_LIST_HITS -->

						<!-- BEGIN: MODULES_LIST_REFERERS -->
						<div class="table-row">
							<div class="table-td text-left"><a href="{MODULES_LIST_REFERERS_URL}"><span class="icon"><i class="ic-referers"></i></span> {PHP.L.Referers}</a></div>
							<div class="table-td text-center"></div>
							<div class="table-td text-center"></div>
						</div>
						<!-- END: MODULES_LIST_REFERERS -->

					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

		<div class="content-box">

			<div class="content-box-header">
				<h3>{PHP.L.Tools}</h3>
				<div class="clear"></div>
			</div>

			<div class="content-box-content content-table">

				<div class="table cells striped">

					<div class="table-head">
						<div class="table-row modules-row">
							<div class="table-th coltop text-left">{PHP.L.adm_tool_name}</div>
							<div class="table-th coltop text-center" style="width:128px;">{PHP.L.Configuration}</div>
						</div>
					</div>

					<div class="table-body">

						<!-- BEGIN: TOOLS_LIST -->
						<div class="table-row">

							<div class="table-td text-left">
								<a href="{TOOLS_LIST_URL}"><span class="icon"><i class="ic-plug"></i></span> {TOOLS_LIST_TITLE}</a>
							</div>

							<div class="table-td text-center">
								<!-- BEGIN: TOOLS_LIST_CONFIG -->
								<a href="{TOOLS_LIST_CONFIG_URL}"><i class="ic-settings"></i></a>
								<!-- END: TOOLS_LIST_CONFIG -->
							</div>

						</div>
						<!-- END: TOOLS_LIST -->

					</div>

				</div>

			</div>

		</div>

	</div>

</div>

<!-- END: ADMIN_MANAGE -->

<!-- BEGIN: ADMIN_TOOL -->

<div class="title">
	<span><i class="ic-forums"></i></span>
	<h2>{TOOL_TITLE}</h2>
</div>

<div class="content-box">

	<div class="content-box-header">
		<h3><i class="ic-codesandbox"></i> {TOOL_TITLE}</h3>
	</div>

	<div class="content-box-content">
		<!-- BEGIN: TOOL_BODY_LIST -->
		{TOOL_BODY}
		<!-- END: TOOL_BODY_LIST -->
	</div>

</div>

<!-- END: ADMIN_TOOL -->