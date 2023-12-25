<!-- BEGIN: ADMIN_HOME -->

<div class="title">
	<span><i class="ic-home"></i></span>
	<h2>{ADMIN_HOME_TITLE}</h2>
</div>

<div class="row row-flex">

	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

		<div class="content-box">

			<div class="content-box-header">
				<h3>{PHP.L.Pages}</h3>
			</div>

			<div class="content-box-content">

				<h4>{PHP.L.adm_valqueue} :</h4>

				<ul class="arrow_list">
					<li>{HOME_PAGE_QUEUED}</li>
					<li>{HOME_PAGE_ADDNEWENTRY}</li>
				</ul>

			</div>

		</div>

	</div>

	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

		<div class="content-box sedtabs">

			<div class="content-box-header">
				<h3 class="tab-title">{PHP.L.Pages}</h3>
				<ul class="content-box-tabs">
					<!-- BEGIN: ADMIN_RSS_NEWS_TAB -->
					<li><a href="{PHP.sys.request_uri}#tab1" data-tabtitle="{ADMIN_RSS_NEWS_TAB_TITLE}">{ADMIN_RSS_NEWS_TAB_TITLE}</a></li>
					<!-- END: ADMIN_RSS_NEWS_TAB -->
					<!-- BEGIN: ADMIN_INFOS_TAB -->
					<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.adm_infos}">{PHP.L.adm_infos}</a></li>
					<!-- END: ADMIN_INFOS_TAB -->
					<!-- BEGIN: ADMIN_UPG_TAB -->
					<li><a href="{PHP.sys.request_uri}#tab3" data-tabtitle="{PHP.L.upg_upgrade}">{PHP.L.upg_upgrade}</a></li>
					<!-- END: ADMIN_UPG_TAB -->
				</ul>
			</div>

			<div class="content-box-content-tabs">

				<!-- BEGIN: ADMIN_RSS_NEWS_TABBODY -->
				<div class="tab-content content-table" id="tab1">
					{ADMIN_RSS_NEWS}
				</div>
				<!-- END: ADMIN_RSS_NEWS_TABBODY -->

				<!-- BEGIN: ADMIN_INFOS_TABBODY -->
				<div class="tab-content content-table" id="tab2">

					<div name="log" id="infos">

						<div class="table cells striped">
							<div class="table-body">
								<div class="table-row">
									<div class="table-td">{PHP.L.adm_phpver} :</div>
									<div class="table-td text-center">{INFOS_PHPVERSION}</div>
								</div>
								<div class="table-row">
									<div class="table-td">{PHP.L.adm_zendver}</div>
									<div class="table-td text-center">{INFOS_ZENDVERSION}</div>
								</div>
								<div class="table-row">
									<div class="table-td">{PHP.L.adm_interface} :</div>
									<div class="table-td text-center">{INFOS_INTERFACE}</div>
								</div>
								<div class="table-row">
									<div class="table-td">{PHP.L.adm_os} :</div>
									<div class="table-td text-center">{INFOS_OS}</div>
								</div>
								<div class="table-row">
									<div class="table-td">SQL :</div>
									<div class="table-td text-center">{INFOS_MYSQL}</div>
								</div>
							</div>
						</div>

					</div>

				</div>
				<!-- END: ADMIN_INFOS_TABBODY -->

				<!-- BEGIN: ADMIN_UPG_TABBODY -->
				<div class="tab-content content-table" id="tab3">

					<form id="forcesqlversion" action="{UPG_FORCESQLVERSION_SEND}" method="post">

						<div class="table cells striped">
							<div class="table-body">
								<div class="table-row">
									<div class="table-td">{PHP.L.upg_codeversion} :</div>
									<div class="table-td text-center">{UPG_VERSION}</div>
								</div>
								<div class="table-row">
									<div class="table-td">{PHP.L.upg_sqlversion} :</div>
									<div class="table-td text-center">{UPG_SQLVERSION}</div>
								</div>
								<div class="table-row">
									<div class="table-td">{UPG_CHECKSTATUS}</div>
									<div class="table-td text-center">{UPG_STATUS}</div>
								</div>
							</div>
						</div>

						<div class="table-btn text-center">
							{UPG_FORCESQL} <button type="submit" class="submit btn">{PHP.L.Update}</button>
						</div>

					</form>

				</div>
				<!-- END: ADMIN_UPG_TABBODY -->

			</div>

		</div>

	</div>

</div>

{ADMIN_QV}


<!-- END: ADMIN_HOME -->