<!-- BEGIN: ADMIN_FORUMS -->

<div class="title">
	<span><i class="ic-forums"></i></span>
	<h2>{ADMIN_FORUMS_TITLE}</h2>
</div>

<!-- BEGIN: FORUMS_BUTTONS -->

<ul class="shortcut-buttons-set">

	<!-- BEGIN: FORUMS_BUTTONS_CONFIG -->
	<li><a class="shortcut-button" href="{BUTTON_FORUMS_CONFIG_URL}"><span>
				<i class="ic-settings ic-3x"></i><br />
				{PHP.L.Configuration}
			</span></a></li>
	<!-- END: FORUMS_BUTTONS_CONFIG -->

	<!-- BEGIN: FORUMS_BUTTONS_STRUCTURE -->
	<li><a class="shortcut-button" href="{BUTTON_FORUMS_STRUCTURE_URL}"><span>
				<i class="ic-manual-gearbox ic-3x"></i><br />
				{PHP.L.Structure}
			</span></a></li>
	<!-- END: FORUMS_BUTTONS_STRUCTURE -->

</ul>

<div class="clear"></div>

<!-- END: FORUMS_BUTTONS -->

<!-- BEGIN: FS_UPDATE -->

<div class="content-box">

	<div class="content-box-header">
		<h3>{FS_UPDATE_FORM_TITLE}</h3>
	</div>

	<div class="content-box-content content-table">

		<form id="updatesection" action="{FS_UPDATE_SEND}" method="post">

			<ul class="form responsive-form">

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Section} :</label></div>
					<div class="form-field">{FS_UPDATE_ID}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_parentcat} :</label></div>
					<div class="form-field">{FS_UPDATE_PARENTCAT}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Category} :</label></div>
					<div class="form-field">{FS_UPDATE_CATEGORY}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Title} :</label></div>
					<div class="form-field">{FS_UPDATE_TITLE}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Description} :</label></div>
					<div class="form-field">
						<div>{FS_UPDATE_DESC}</div>
					</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Icon} :</label></div>
					<div class="form-field">{FS_UPDATE_ICON}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_diplaysignatures} :</label></div>
					<div class="form-field">{FS_UPDATE_ALLOWUSERTEXT}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_enablebbcodes} :</label></div>
					<div class="form-field">{FS_UPDATE_ALLOWBBCODES}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_enablesmilies} :</label></div>
					<div class="form-field">{FS_UPDATE_ALLOWSMILIES}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_enableprvtopics} :</label></div>
					<div class="form-field">{FS_UPDATE_ALLOWPRIVATETOPICS}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_countposts} :</label></div>
					<div class="form-field">{FS_UPDATE_COUNTPOST}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Locked} :</label></div>
					<div class="form-field">{FS_UPDATE_STATE}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_autoprune} :</label></div>
					<div class="form-field">{FS_UPDATE_AUTOPRUNE}</div>
				</li>

				<!-- BEGIN: FS_ADMIN -->

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.adm_postcounters} :</label></div>
					<div class="form-field">{FS_UPDATE_RESYNC}</div>
				</li>

				<li class="form-row">
					<div class="form-label"><label>{PHP.L.Delete} :</label></div>
					<div class="form-field">{FS_UPDATE_DELETE}</div>
				</li>

				<!-- END: FS_ADMIN -->

			</ul>
			<div class="form-btn text-center">
				<button type="submit" class="submit btn">{PHP.L.Update}</button>
			</div>

		</form>

	</div>

</div>

<!-- END: FS_UPDATE -->

<!-- BEGIN: FS_CAT -->

