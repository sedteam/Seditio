<!-- BEGIN: ADMIN_TRANSLATIONS -->

<div class="title">
	<span><i class="ic-flag"></i></span>
	<h2>{ADMIN_TRANSLATIONS_TITLE}</h2>
</div>

<!-- Navigation Shortcut Buttons -->
<ul class="shortcut-buttons-set">
	<li><a class="shortcut-button {SUBNAV_LIST_SELECTED}" href="{SUBNAV_LIST_URL}"><span>
		<i class="ic-notebook ic-3x"></i><br />
		{PHP.L.adm_translations}
	</span></a></li>
	<li><a class="shortcut-button {SUBNAV_ADD_SELECTED}" href="{SUBNAV_ADD_URL}"><span>
		<i class="ic-plus ic-3x"></i><br />
		{PHP.L.adm_translations_add}
	</span></a></li>
	<li><a class="shortcut-button {SUBNAV_LANGUAGES_SELECTED}" href="{SUBNAV_LANGUAGES_URL}"><span>
		<i class="ic-flag ic-3x"></i><br />
		{PHP.L.adm_translations_languages}
	</span></a></li>
	<li><a class="shortcut-button {SUBNAV_IO_SELECTED}" href="{SUBNAV_IO_URL}"><span>
		<i class="ic-repeat ic-3x"></i><br />
		{PHP.L.adm_translations_io}
	</span></a></li>
	<li><a class="shortcut-button {SUBNAV_TOOLS_SELECTED}" href="{SUBNAV_TOOLS_URL}"><span>
		<i class="ic-settings ic-3x"></i><br />
		{PHP.L.adm_translations_tools}
	</span></a></li>
</ul>
<div class="clear"></div>

<!-- Language Switcher Bar -->
<div class="well btn-lang-group">
	<!-- BEGIN: LANG_TABS -->
	<a href="{LANG_TAB_URL}" class="btn btn-lang-item {LANG_TAB_SELECTED}">
		{LANG_TAB_CHECK}{LANG_TAB_FLAG} {LANG_TAB_TITLE} ({LANG_TAB_NATIVE})
	</a>
	<!-- END: LANG_TABS -->
</div>

