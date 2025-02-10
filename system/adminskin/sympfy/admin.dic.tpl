<!-- BEGIN: ADMIN_DIC -->

<div class="title">
	<span><i class="ic-forums"></i></span>
	<h2>{ADMIN_DIC_TITLE}</h2>
</div>

<!-- BEGIN: DIC_STRUCTURE -->

<div class="content-box sedtabs">

	<div class="content-box-header">
		<h3 class="tab-title">{PHP.L.adm_dic_list}</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected" data-tabtitle="{PHP.L.adm_dic_list}">{PHP.L.adm_dic_list}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.adm_dic_add}">{PHP.L.adm_dic_add}</a></li>
		</ul>
	</div>

	<div class="content-box-content content-table">

		<div class="tab-content default-tab" id="tab1">

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left" style="width:20px">{PHP.L.Id}</div>
						<div class="table-th coltop text-left">{PHP.L.Title}</div>
						<div class="table-th coltop text-left">{PHP.L.adm_dic_code}</div>
						<div class="table-th coltop text-left">{PHP.L.Type}</div>
						<div class="table-th coltop text-left" style="width:100px">{PHP.L.Options}</div>
					</div>
				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: DIC_LIST -->

					<div class="table-row resp-table-row">

						<div class="table-td text-left resp-table-td dic-id" data-label="{PHP.L.Id}">
							{DIC_LIST_ID}
						</div>

						<div class="table-td text-left resp-table-td dic-title" data-label="{PHP.L.Title}">
							<a href="{DIC_LIST_URL}">{DIC_LIST_TITLE}</a>
						</div>

						<div class="table-td text-left resp-table-td dic-code" data-label="{PHP.L.adm_dic_code}">
							{DIC_LIST_CODE}
						</div>

						<div class="table-td text-left resp-table-td dic-type" data-label="{PHP.L.Type}">
							{DIC_LIST_TYPE}
						</div>

						<div class="table-td text-left resp-table-td dic-actions">

							<!-- BEGIN: ADMIN_DELETE -->
							<a href="{DIC_LIST_DELETE_URL}" title="{PHP.L.Delete}" onclick="return confirmDelete();" class="btn btn-small"><i class="ic-trash"></i></a>
							<!-- END: ADMIN_DELETE -->

							<!-- BEGIN: ADMIN_ACTIONS -->
							<a href="{DIC_LIST_EDIT_URL}" title="{PHP.L.Edit}" class="btn btn-small"><i class="ic-edit"></i></a>
							<!-- END: ADMIN_ACTIONS -->

						</div>

					</div>

					<!-- END: DIC_LIST -->

				</div>

			</div>

		</div>

		<div class="tab-content" id="tab2">

			<form id="adddirectory" action="{DIC_ADD_SEND}" method="post">

				<div class="table cells striped resp-table">

					<div class="table-body resp-table-body">

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td" style="width:220px;">
								{PHP.L.Title} :
							</div>
							<div class="table-td text-left resp-table-td">
								{DIC_ADD_TITLE}
								<div class="descr">{PHP.L.adm_required}</div>
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.adm_dic_code} :
							</div>
							<div class="table-td text-left resp-table-td">
								{DIC_ADD_CODE}
								<div class="descr">{PHP.L.adm_required}</div>
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.adm_dic_mera} :
							</div>
							<div class="table-td text-left resp-table-td">
								{DIC_ADD_MERA}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.adm_dic_values} :
							</div>
							<div class="table-td text-left resp-table-td">
								{DIC_ADD_VALUES}
								<div class="descr">{PHP.L.adm_dic_comma_separat}</div>
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.adm_dic_form_title} :
							</div>
							<div class="table-td text-left resp-table-td">
								{DIC_ADD_FORM_TITLE}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.adm_dic_form_desc} :
							</div>
							<div class="table-td text-left resp-table-td">
								{DIC_ADD_FORM_DESC}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.adm_dic_form_size} :
							</div>
							<div class="table-td text-left resp-table-td">
								{DIC_ADD_FORM_SIZE}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.adm_dic_form_maxsize} :
							</div>
							<div class="table-td text-left resp-table-td">
								{DIC_ADD_FORM_MAXSIZE}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.adm_dic_form_cols} :
							</div>
							<div class="table-td text-left resp-table-td">
								{DIC_ADD_FORM_COLS}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.adm_dic_form_rows} :
							</div>
							<div class="table-td text-left resp-table-td">
								{DIC_ADD_FORM_ROWS}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.Type} :
							</div>
							<div class="table-td text-left resp-table-td">
								{DIC_ADD_TYPE}
							</div>
						</div>

					</div>

				</div>

				<div class="table-btn text-center">
					<button type="submit" class="submit btn">{PHP.L.Add}</button>
				</div>

			</form>

		</div>

	</div>

