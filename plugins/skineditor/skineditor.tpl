<!-- BEGIN: SKINEDITOR -->

<!-- BEGIN: SKINEDITOR_ERROR -->

{SKINEDITOR_ERROR_BODY}

<!-- END: SKINEDITOR_ERROR -->

<!-- BEGIN: SKIN_LIST -->

<h4>{PHP.L.Skin}</h4>

<div class="table cells striped resp-table">

	<div class="table-head resp-table-head">

		<div class="table-row resp-table-row">

			<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Edit}</div>
			<div class="table-th coltop text-left">{PHP.L.Skin}</div>
			<div class="table-th coltop text-center" style="width:15%;">{PHP.L.Code}</div>
			<div class="table-th coltop text-center" style="width:10%;">{PHP.L.Version}</div>
			<div class="table-th coltop text-center" style="width:15%;">{PHP.L.Updated}</div>
			<div class="table-th coltop text-center" style="width:25%;">{PHP.L.Author}</div>
			<div class="table-th coltop text-center" style="width:10%;">{PHP.L.Default}</div>
			<div class="table-th coltop text-center" style="width:10%;">{PHP.L.Set}</div>

		</div>

	</div>

	<div class="table-body resp-table-body">

		<!-- BEGIN: SKIN_LIST_ROW -->

		<div class="table-row resp-table-row">

			<div class="table-td text-center resp-table-td skineditor-edit" data-label="{PHP.L.Edit}">
				{SKIN_LIST_ROW_EDIT}
			</div>

			<div class="table-td text-left resp-table-td skineditor-edit" data-label="{PHP.L.Skin}">
				{SKIN_LIST_ROW_NAME}
			</div>

			<div class="table-td text-center resp-table-td skineditor-edit" data-label="{PHP.L.Code}">
				{SKIN_LIST_ROW_CODE}
			</div>

			<div class="table-td text-center resp-table-td skineditor-edit" data-label="{PHP.L.Version}">
				{SKIN_LIST_ROW_VERSION}
			</div>

			<div class="table-td text-center resp-table-td skineditor-edit" data-label="{PHP.L.Updated}">
				{SKIN_LIST_ROW_UPDATED}
			</div>

			<div class="table-td text-center resp-table-td skineditor-edit" data-label="{PHP.L.Author}">
				{SKIN_LIST_ROW_AUTHOR}
			</div>

			<div class="table-td text-center resp-table-td skineditor-edit" data-label="{PHP.L.Default}">
				{SKIN_LIST_ROW_DEFAULT}
			</div>

			<div class="table-td text-center resp-table-td skineditor-edit" data-label="{PHP.L.Set}">
				{SKIN_LIST_ROW_SET}
			</div>

		</div>

		<!-- END: SKIN_LIST_ROW -->

	</div>

</div>

<!-- END: SKIN_LIST -->

<!-- BEGIN: TPL_SKIN_LIST -->

<h4>{PHP.L.TPL}</h4>

<div class="table cells striped resp-table">

	<div class="table-head resp-table-head">

		<div class="table-row resp-table-row">

			<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Edit}</div>
			<div class="table-th coltop text-left">{PHP.L.File}</div>
			<div class="table-th coltop text-center" style="width:10%;">{PHP.L.Size}</div>
			<div class="table-th coltop text-center" style="width:15%;">{PHP.L.plu_makbak}</div>
			<div class="table-th coltop text-center" style="width:15%;">{PHP.L.plu_delbak}</div>
			<div class="table-th coltop text-center" style="width:15%;">{PHP.L.plu_resbak}</div>

		</div>

	</div>

	<div class="table-body resp-table-body">

		<!-- BEGIN: TPL_SKIN_LIST_ROW -->

		<div class="table-row resp-table-row">

			<div class="table-td text-center resp-table-td skineditor-tpl-edit" data-label="{PHP.L.Edit}">
				{TPL_SKIN_LIST_ROW_EDIT}
			</div>

			<div class="table-td text-left resp-table-td skineditor-tpl-file" data-label="{PHP.L.File}">
				{TPL_SKIN_LIST_ROW_TPLFILE}
			</div>

			<div class="table-td text-center resp-table-td skineditor-tpl-size" data-label="{PHP.L.Size}">
				{TPL_SKIN_LIST_ROW_TPLSIZE}
			</div>

			<div class="table-td text-center resp-table-td skineditor-tpl-makbak" data-label="{PHP.L.plu_makbak}">
				{TPL_SKIN_LIST_ROW_BACKUP} {TPL_SKIN_LIST_ROW_XBACKUP}
			</div>

			<div class="table-td text-center resp-table-td skineditor-tpl-delbak" data-label="{PHP.L.plu_delbak}">
				{TPL_SKIN_LIST_ROW_DELETE_BACKUP}
			</div>

			<div class="table-td text-center resp-table-td skineditor-tpl-resbak" data-label="{PHP.L.plu_resbak}">
				{TPL_SKIN_LIST_ROW_RESTORE_BACKUP}
			</div>

		</div>

		<!-- END: TPL_SKIN_LIST_ROW -->

		<div class="table-row resp-table-row">

			<div class="table-td text-left resp-table-td skineditor-tpl-edit">

			</div>

			<div class="table-td text-left resp-table-td skineditor-tpl-file">
				{TPL_SKIN_LIST_FILES} {PHP.L.Files}
			</div>

			<div class="table-td text-center resp-table-td skineditor-tpl-size">
				{PHP.L.Total} : {TPL_SKIN_LIST_TOTALSIZE}
			</div>

			<div class="table-td text-left resp-table-td skineditor-tpl-makbak">

			</div>

			<div class="table-td text-left resp-table-td skineditor-tpl-delbak">

			</div>

			<div class="table-td text-left resp-table-td skineditor-tpl-resbak">

			</div>

		</div>

	</div>

</div>

<!-- END: TPL_SKIN_LIST -->

<!-- BEGIN: TPL_SKIN_EDIT -->

<h4>{PHP.L.Edit} {PHP.L.TPL}</h4>

<form id="update" action="{TPL_SKIN_EDIT_SEND}" method="post">

	<div class="table cells striped">

		<div class="table-body">

			<div class="table-row">
				<div class="table-td text-left">
					{PHP.L.File} : {TPL_SKIN_EDIT_FILE}
				</div>
				<div class="table-td text-right">
					<a href="{TPL_SKIN_EDIT_CHANCEL_URL}" class="btn btn-adm">{PHP.L.Cancel}</a>
				</div>
			</div>

		</div>

	</div>

	<div class="box">

		{TPL_SKIN_EDIT_TEXTAREA}

		<script>
			var editor = CodeMirror.fromTextArea(document.getElementById("content"), {
				lineNumbers: true,
				matchBrackets: true,
				indentUnit: 4,
				indentWithTabs: true,
				mode: "{TPL_SKIN_EDIT_HMODE}",
				tabMode: "shift",
				theme: 'default'
			});
		</script>

	</div>

	<div class="text-center">

		<input type="submit" class="submit btn" name="b1" value="{PHP.L.Update}" />
		<input type="submit" class="submit btn" name="b2" value="{PHP.L.plu_reopen}" />

	</div>

</form>

<!-- END: TPL_SKIN_EDIT -->

<!-- END: SKINEDITOR -->