<!-- BEGIN: LIST -->
<div class="content-box">

	<div class="content-box-header">
		<h3>{TRANSLATIONS_LANG_HEADER} ({PHP.L.Total}: {TRANSLATIONS_TOTALITEMS})</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content content-table">

		<!-- Filter and Search Bar -->
		<div class="table-filters">
			<form action="{SEARCH_ACTION_URL}" method="get" class="form-inline">
				<input type="hidden" name="m" value="translations" />
				<input type="hidden" name="s" value="list" />
				<input type="hidden" name="tlang" value="{TRANSLATIONS_CURRENT_LANG}" />

				<div>
					{TRANSLATIONS_SCOPE_SELECT}
				</div>

				<div>
					{TRANSLATIONS_TYPE_SELECT}
				</div>

				<div>
					{TRANSLATIONS_SEARCH_INPUT}
				</div>

				<div>
					<button type="submit" class="btn btn-primary"><i class="ic-search"></i> {PHP.L.Search}</button>
					<!-- IF {TRANSLATIONS_SEARCH_QUERY} != '' -->
					<a href="{SEARCH_RESET_URL}" class="btn" title="{PHP.L.Reset}"><i class="ic-close"></i></a>
					<!-- ENDIF -->
				</div>

				<div class="table-filters-perpage">
					<span>{PHP.L.adm_translations_show}:</span>
					{TRANSLATIONS_PERPAGE_TOP}
				</div>
			</form>
		</div>

		<form action="{TRANSLATIONS_SUBMIT_URL}" method="post">

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left w-tra-key">{PHP.L.adm_translations_key}</div>
						<div class="table-th coltop text-left">{PHP.L.adm_translations_val}</div>
						<div class="table-th coltop text-center w-tra-scope">{PHP.L.adm_translations_scope}</div>
						<div class="table-th coltop text-center w-tra-type">{PHP.L.adm_translations_type}</div>
						<div class="table-th coltop text-center w-tra-action">{PHP.L.Action}</div>
					</div>
				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: TRA_ROW -->
					<div class="table-row resp-table-row">

						<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_translations_key}">
							<span class="translation-key">
								{TRA_ROW_KEY}
							</span>
						</div>

						<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_translations_val}">
							{TRA_ROW_VALUE_INPUT}
						</div>

						<div class="table-td text-center resp-table-td" data-label="{PHP.L.adm_translations_scope}">
							<span><strong>{TRA_ROW_SCOPE}</strong><!-- IF {TRA_ROW_CODE} != 'main' --><br /><small>{TRA_ROW_CODE}</small><!-- ENDIF --></span>
						</div>

						<div class="table-td text-center resp-table-td" data-label="{PHP.L.adm_translations_type}">
							<!-- BEGIN: TRA_TYPE_LOCKED -->
							<span class="badge"><i class="ic-lock"></i> {PHP.L.adm_translations_locked}</span>
							<!-- END: TRA_TYPE_LOCKED -->
							<!-- BEGIN: TRA_TYPE_SYSTEM -->
							<span class="badge"><i class="ic-cog"></i> {PHP.L.adm_translations_system}</span>
							<!-- END: TRA_TYPE_SYSTEM -->
							<!-- BEGIN: TRA_TYPE_CUSTOM -->
							<span class="badge"><i class="ic-user"></i> {PHP.L.adm_translations_custom}</span>
							<!-- END: TRA_TYPE_CUSTOM -->
						</div>

						<div class="table-td text-center resp-table-td" data-label="{PHP.L.Action}">
							<!-- BEGIN: TRA_ACTION_EDIT -->
							<a href="{TRA_ROW_EDIT_URL}" class="btn btn-small" title="{PHP.L.Edit}"><i class="ic-pencil"></i></a>
							<!-- END: TRA_ACTION_EDIT -->
							<!-- BEGIN: TRA_ACTION_RESET -->
							<a href="{TRA_ROW_RESET_URL}" class="btn btn-small" title="{PHP.L.adm_translations_reset}" onclick="return sedjs.confirmact('{PHP.L.adm_translations_reset_confirm}');"><i class="ic-refresh"></i></a>
							<!-- END: TRA_ACTION_RESET -->
							<!-- BEGIN: TRA_ACTION_DELETE -->
							<a href="{TRA_ROW_DELETE_URL}" class="btn btn-small" title="{PHP.L.Delete}" onclick="return sedjs.confirmact('{PHP.L.adm_translations_delete_confirm}');"><i class="ic-trash"></i></a>
							<!-- END: TRA_ACTION_DELETE -->
						</div>

					</div>
					<!-- END: TRA_ROW -->

				</div>

			</div>

			<div class="table-footer-bar">
				<div class="table-footer-controls">
					<ul class="pagination">
						<!-- IF {TRANSLATIONS_PAGEPREV} --><li class="page-item">{TRANSLATIONS_PAGEPREV}</li><!-- ENDIF -->
						{TRANSLATIONS_PAGINATION}
						<!-- IF {TRANSLATIONS_PAGENEXT} --><li class="page-item">{TRANSLATIONS_PAGENEXT}</li><!-- ENDIF -->
					</ul>
					<div class="table-footer-perpage">
						<span>{PHP.L.adm_translations_show}:</span>
						{TRANSLATIONS_PERPAGE_BOTTOM}
					</div>
				</div>
				<div class="table-footer-actions">
					<button type="submit" class="btn btn-primary"><i class="ic-check"></i> {PHP.L.Update}</button>
				</div>
			</div>

		</form>

	</div>

</div>
<!-- END: LIST -->

<!-- BEGIN: EDIT_VARIABLE -->
<div class="content-box">

	<div class="content-box-header">
		<h3><i class="ic-pencil"></i> {PHP.L.Edit}: {EDIT_DISPLAY_KEY}</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content content-table">

		<form action="{EDIT_ACTION_URL}" method="post">

			<div class="table cells striped resp-table">
				<div class="table-body resp-table-body">

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td w-form-label">
							<strong>{PHP.L.adm_translations_key}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							<span class="translation-key-lg">{EDIT_DISPLAY_KEY}</span>
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							<strong>{PHP.L.adm_translations_scope} / {PHP.L.adm_translations_code}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							<span class="badge">{EDIT_SCOPE}<!-- IF {EDIT_CODE} != 'main' --> / {EDIT_CODE}<!-- ENDIF --></span>
						</div>
					</div>

					<!-- BEGIN: EDIT_LANG_ROW -->
					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							<strong>{EDIT_LANG_TITLE}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							{EDIT_LANG_VALUE_INPUT}
						</div>
					</div>
					<!-- END: EDIT_LANG_ROW -->

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td"></div>
						<div class="table-td text-left resp-table-td btn-group-actions">
							<button type="submit" class="btn btn-primary"><i class="ic-check"></i> {PHP.L.Update}</button>
							<a href="{EDIT_CANCEL_URL}" class="btn"><i class="ic-arrow-left"></i> {PHP.L.Cancel}</a>
						</div>
					</div>

				</div>
			</div>

		</form>

	</div>