</div>

<!-- END: DIC_STRUCTURE -->

<!-- BEGIN: DIC_EDIT -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.adm_dic_edit}</h3>
		<div class="content-box-header-right">

			<div class="clear"></div>
		</div>
	</div>

	<div class="content-box-content content-table">

		<form id="updatedirectory" action="{DIC_EDIT_SEND}" method="post">

			<div class="table cells striped resp-table">

				<div class="table-body resp-table-body">

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td" style="width:220px;">
							{PHP.L.Title} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EDIT_TITLE}
							<div class="descr">{PHP.L.adm_required}</div>
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_mera} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EDIT_MERA}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_values} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EDIT_VALUES}
							<div class="descr">{PHP.L.adm_dic_comma_separat}</div>
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_form_title} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EDIT_FORM_TITLE}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_form_desc} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EDIT_FORM_DESC}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_form_size} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EDIT_FORM_SIZE}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_form_maxsize} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EDIT_FORM_MAXSIZE}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_form_cols} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EDIT_FORM_COLS}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_form_rows} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EDIT_FORM_ROWS}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.Type} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EDIT_TYPE}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_parentcat} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EDIT_DICPARENT}
						</div>
					</div>

				</div>

			</div>

			<div class="table-btn text-center">
				<button type="submit" class="submit btn">{PHP.L.Update}</button>
			</div>

		</form>

	</div>

</div>

<!-- END: DIC_EDIT -->

<!-- BEGIN: DIC_TERMS -->

<div class="content-box sedtabs">

	<div class="content-box-header">
		<h3 class="tab-title">{PHP.L.adm_dic_term_list}"</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected" data-tabtitle="{PHP.L.adm_dic_term_list}">{PHP.L.adm_dic_term_list}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.adm_dic_add_term}">{PHP.L.adm_dic_add_term}</a></li>
		</ul>
	</div>

	<div class="content-box-content content-table">

		<div class="tab-content default-tab" id="tab1">

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">

					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left" style="width:20px;">{PHP.L.Id}</div>
						<div class="table-th coltop text-left">{PHP.L.adm_dic_term_title}</div>
						<div class="table-th coltop text-left">{PHP.L.adm_dic_term_value}</div>
						<div class="table-th coltop text-left">{PHP.L.adm_dic_children}</div>
						<div class="table-th coltop text-left" style="width:150px;">{PHP.L.adm_dic_term_defval}</div>
						<div class="table-th coltop text-left" style="width:100px;">{PHP.L.Options}</div>
					</div>

				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: TERMS_LIST -->

					<div class="table-row resp-table-row">

						<div class="table-td text-left resp-table-td" data-label="{PHP.L.Id}">
							{TERM_LIST_ID}
						</div>

						<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_dic_term_title}">
							{TERM_LIST_TITLE}
						</div>

						<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_dic_term_value}">
							{TERM_LIST_CODE}
						</div>

						<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_dic_children}">
							{TERM_LIST_CHILDRENDIC}
						</div>

						<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_dic_term_defval}">
							<!-- BEGIN: TERM_DEFAULT -->
							<i class="ic-check"></i>
							<!-- END: TERM_DEFAULT -->
						</div>

						<div class="table-td text-left resp-table-td dic-actions">
							<a href="{TERM_LIST_DELETE_URL}" title="{PHP.L.Delete}" onclick="return confirmDelete();" class="btn btn-small"><i class="ic-trash"></i></a>
							<a href="{TERM_LIST_EDIT_URL}" title="{PHP.L.Edit}" class="btn btn-small"><i class="ic-edit"></i></a>
						</div>

					</div>

					<!-- END: TERMS_LIST -->

				</div>

			</div>

		</div>

		<div class="tab-content" id="tab2">

			<form id="addstructure" action="{TERM_ADD_SEND}" method="post">

				<div class="table cells striped resp-table">

					<div class="table-body resp-table-body">

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td" style="width:180px;">
								{PHP.L.adm_dic_term_title} :
							</div>
							<div class="table-td text-left resp-table-td">
								{TERM_ADD_TITLE}
								<div class="descr">{PHP.L.adm_required}</div>
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.adm_dic_term_value} :
							</div>
							<div class="table-td text-left resp-table-td">
								{TERM_ADD_CODE}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.adm_dic_children} :
							</div>
							<div class="table-td text-left resp-table-td">
								{TERM_ADD_CHILDRENDIC}
							</div>
						</div>

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td">
								{PHP.L.adm_dic_term_defval} :
							</div>
							<div class="table-td text-left resp-table-td">
								{TERM_ADD_DEFVAL}
							</div>
						</div>

					</div>

				</div>

				<div class="table-btn text-center">
					<button type="submit" class="submit btn">{PHP.L.Add}</button>
				</div>

			</form>

		</div>

	</div>

