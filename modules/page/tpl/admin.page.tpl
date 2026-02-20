<!-- BEGIN: ADMIN_PAGE -->

<div class="title">
	<span><i class="ic-forums"></i></span>
	<h2>{ADMIN_PAGE_TITLE}</h2>
</div>

<!-- BEGIN: STRUCTURE_UPDATE -->

<div class="content-box sedtabs">

	<div class="content-box-header">
		<h3 class="tab-title">{PHP.L.Structure}</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected" data-tabtitle="{PHP.L.Structure}">{PHP.L.Structure}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.Meta}">{PHP.L.Meta}</a></li>
		</ul>
	</div>

	<div class="content-box-content content-table">

		<form id="savestructure" action="{STRUCTURE_UPDATE_SEND}" method="post">

			<div class="tab-content default-tab" id="tab1">

				<ul class="form responsive-form">

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Code} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_CODE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Path} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_PATH}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Title} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_TITLE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Description} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_DESC}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Icon} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_ICON}</div>
					</li>

					<li class="form-row">
						<div class="form-field-100">{PHP.L.Text} :<br />{STRUCTURE_UPDATE_TEXT}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Thumbnail} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_THUMB}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Group} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_GROUP}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.adm_tpl_mode} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_TPL}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.adm_enablecomments} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_ALLOWCOMMENTS}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.adm_enableratings} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_ALLOWRATINGS}</div>
					</li>

				</ul>

			</div>

			<div class="tab-content" id="tab2">

				<ul class="form responsive-form">

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Title} H1 :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_SEOH1}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.mt_title} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_SEOTITLE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.mt_description} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_SEODESC}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.mt_keywords} :</label></div>
						<div class="form-field">{STRUCTURE_UPDATE_SEOKEYWORDS}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.mt_index} :</label></div>
						<div class="form-field">
							{STRUCTURE_UPDATE_SEOINDEX}
							<div class="help">{PHP.L.mt_index_help}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.mt_follow} :</label></div>
						<div class="form-field">
							{STRUCTURE_UPDATE_SEOFOLLOW}
							<div class="help">{PHP.L.mt_follow_help}</div>
						</div>
					</li>

				</ul>

			</div>

			<div style="text-align: center; padding:15px 0;">
				<button type="submit" class="submit btn">{PHP.L.Update}</button>
			</div>

		</form>

	</div>

</div>
<!-- END: STRUCTURE_UPDATE -->

<!-- BEGIN: PAGE_STRUCTURE -->

<div class="content-box sedtabs">

	<div class="content-box-header">
		<h3 class="tab-title">{PHP.L.Structure}</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected" data-tabtitle="{PHP.L.Structure}">{PHP.L.Structure}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.addnewentry}">{PHP.L.addnewentry}</a></li>
		</ul>
	</div>

	<div class="content-box-content content-table">

		<div class="tab-content default-tab" id="tab1">

			<form id="savestructure" action="{PAGE_STRUCTURE_SEND}" method="post">

				<div class="table cells striped resp-table">

					<div class="table-head resp-table-head">
						<div class="table-row resp-table-row">
							<div class="table-th coltop text-left">{PHP.L.Code}</div>
							<div class="table-th coltop text-left">{PHP.L.Title}</div>
							<div class="table-th coltop text-left">{PHP.L.Path}</div>
							<div class="table-th coltop text-left">{PHP.L.TPL}</div>
							<div class="table-th coltop text-left">{PHP.L.Group}</div>
							<div class="table-th coltop text-center" style="width:80px;">{PHP.L.Pages}</div>
							<div class="table-th coltop text-center" style="width:40px;">{PHP.L.Rights}</div>
							<div class="table-th coltop text-center" style="width:40px;">{PHP.L.Delete}</div>
						</div>
					</div>

					<div class="table-body resp-table-body">

						<!-- BEGIN: STRUCTURE_LIST -->

						<div class="table-row resp-table-row">
							<div class="table-td text-left resp-table-td structure-code" data-label="{PHP.L.Code}">
								{STRUCTURE_LIST_CODE}
							</div>
							<div class="table-td text-left resp-table-td structure-title" data-label="{PHP.L.Title}">
								{STRUCTURE_LIST_TITLE}
							</div>
							<div class="table-td text-left resp-table-td structure-path" data-label="{PHP.L.Path}">
								{STRUCTURE_LIST_PATH}
							</div>
							<div class="table-td text-left resp-table-td structure-tpl" data-label="{PHP.L.TPL}">
								{STRUCTURE_LIST_TPL}
							</div>
							<div class="table-td text-left resp-table-td structure-group" data-label="{PHP.L.Group}">
								{STRUCTURE_LIST_GROUP}
							</div>
							<div class="table-td text-center resp-table-td structure-pagecount" data-label="{PHP.L.Pages}">
								{STRUCTURE_LIST_PAGECOUNT} <a href="{STRUCTURE_LIST_OPEN_URL}"><i class="ic-arrow-right"></i></a>
							</div>
							<div class="table-td text-center resp-table-td structure-rights" data-label="{PHP.L.Rights}">
								<a href="{STRUCTURE_LIST_RIGHTS_URL}"><i class="ic-lock"></i></a>
							</div>
							<div class="table-td text-center resp-table-td structure-delete" data-label="{PHP.L.Delete}">
								<!-- BEGIN: STRUCTURE_LIST_DELETE -->
								<a href="{STRUCTURE_LIST_DELETE_URL}"><i class="ic-trash"></i></a>
								<!-- END: STRUCTURE_LIST_DELETE -->
							</div>
						</div>

						<!-- END: STRUCTURE_LIST -->

					</div>

				</div>

				<div class="table-btn text-center">
					<button type="submit" class="submit btn">{PHP.L.Update}</button>
				</div>

			</form>

		</div>

		<div class="tab-content" id="tab2">

			<form id="addstructure" action="{PAGE_STRUCTURE_ADD_SEND}" method="post">

				<ul class="form responsive-form">

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Code} :</label></div>
						<div class="form-field">{PAGE_STRUCTURE_ADD_CODE} <div class="descr">{PHP.L.adm_required}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Path} :</label></div>
						<div class="form-field">{PAGE_STRUCTURE_ADD_PATH} <div class="descr">{PHP.L.adm_required}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Title} :</label></div>
						<div class="form-field">{PAGE_STRUCTURE_ADD_TITLE} <div class="descr">{PHP.L.adm_required}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Description} :</label></div>
						<div class="form-field">{PAGE_STRUCTURE_ADD_DESC}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Icon} :</label></div>
						<div class="form-field">{PAGE_STRUCTURE_ADD_ICON}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Group} :</label></div>
						<div class="form-field">{PAGE_STRUCTURE_ADD_GROUP}</div>
					</li>

					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn">{PHP.L.Add}</button>
						</div>
					</li>

				</ul>

			</form>

		</div>

	</div>