</div>
<!-- END: EDIT_VARIABLE -->

<!-- BEGIN: ADD_VARIABLE -->
<div class="content-box">

	<div class="content-box-header">
		<h3><i class="ic-plus"></i> {PHP.L.adm_translations_add}</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content content-table">

		<!-- IF {ADD_KEY_EXISTS_WARNING} -->
		<div class="notification attention notification-flex">
			<div>
				<span><i class="ic-alert-triangle"></i> {ADD_KEY_EXISTS_MESSAGE}</span>
				<a href="{ADD_KEY_EXISTS_EDIT_URL}" class="btn btn-sm btn-primary"><i class="ic-edit"></i> {PHP.L.Edit}</a>
			</div>
		</div>
		<!-- ENDIF -->

		<form action="{TRANSLATIONS_ADD_URL}" method="post">

			<div class="table cells striped resp-table">
				<div class="table-body resp-table-body">

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td w-form-label">
							<strong>{PHP.L.adm_translations_key}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							<div class="translation-key-input-wrapper">
								<span class="translation-key-prefix">$L['</span>
								{ADD_KEY_INPUT}
								<span class="translation-key-suffix">']</span>
							</div>
							<div class="translation-help-block"><small>{PHP.L.adm_translations_key_hint}</small></div>
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							<strong>{PHP.L.adm_translations_scope}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							{ADD_SCOPE_SELECT}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							<strong>{PHP.L.adm_translations_code}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							{ADD_CODE_INPUT}
						</div>
					</div>

					<!-- BEGIN: ADD_LANG_ROW -->
					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							<strong>{ADD_LANG_TITLE}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							{ADD_LANG_VALUE_INPUT}
						</div>
					</div>
					<!-- END: ADD_LANG_ROW -->

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td"></div>
						<div class="table-td text-left resp-table-td">
							<button type="submit" class="btn btn-primary"><i class="ic-plus"></i> {PHP.L.adm_translations_add}</button>
						</div>
					</div>

				</div>
			</div>

		</form>

	</div>

</div>
<!-- END: ADD_VARIABLE -->

<!-- BEGIN: LANGUAGES -->
<div class="content-box sedtabs">

	<div class="content-box-header">
		<h3 class="tab-title">{PHP.L.adm_translations_languages}</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected" data-tabtitle="{PHP.L.adm_translations_languages}">{PHP.L.adm_translations_languages}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.adm_translations_lang_add}">{PHP.L.adm_translations_lang_add}</a></li>
		</ul>
	</div>

	<div class="content-box-content content-table">

		<div class="tab-content default-tab" id="tab1">

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left w-lang-code">{PHP.L.adm_translations_lang_code}</div>
						<div class="table-th coltop text-left">{PHP.L.adm_translations_lang_title}</div>
						<div class="table-th coltop text-left">{PHP.L.adm_translations_lang_native}</div>
						<div class="table-th coltop text-center w-lang-direction">{PHP.L.adm_translations_lang_direction}</div>
						<div class="table-th coltop text-center w-lang-active">{PHP.L.Active}</div>
						<div class="table-th coltop text-center w-lang-default">{PHP.L.Default}</div>
						<div class="table-th coltop text-center w-lang-order">{PHP.L.Order}</div>
						<div class="table-th coltop text-center w-lang-action">{PHP.L.Action}</div>
					</div>
				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: LANG_ROW -->
					<div class="table-row resp-table-row">

						<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_translations_lang_code}">
							{LANG_ROW_FLAG} <strong>{LANG_ROW_CODE}</strong>
						</div>

						<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_translations_lang_title}">
							{LANG_ROW_TITLE}
						</div>

						<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_translations_lang_native}">
							{LANG_ROW_NATIVE}
						</div>

						<div class="table-td text-center resp-table-td" data-label="{PHP.L.adm_translations_lang_direction}">
							{LANG_ROW_DIRECTION}
						</div>

						<div class="table-td text-center resp-table-td" data-label="{PHP.L.Active}">
							{LANG_ROW_ACTIVE}
						</div>

						<div class="table-td text-center resp-table-td" data-label="{PHP.L.Default}">
							<!-- BEGIN: LANG_DEFAULT_YES -->
							<i class="ic-check"></i>
							<!-- END: LANG_DEFAULT_YES -->
							<!-- BEGIN: LANG_DEFAULT_SET -->
							<a href="{LANG_ROW_DEFAULT_URL}" class="lang-set-default" title="{PHP.L.Default}"><i class="ic-wand"></i></a>
							<!-- END: LANG_DEFAULT_SET -->
						</div>

						<div class="table-td text-center resp-table-td" data-label="{PHP.L.Order}">
							{LANG_ROW_ORDER}
						</div>

						<div class="table-td text-center resp-table-td" data-label="{PHP.L.Action}">
							<a href="{LANG_ROW_EDIT_URL}" class="btn btn-small" title="{PHP.L.Edit}"><i class="ic-pencil"></i></a>
							<!-- BEGIN: LANG_DELETE_BTN -->
							<a href="{LANG_ROW_DELETE_URL}" class="btn btn-small" title="{PHP.L.Delete}" onclick="return sedjs.confirmact('{PHP.L.adm_translations_lang_delete_confirm}');"><i class="ic-trash"></i></a>
							<!-- END: LANG_DELETE_BTN -->
						</div>

					</div>
					<!-- END: LANG_ROW -->

				</div>

			</div>

		</div>

		<div class="tab-content" id="tab2">

			<form action="{TRANSLATIONS_LANG_SAVE_URL}" method="post">

				<div class="table cells striped resp-table">
					<div class="table-body resp-table-body">

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td w-form-label">
								<strong>{PHP.L.adm_translations_lang_code}:</strong>
							</div>
							<div class="table-td text-left resp-table-td">
								{LANG_ADD_CODE_INPUT}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								<strong>{PHP.L.adm_translations_lang_base}:</strong>
							</div>
							<div class="table-td text-left resp-table-td">
								{LANG_ADD_BASE_SELECT}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								<strong>{PHP.L.adm_translations_lang_title}:</strong>
							</div>
							<div class="table-td text-left resp-table-td">
								{LANG_ADD_TITLE_INPUT}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								<strong>{PHP.L.adm_translations_lang_native}:</strong>
							</div>
							<div class="table-td text-left resp-table-td">
								{LANG_ADD_NATIVE_INPUT}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								<strong>{PHP.L.adm_translations_lang_direction}:</strong>
							</div>
							<div class="table-td text-left resp-table-td">
								{LANG_ADD_DIRECTION_SELECT}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								<strong>{PHP.L.Order}:</strong>
							</div>
							<div class="table-td text-left resp-table-td">
								{LANG_ADD_ORDER_INPUT}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								<strong>{PHP.L.Active}:</strong>
							</div>
							<div class="table-td text-left resp-table-td">
								{LANG_ADD_ACTIVE_CHECKBOX}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td"></div>
							<div class="table-td text-left resp-table-td">
								<button type="submit" class="btn btn-primary"><i class="ic-plus"></i> {PHP.L.adm_translations_lang_add}</button>
							</div>
						</div>

					</div>
				</div>

			</form>

		</div>

	</div>

