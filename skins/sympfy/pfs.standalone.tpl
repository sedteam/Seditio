<!-- BEGIN: MAIN -->

<!-- BEGIN: PFS_STANDALONE_HEADER -->

{PFS_STANDALONE_HEADER1}
<link href="skins/{PHP.skin}/css/framework.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/fonts.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/plugins.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/cms.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/sympfy.css" type="text/css" rel="stylesheet" />
<link href="skins/{PHP.skin}/css/responsive.css" type="text/css" rel="stylesheet" />
{PFS_STANDALONE_HEADER2}

<!-- END: PFS_STANDALONE_HEADER -->

<main id="standalone">

	<div class="section-title">

		{BREADCRUMBS}

		<h1>{PFS_SHORTTITLE}</h1>

	</div>

	<div class="section-body">

		<div class="pfs-stats">
			{PFS_STATS}
		</div>

		<div class="sedtabs">

			<ul class="tabs">
				<li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Folders} & {PHP.L.Files}</a></li>
				<!-- BEGIN: PFS_UPLOAD_TAB -->
				<li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.pfs_newfile}</a></li>
				<!-- END: PFS_UPLOAD_TAB -->
				<!-- BEGIN: PFS_NEWFOLDER_TAB -->
				<li><a href="{PHP.sys.request_uri}#tab3">{PHP.L.pfs_newfolder}</a></li>
				<!-- END: PFS_NEWFOLDER_TAB -->
			</ul>

			<div class="tab-box">

				<div id="tab1" class="tabs">

					<!-- BEGIN: PFS_UPLOAD_STATUS -->

					{PFS_UPLOAD_STATUS}

					<!-- END: PFS_UPLOAD_STATUS -->

					<!-- BEGIN: PFS_FOLDERS -->

					<h4>{PFS_FOLDERS_COUNT} {PHP.L.Folders} / {PFS_FOLDERS_SUBFILES_COUNT} {PHP.L.Files} :</h4>

					<div class="table cells striped resp-table">

						<div class="table-head resp-table-head">

							<div class="table-row resp-table-row">
								<div class="table-th coltop text-left" style="width:30%;">{PHP.L.Folder}</div>
								<div class="table-th coltop text-left">{PHP.L.Type}</div>
								<div class="table-th coltop text-center">{PHP.L.Files}</div>
								<div class="table-th coltop text-center" style="width:90px;">{PHP.L.Size}</div>
								<div class="table-th coltop text-left" style="width:150px;">{PHP.L.Updated}</div>
								<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Hits}</div>
								<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Delete}</div>
								<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Edit}</div>
							</div>

						</div>

						<div class="table-body resp-table-body">

							<!-- BEGIN: PFS_LIST_FOLDERS -->

							<div class="table-row resp-table-row">
								<div class="table-td text-left resp-table-td pff-td-folder" data-label="{PHP.L.Folder}">
									<a href="{PFS_LIST_FOLDERS_URL}">{PFS_LIST_FOLDERS_TITLE}</a>
								</div>
								<div class="table-td text-left resp-table-td pff-td-type" data-label="{PHP.L.Type}">
									{PFS_LIST_FOLDERS_TYPE}
								</div>
								<div class="table-td text-center resp-table-td pff-td-files" data-label="{PHP.L.Files}">
									{PFS_LIST_FOLDERS_HITS}
								</div>
								<div class="table-td text-center resp-table-td pff-td-size" data-label="{PHP.L.Size}">
									{PFS_LIST_FOLDERS_SIZE}
								</div>
								<div class="table-td text-left resp-table-td pff-td-updates" data-label="{PHP.L.Updated}">
									{PFS_LIST_FOLDERS_UPDATE}
								</div>
								<div class="table-td text-center resp-table-td pff-td-hits" data-label="{PHP.L.Hits}">
									{PFS_LIST_FOLDERS_VIEWCOUNTS}
								</div>
								<div class="table-td text-center resp-table-td pff-td-delete">
									<!-- BEGIN: PFS_LIST_FOLDERS_DELETE_URL -->
									<a href="{PFS_LIST_FOLDERS_DELETE_URL}" class="btn-icon" title="{PHP.L.Delete}"><i class="ic-trash"></i></a>
									<!-- END: PFS_LIST_FOLDERS_DELETE_URL -->
								</div>
								<div class="table-td text-center resp-table-td pff-td-edit">
									<a href="{PFS_LIST_FOLDERS_EDIT_URL}" class="btn-icon" title="{PHP.L.Edit}"><i class="ic-pencil"></i></a>
								</div>
							</div>

							<!-- END: PFS_LIST_FOLDERS -->

						</div>

					</div>

					<!-- END: PFS_FOLDERS -->

					<!-- BEGIN: PFS_FILES -->

					<h4>{PFS_FILES_COUNT} {PHP.L.pfs_filesinthisfolder} :</h4>

					<div class="table cells striped resp-table">

						<div class="table-head resp-table-head">

							<div class="table-row resp-table-row">
								<div class="table-th coltop text-left" style="width:70px;">{PHP.L.File}</div>
								<div class="table-th coltop text-left" style="width:auto;">{PHP.L.Title}</div>
								<div class="table-th coltop text-left" style="width:90px;">{PHP.L.Size}</div>
								<div class="table-th coltop text-left" style="width:150px;">{PHP.L.Date}</div>
								<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Hits}</div>
								<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Delete}</div>
								<div class="table-th coltop text-center" style="width:30px;">{PHP.L.Edit}</div>
								<div class="table-th coltop text-center" style="width:30px;">{PHP.L.pfs_setassample}</div>
								<!-- BEGIN: PFS_STNDL_HEAD -->
								<div class="table-th coltop text-center" style="width:150px;"></div>
								<!-- END: PFS_STNDL_HEAD -->
							</div>

						</div>

						<div class="table-body resp-table-body">

							<!-- BEGIN: PFS_LIST_FILES -->

							<div class="table-row resp-table-row">

								<div class="table-td text-left resp-table-td pfs-td-icon" data-label="{PHP.L.File}">
									<!-- BEGIN: PFS_LIST_FILES_ICON -->
									{PFS_LIST_FILES_ICON}
									<!-- END: PFS_LIST_FILES_ICON -->
								</div>

								<div class="table-td text-left resp-table-td pfs-td-title" data-label="{PHP.L.Title}">
									<a href="{PFS_LIST_FILES_URL}" class="fn-copyurl" title="{PHP.L.Copyurl}" onclick="sedjs.copyurl(event);"><i class="ic-link"></i></a> {PFS_LIST_FILES_TITLE}
								</div>

								<div class="table-td text-left resp-table-td pfs-td-size" data-label="{PHP.L.Size}">
									{PFS_LIST_FILES_SIZE}
								</div>

								<div class="table-td text-left resp-table-td pfs-td-update" data-label="{PHP.L.Date}">
									{PFS_LIST_FILES_UPDATE}
								</div>

								<div class="table-td text-center resp-table-td pfs-td-viewcounts" data-label="{PHP.L.Hits}">
									{PFS_LIST_FILES_VIEWCOUNTS}
								</div>

								<div class="table-td text-center resp-table-td pfs-td-delete">
									<a href="{PFS_LIST_FILES_DELETE_URL}" class="btn-icon" title="{PHP.L.Delete}"><i class="ic-trash"></i></a>
								</div>

								<div class="table-td text-center resp-table-td pfs-td-edit">
									<a href="{PFS_LIST_FILES_EDIT_URL}" class="btn-icon" title="{PHP.L.Edit}"><i class="ic-pencil"></i></a>
								</div>

								<div class="table-td text-center resp-table-td pfs-td-setasample">
									{PFS_LIST_FILES_SETASSAMPLE}
								</div>

								<!-- BEGIN: PFS_LIST_FILES_STNDL -->
								<div class="table-td text-center resp-table-td pfs-td-stndl">
									{PFS_LIST_FILES_STNDL}
								</div>
								<!-- END: PFS_LIST_FILES_STNDL -->

							</div>

							<!-- END: PFS_LIST_FILES -->

						</div>

					</div>

					<!-- END: PFS_FILES -->

					<!-- BEGIN: PFS_EMPTY -->
					<div class="pfs-empty">
						{PHP.L.pfs_filelistempty}
					</div>
					<!-- END: PFS_EMPTY -->

					<!-- BEGIN: PFS_HELP -->
					<div class="pfs-help">
						<h5>{PHP.L.Help} :</h5>
						{PFS_HELP}
					</div>
					<!-- END: PFS_HELP -->

				</div>

				<!-- BEGIN: PFS_UPLOAD -->
				<div id="tab2" class="tabs">

					<h4>{PHP.L.pfs_newfile}</h4>

					<form enctype="multipart/form-data" action="{PFS_UPLOAD_SEND}" method="post">

						{PFS_UPLOAD_MAXFILESIZE}

						{PHP.L.Folder} : {PFS_UPLOAD_FOLDERS}

						<!-- BEGIN: PFS_UPLOAD_IMG_RESIZE -->
						{PFS_UPLOAD_IMG_RESIZE_SIZE} : {PFS_UPLOAD_IMG_RESIZE}
						<!-- END: PFS_UPLOAD_IMG_RESIZE -->

						<!-- BEGIN: PFS_UPLOAD_ADD_LOGO -->
						{PHP.L.pfs_addlogo} : {PFS_UPLOAD_ADD_LOGO}
						<!-- END: PFS_UPLOAD_ADD_LOGO -->

						<div class="table cells striped resp-table">

							<div class="table-head resp-table-head">

								<div class="table-row resp-table-row">
									<div class="table-th coltop text-center" style="width:30px;">&nbsp;</div>
									<div class="table-th coltop text-left">{PHP.L.Title}</div>
									<div class="table-th coltop text-left">{PHP.L.File}</div>
								</div>

							</div>

							<div class="table-body resp-table-body">

								<!-- BEGIN: PFS_UPLOAD_LIST -->

								<div class="table-row resp-table-row">

									<div class="table-td text-left resp-table-td">
										#{PFS_UPLOAD_LIST_NUM}
									</div>

									<div class="table-td text-left resp-table-td">
										{PFS_UPLOAD_LIST_TITLE}
									</div>

									<div class="table-td text-left resp-table-td">
										{PFS_UPLOAD_LIST_FILE}
										<!-- BEGIN: PFS_UPLOAD_MORE -->
										<a href="{PFS_UPLOAD_MORE_URL}">{PFS_UPLOAD_MORE_ICON}</a>
										<!-- END: PFS_UPLOAD_MORE -->
									</div>

								</div>

								<!-- END: PFS_UPLOAD_LIST -->

							</div>

							<div class="table-body resp-table-body" id="moreuploads" style="display:none;">

								<!-- BEGIN: PFS_UPLOAD_MORE_LIST -->

								<div class="table-row resp-table-row">

									<div class="table-td text-left resp-table-td">
										{PFS_UPLOAD_LIST_NUM}
									</div>

									<div class="table-td text-left resp-table-td">
										{PFS_UPLOAD_LIST_TITLE}
									</div>

									<div class="table-td text-left resp-table-td">
										{PFS_UPLOAD_LIST_FILE}
										<!-- BEGIN: PFS_UPLOAD_MORE -->
										<a href="{PFS_UPLOAD_MORE_URL}" class="btn-icon">{PFS_UPLOAD_MORE_ICON}</a>
										<!-- END: PFS_UPLOAD_MORE -->
									</div>

								</div>

								<!-- END: PFS_UPLOAD_MORE_LIST -->

							</div>

							<div class="table-body resp-table-body">

								<div class="table-row resp-table-row">

									<div class="table-td text-left resp-table-td">

									</div>

									<div class="table-td text-right resp-table-td">
										{PHP.L.pfs_multiuploading}
									</div>

									<div class="table-td text-left resp-table-td">
										{PFS_UPLOAD_MULTIPLE}
									</div>

								</div>

							</div>

						</div>

						<div class="centered">
							<button type="submit" class="submit btn btn-big">{PHP.L.Upload}</button>
						</div>

					</form>

					<div class="pfs-allow">
						<h5>{PHP.L.pfs_extallowed}</h5>
						{PFS_ALLOWED_EXT}
					</div>

				</div>
				<!-- END: PFS_UPLOAD -->

				<!-- BEGIN: PFS_NEWFOLDER -->
				<div id="tab3" class="tabs">

					<h4>{PHP.L.pfs_newfolder}</h4>

					<form id="newfolder" action="{PFS_NEWFOLDER_SEND}" method="post">

						<ul class="form responsive-form">

							<li class="form-row">
								<div class="form-label"><label>{PHP.L.Title}</label></div>
								<div class="form-field">{PFS_NEWFOLDER_TITLE}</div>
							</li>

							<li class="form-row">
								<div class="form-label"><label>{PHP.L.Description}</label></div>
								<div class="form-field">{PFS_NEWFOLDER_DESC}</div>
							</li>

							<li class="form-row">
								<div class="form-label"><label>{PHP.L.Type}</label></div>
								<div class="form-field">{PFS_NEWFOLDER_TYPE}</div>
							</li>

						</ul>

						<div class="centered">
							<button type="submit" class="submit btn btn-big">{PHP.L.Create}</button>
						</div>

					</form>

				</div>
				<!-- END: PFS_NEWFOLDER -->

			</div>

		</div>

	</div>

</main>

<!-- BEGIN: PFS_STANDALONE_FOOTER -->

<script src="skins/{PHP.skin}/js/jquery.min.js"></script>
<script src="skins/{PHP.skin}/js/jquery.plugins.min.js"></script>
<script src="skins/{PHP.skin}/js/app.js"></script>

{PFS_STANDALONE_FOOTER}

<!-- END: PFS_STANDALONE_FOOTER -->

<!-- END: MAIN -->