</div>

<!-- END: DIC_TERMS -->

<!-- BEGIN: DIC_ITEM_EDIT -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.adm_dic_term_edit} : {DIC_TITLE}</h3>
		<div class="content-box-header-right">
			<div class="clear"></div>
		</div>
	</div>

	<div class="content-box-content content-table">

		<form id="updatedirectory" action="{DIC_ITEM_EDIT_SEND}" method="post">

			<div class="table cells striped resp-table">

				<div class="table-body resp-table-body">

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_term_title} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_ITEM_EDIT_TITLE}
							<div class="descr">{PHP.L.adm_required}</div>
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_term_value} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_ITEM_EDIT_CODE}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_children} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_ITEM_EDIT_CHILDRENDIC}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_term_defval} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_ITEM_EDIT_DEFVAL}
						</div>
					</div>

				</div>

			</div>

			<div class="table-btn text-center">
				<button type="submit" class="submit btn">{PHP.L.Update}</button>
			</div>

		</form>

	</div>

</div>

<!-- END: DIC_ITEM_EDIT -->


<!-- BEGIN: DIC_EXTRA -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.adm_dic_extra} / {DIC_EXTRA_TITLE}</h3>
		<div class="content-box-header-right">
			<div class="clear"></div>
		</div>
	</div>

	<div class="content-box-content content-table">

		<form id="addextra" action="{DIC_EXTRA_SEND}" method="post">

			<div class="table cells striped resp-table">

				<div class="table-body resp-table-body">

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td" style="width:180px;">
							{PHP.L.adm_dic_code} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EXTRA_DICCODE}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td" style="width:180px;">
							{PHP.L.adm_dic_extra_location} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EXTRA_LOCATION}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_extra_type} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EXTRA_TYPE}
						</div>
					</div>

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.adm_dic_extra_size} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EXTRA_SIZE}
						</div>
					</div>

					<!-- BEGIN: DIC_EXTRA_DELETE -->

					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td">
							{PHP.L.Delete} :
						</div>
						<div class="table-td text-left resp-table-td">
							{DIC_EXTRA_DELETE}
						</div>
					</div>

					<!-- END: DIC_EXTRA_DELETE -->

				</div>

			</div>

			<div class="table-btn text-center">
				<button type="submit" class="submit btn">{DIC_EXTRA_SUBMIT_NAME}</button>
			</div>

		</form>

	</div>

</div>

<!-- END: DIC_EXTRA -->

<!-- END: ADMIN_DIC -->