</div>

<!-- END: PAGE_STRUCTURE -->

<!-- BEGIN: PAGE_SORTING -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.adm_sortingorder}</h3>
	</div>

	<div class="content-box-content content-table">

		<form id="chgorder" action="{PAGE_SORTING_SEND}" method="post">

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">
					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left">{PHP.L.Code}</div>
						<div class="table-th coltop text-left">{PHP.L.Path}</div>
						<div class="table-th coltop text-left">{PHP.L.Title}</div>
						<div class="table-th coltop text-left">{PHP.L.Order}</div>
					</div>
				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: SORTING_STRUCTURE_LIST -->
					<div class="table-row resp-table-row">
						<div class="table-td text-left resp-table-td structure-code" data-label="{PHP.L.Code}">{STRUCTURE_LIST_CODE}</div>
						<div class="table-td text-left resp-table-td structure-path" data-label="{PHP.L.Path}">{STRUCTURE_LIST_PATH}</div>
						<div class="table-td text-left resp-table-td structure-title" data-label="{PHP.L.Title}">{STRUCTURE_LIST_TITLE}</div>
						<div class="table-td text-left resp-table-td structure-order" data-label="{PHP.L.Order}">{STRUCTURE_LIST_ORDER}</div>
					</div>
					<!-- END: SORTING_STRUCTURE_LIST -->

				</div>

			</div>

			<div class="table-btn text-center">
				<button type="submit" class="submit btn">{PHP.L.Update}</button>
			</div>

		</form>

	</div>

</div>

<!-- END: PAGE_SORTING -->

<!-- BEGIN: PAGE_QUEUE -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.adm_valqueue}</h3>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left">#</div>
					<div class="table-th coltop text-left">{PHP.L.Title} {PHP.L.adm_clicktoedit}</div>
					<div class="table-th coltop text-left">{PHP.L.Category}</div>
					<div class="table-th coltop text-left">{PHP.L.Date}</div>
					<div class="table-th coltop text-left">{PHP.L.Owner}</div>
					<div class="table-th coltop text-center" style="width:120px;">{PHP.L.Validate}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: PAGE_QUEUE_LIST -->

				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td page-id" data-label="#">{PAGE_LIST_ID}</div>
					<div class="table-td text-left resp-table-td page-title" data-label="{PHP.L.Title} {PHP.L.adm_clicktoedit}">{PAGE_LIST_TITLE}</div>
					<div class="table-td text-left resp-table-td page-category" data-label="{PHP.L.Category}">{PAGE_LIST_CATPATH}</div>
					<div class="table-td text-left resp-table-td page-date" data-label="{PHP.L.Date}">{PAGE_LIST_DATE}</div>
					<div class="table-td text-left resp-table-td page-owner" data-label="{PHP.L.Owner}">{PAGE_LIST_OWNER}</div>
					<div class="table-td text-center resp-table-td page-validate">{PAGE_LIST_VALIDATE}</div>
				</div>

				<!-- END: PAGE_QUEUE_LIST -->

			</div>

		</div>

		<!-- BEGIN: WARNING -->
		<div style="padding:10px;">
			<p>{PHP.L.None}</p>
		</div>
		<!-- END: WARNING -->

	</div>

</div>

<!-- END: PAGE_QUEUE -->

<!-- END: ADMIN_PAGE -->