<div class="content-box">

	<div class="sedtabs">

		<div class="content-box-header">
			<h3 class="tab-title">{PHP.L.adm_forum_structure_cat}</h3>
			<ul class="content-box-tabs">
				<li><a href="{PHP.sys.request_uri}#tab1" class="selected" data-tabtitle="{PHP.L.adm_forum_structure_cat}">{PHP.L.adm_forum_structure_cat}</a></li>
				<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.addnewentry}">{PHP.L.addnewentry}</a></li>
			</ul>
		</div>

		<div class="content-box-content content-table">

			<div class="tab-content default-tab" id="tab1">

				<form id="updateorder" action="{FS_CAT_SEND}" method="post">

					<div class="table cells striped resp-table">

						<div class="table-head resp-table-head">

							<div class="table-row resp-table-row">
								<div class="table-th coltop text-left">{PHP.L.Section}</div>
								<div class="table-th coltop text-center">{PHP.L.Order}</div>
								<div class="table-th coltop text-center">{PHP.L.adm_enableprvtopics}</div>
								<div class="table-th coltop text-center" style="width:48px;">{PHP.L.Topics}</div>
								<div class="table-th coltop text-center" style="width:48px;">{PHP.L.Posts}</div>
								<div class="table-th coltop text-center" style="width:48px;">{PHP.L.Views}</div>
								<div class="table-th coltop text-center" style="width:80px;">{PHP.L.Rights}</div>
								<div class="table-th coltop text-center" style="width:64px;">{PHP.L.Open}</div>
							</div>

						</div>

						<div class="table-body resp-table-body">

							<!-- BEGIN: FS_LIST -->

							<!-- BEGIN: FN_CAT -->

							<div class="table-row resp-table-row">

								<div class="table-td text-left resp-table-td"><a href="{FN_CAT_URL}">{FN_CAT_TITLE} ({FN_CAT_PATH})</a></div>
								<div class="table-td text-center resp-table-td resp-hide"></div>
								<div class="table-td text-center resp-table-td resp-hide"></div>
								<div class="table-td text-center resp-table-td resp-hide"></div>
								<div class="table-td text-center resp-table-td resp-hide"></div>
								<div class="table-td text-center resp-table-td resp-hide"></div>
								<div class="table-td text-center resp-table-td resp-hide"></div>
								<div class="table-td text-center resp-table-td resp-hide"></div>
							</div>

							<!-- END: FN_CAT -->

							<div class="table-row resp-table-row">

								<div class="table-td text-left resp-table-td forums-title" data-label="{PHP.L.Section}">
									{FS_LIST_TITLE}
								</div>
								<div class="table-td text-center resp-table-td forums-order" data-label="{PHP.L.Order}">
									<a href="{FS_LIST_ORDER_UP_URL}">{PHP.cfg.arrow_up}</a><a href="{FS_LIST_ORDER_DOWN_URL}">{PHP.cfg.arrow_down}</a>
								</div>
								<div class="table-td text-center resp-table-td forums-prvtopics" data-label="{PHP.L.adm_enableprvtopics}">
									{FS_LIST_ALLOWPRIWATETOPICS}
								</div>
								<div class="table-td text-center resp-table-td forums-topics" data-label="{PHP.L.Topics}">
									{FS_LIST_TOPICCOUNT}
								</div>
								<div class="table-td text-center resp-table-td forums-posts" data-label="{PHP.L.Posts}">
									{FS_LIST_POSTCONT}
								</div>
								<div class="table-td text-center resp-table-td forums-views" data-label="{PHP.L.Views}">
									{FS_LIST_VIEWCOUNT}
								</div>
								<div class="table-td text-center resp-table-td forums-rights" data-label="{PHP.L.Rights}">
									<a href="{FS_LIST_RIGHTS_URL}"><i class="ic-lock"></i></a>
								</div>
								<div class="table-td text-center resp-table-td forums-open" data-label="{PHP.L.Open}">
									<a href="{FS_LIST_OPEN_URL}"><i class="ic-arrow-right"></i></a>
								</div>

							</div>

							<!-- END: FS_LIST -->

						</div>

					</div>

					<div class="table-btn text-center">
						<button type="submit" class="submit btn">{PHP.L.Update}</button>
					</div>

				</form>

			</div>

			<div class="tab-content" id="tab2">

				<form id="addsection" action="{FS_ADD_SEND}" method="post">

					<ul class="form responsive-form">

						<li class="form-row">
							<div class="form-label"><label>{PHP.L.Category} :</label></div>
							<div class="form-field">{FS_ADD_CATEGORY}</div>
						</li>

						<li class="form-row">
							<div class="form-label"><label>{PHP.L.Title} :</label></div>
							<div class="form-field">
								<div class="field">{FS_ADD_TITLE}</div>
								<div class="descr">{PHP.L.adm_required}</div>
							</div>
						</li>

						<li class="form-row">
							<div class="form-label"><label>{PHP.L.Description} :</label></div>
							<div class="form-field">{FS_ADD_DESC}</div>
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

</div>

<!-- END: FS_CAT -->

<!-- END: ADMIN_FORUMS -->