</div>
<!-- END: LANGUAGES -->

<!-- BEGIN: EDIT_LANGUAGE -->
<div class="content-box">

	<div class="content-box-header">
		<h3><i class="ic-pencil"></i> {PHP.L.adm_translations_lang_edit}: {LANG_EDIT_TITLE} ({LANG_EDIT_CODE})</h3>
		<div class="clear"></div>
	</div>

	<div class="content-box-content content-table">

		<form action="{TRANSLATIONS_LANG_SAVE_URL}" method="post">

			<div class="table cells striped resp-table">
				<div class="table-body resp-table-body">

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td w-form-label">
							<strong>{PHP.L.adm_translations_lang_code}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							{LANG_EDIT_CODE_INPUT}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							<strong>{PHP.L.adm_translations_lang_title}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							{LANG_EDIT_TITLE_INPUT}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							<strong>{PHP.L.adm_translations_lang_native}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							{LANG_EDIT_NATIVE_INPUT}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							<strong>{PHP.L.adm_translations_lang_direction}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							{LANG_EDIT_DIRECTION_SELECT}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							<strong>{PHP.L.Order}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							{LANG_EDIT_ORDER_INPUT}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							<strong>{PHP.L.Active}:</strong>
						</div>
						<div class="table-td text-left resp-table-td">
							{LANG_EDIT_ACTIVE_CHECKBOX}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td"></div>
						<div class="table-td text-left resp-table-td btn-group-actions">
							<button type="submit" class="btn btn-primary"><i class="ic-check"></i> {PHP.L.Update}</button>
							<a href="{LANG_EDIT_CANCEL_URL}" class="btn"><i class="ic-arrow-left"></i> {PHP.L.Cancel}</a>
						</div>
					</div>

				</div>
			</div>

		</form>

	</div>

</div>
<!-- END: EDIT_LANGUAGE -->

<!-- BEGIN: IO -->

