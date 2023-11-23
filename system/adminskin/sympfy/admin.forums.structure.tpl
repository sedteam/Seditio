<!-- BEGIN: ADMIN_FORUMS -->

<div class="title">
	<span><i class="ic-forums"></i></span>
	<h2>{ADMIN_FORUMS_TITLE}</h2>
</div>

<!-- BEGIN: FORUMS_STRUCTURE_UPDATE -->

<div class="content-box">
	<div class="content-box-header">
		<h3>{PHP.L.editdeleteentries} : {FN_UPDATE_FORM_TITLE}</h3>
	</div>

	<div class="content-box-content">

		<form id="savestructure" action="{FN_UPDATE_SEND}" method="post">

			<ul class="form responsive-form">

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Code} :</label></div>
					<div class="form-field">{FN_UPDATE_CODE}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Path} :</label></div>
					<div class="form-field">{FN_UPDATE_PATH}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Title} :</label></div>
					<div class="form-field">{FN_UPDATE_TITLE}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Description} :</label></div>
					<div class="form-field">{FN_UPDATE_DESC}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Icon} :</label></div>
					<div class="form-field">{FN_UPDATE_ICON}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_defstate} :</label></div>
					<div class="form-field">{FN_UPDATE_DEFSTATE}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_tpl_mode} :</label></div>
					<div class="form-field">{FN_UPDATE_TPLMODE}</div>
				</li>

			</ul>
			<div class="form-btn text-center">
				<button type="submit" class="submit btn">{PHP.L.Update}</button>
			</div>

		</form>

	</div>

</div>

<!-- END: FORUMS_STRUCTURE_UPDATE -->


<!-- BEGIN: FORUMS_STRUCTURE -->

<div class="content-box sedtabs">
	<div class="content-box-header">
		<h3 class="tab-title">{PHP.L.adm_forum_structure_cat}</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected" data-tabtitle="{PHP.L.adm_forum_structure_cat}">{PHP.L.adm_forum_structure_cat}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.addnewentry}">{PHP.L.addnewentry}</a></li>
		</ul>
	</div>

	<div class="content-box-content content-table">

		<div class="tab-content default-tab" id="tab1">

			<form id="savestructure" action="{FORUMS_STRUCTURE_UPDATE_SEND}" method="post">

				<div class="table cells striped resp-table">

					<div class="table-head resp-table-head">

						<div class="table-row resp-table-row">

							<div class="table-th coltop text-left">{PHP.L.Code}</div>
							<div class="table-th coltop text-left">{PHP.L.Path}</div>
							<div class="table-th coltop text-left">{PHP.L.adm_defstate}</div>
							<div class="table-th coltop text-center">{PHP.L.TPL}</div>
							<div class="table-th coltop text-left">{PHP.L.Title}</div>
							<div class="table-th coltop text-center">{PHP.L.Sections}</div>
							<div class="table-th coltop text-center">{PHP.L.Options}</div>
							<div class="table-th coltop text-center">{PHP.L.Delete}</div>

						</div>

					</div>

					<div class="table-body resp-table-body">

						<!-- BEGIN: STRUCTURE_LIST -->

						<div class="table-row resp-table-row">

							<div class="table-td text-left resp-table-td forums-str-code" data-label="{PHP.L.Code}">
								{STRUCTURE_LIST_CODE}
							</div>
							<div class="table-td text-left resp-table-td forums-str-path" data-label="{PHP.L.Path}">
								{STRUCTURE_LIST_PATH}
							</div>
							<div class="table-td text-left resp-table-td forums-str-defstate" data-label="{PHP.L.adm_defstate}">
								{STRUCTURE_LIST_DEFSTATE}
							</div>
							<div class="table-td text-center resp-table-td forums-str-tpl" data-label="{PHP.L.TPL}">
								{STRUCTURE_LIST_TPL}
							</div>
							<div class="table-td text-left resp-table-td forums-str-title" data-label="{PHP.L.Title}">
								{STRUCTURE_LIST_TITLE}
							</div>
							<div class="table-td text-center resp-table-td forums-str-sections" data-label="{PHP.L.Sections}">
								{STRUCTURE_LIST_SECTIONCOUNT} <a href="{STRUCTURE_LIST_OPEN_URL}"><i class="ic-arrow-right"></i></a>
							</div>
							<div class="table-td text-center resp-table-td forums-str-options" data-label="{PHP.L.Options}">
								<a href="{STRUCTURE_LIST_OPTIONS_URL}"><i class="ic-settings"></i></a></a>
							</div>
							<div class="table-td text-center resp-table-td forums-str-actions">
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

		</div>

		<div class="tab-content" id="tab2">

			<form id="addstructure" action="{FN_ADD_SEND}" method="post">

				<ul class="form responsive-form">

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Code} :</label></div>
						<div class="form-field">
							{FN_ADD_CODE}
							<div class="descr">{PHP.L.adm_required}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Path} :</label></div>
						<div class="form-field">
							{FN_ADD_PATH}
							<div class="descr">{PHP.L.adm_required}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.adm_defstate} :</label></div>
						<div class="form-field">{FN_ADD_DEFSTATE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Title} :</label></div>
						<div class="form-field">
							{FN_ADD_TITLE}
							<div class="descr">{PHP.L.adm_required}</div>
						</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Description} :</label></div>
						<div class="form-field">{FN_ADD_DESC}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Icon} :</label></div>
						<div class="form-field">{FN_ADD_ICON}</div>
					</li>

				</ul>

				<div class="form-btn text-center">
					<button type="submit" class="submit btn">{PHP.L.Add}</button>
				</div>

			</form>

		</div>

	</div>

</div>

<!-- END: FORUMS_STRUCTURE -->

<!-- END: ADMIN_FORUMS -->