<div class="row row-flex">

	<!-- Export Card -->
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<div class="content-box flex-box-card">
			<div class="content-box-header">
				<h3><i class="ic-upload"></i> {PHP.L.adm_translations_export}</h3>
				<div class="clear"></div>
			</div>
			<div class="content-box-content content-table">
				<form action="{IO_EXPORT_ACTION_URL}" method="post">
					<div class="table cells striped resp-table">
						<div class="table-body resp-table-body">

							<div class="table-row resp-table-row">
								<div class="table-td text-left resp-table-td" style="width: 35%;">
									<strong>{PHP.L.adm_translations_language}:</strong>
								</div>
								<div class="table-td text-left resp-table-td">
									{IO_EXPORT_LANG_SELECT}
								</div>
							</div>

							<div class="table-row resp-table-row">
								<div class="table-td text-left resp-table-td" style="width: 35%;">
									<strong>{PHP.L.adm_translations_scope}:</strong>
								</div>
								<div class="table-td text-left resp-table-td">
									{IO_EXPORT_SCOPE_SELECT}
								</div>
							</div>

						</div>
					</div>

					<div class="io-box-footer">
						<p class="help-block"><small>{PHP.L.adm_translations_export_hint}</small></p>
						<button type="submit" class="btn btn-primary">
							<i class="ic-download"></i> {PHP.L.adm_translations_export_btn}
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Import Card -->
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<div class="content-box flex-box-card">
			<div class="content-box-header">
				<h3><i class="ic-download"></i> {PHP.L.adm_translations_import_file_title}</h3>
				<div class="clear"></div>
			</div>
			<div class="content-box-content content-table">
				<form action="{IO_IMPORT_ACTION_URL}" method="post" enctype="multipart/form-data">
					<div class="table cells striped resp-table">
						<div class="table-body resp-table-body">

							<div class="table-row resp-table-row">
								<div class="table-td text-left resp-table-td" style="width: 35%;">
									<strong>{PHP.L.adm_translations_import_select_file}:</strong>
								</div>
								<div class="table-td text-left resp-table-td">
									<input type="file" name="import_file" accept=".json,application/json" class="form-control" required="required" />
								</div>
							</div>

							<div class="table-row resp-table-row">
								<div class="table-td text-left resp-table-td" style="width: 35%;">
									<strong>{PHP.L.adm_translations_target_lang}:</strong>
								</div>
								<div class="table-td text-left resp-table-td">
									{IO_IMPORT_LANG_SELECT}
								</div>
							</div>

							<div class="table-row resp-table-row">
								<div class="table-td text-left resp-table-td" style="width: 35%;">
									<strong>{PHP.L.adm_translations_strategy}:</strong>
								</div>
								<div class="table-td text-left resp-table-td">
									{IO_IMPORT_STRATEGY_SELECT}
								</div>
							</div>

						</div>
					</div>

					<div class="io-box-footer">
						<p class="help-block"><small>{PHP.L.adm_translations_import_hint}</small></p>
						<button type="submit" class="btn btn-primary">
							<i class="ic-upload"></i> {PHP.L.adm_translations_import_btn}
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

</div>
<!-- END: IO -->

<!-- BEGIN: TOOLS -->
<div class="row row-flex">

	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<div class="content-box flex-box-card">
			<div class="content-box-header">
				<h3><i class="ic-refresh"></i> {PHP.L.adm_translations_regenerate}</h3>
				<div class="clear"></div>
			</div>
			<div class="content-box-content content-table">
				<div class="tools-box-body">
					<p>{PHP.L.adm_langcache_regenerate_hint}</p>
					<div class="tools-box-action">
						<a href="{TRANSLATIONS_REGENERATE_URL}" class="btn btn-primary"><i class="ic-refresh"></i> {PHP.L.adm_translations_regenerate}</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<div class="content-box flex-box-card">
			<div class="content-box-header">
				<h3><i class="ic-download"></i> {PHP.L.adm_translations_import}</h3>
				<div class="clear"></div>
			</div>
			<div class="content-box-content content-table">
				<div class="tools-box-body">
					<p>{PHP.L.adm_translations_import_desc}</p>
					<div class="tools-box-action">
						<a href="{TRANSLATIONS_IMPORT_URL}" class="btn"><i class="ic-download"></i> {PHP.L.adm_translations_import_missing}</a>
						<a href="{TRANSLATIONS_REIMPORT_URL}" class="btn btn-warning" onclick="return sedjs.confirmact('{PHP.L.adm_translations_reimport_confirm}');"><i class="ic-refresh"></i> {PHP.L.adm_translations_reimport}</a>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<!-- END: TOOLS -->

<!-- END: ADMIN_TRANSLATIONS -->
