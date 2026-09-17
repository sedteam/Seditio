<!-- BEGIN: ADMIN_CONFIG_LANG -->

<div class="content-box">
	<div class="content-box-header">
		<h3>{PHP.L.Default} {PHP.L.core_lang}</h3>
	</div>
	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left w-lang-code">{PHP.L.adm_translations_lang_code}</div>
					<div class="table-th coltop text-left">{PHP.L.adm_translations_lang_title}</div>
					<div class="table-th coltop text-left">{PHP.L.adm_translations_lang_native}</div>
					<div class="table-th coltop text-center w-lang-direction">{PHP.L.adm_translations_lang_direction}</div>
					<div class="table-th coltop text-center w-lang-active">{PHP.L.Active}</div>
					<div class="table-th coltop text-center w-lang-default">{PHP.L.Default}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: LANG_LIST -->

				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_translations_lang_code}">
						{LANG_LIST_FLAG} <a href="{LANG_LIST_TRANSLATIONS_URL}"><strong>{LANG_LIST_CODE}</strong></a>
					</div>
					<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_translations_lang_title}">
						<a href="{LANG_LIST_TRANSLATIONS_URL}">{LANG_LIST_TITLE}</a>
					</div>
					<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_translations_lang_native}">
						{LANG_LIST_NATIVE}
					</div>
					<div class="table-td text-center resp-table-td" data-label="{PHP.L.adm_translations_lang_direction}">
						{LANG_LIST_DIRECTION}
					</div>
					<div class="table-td text-center resp-table-td" data-label="{PHP.L.Active}">
						{LANG_LIST_ACTIVE}
					</div>
					<div class="table-td text-center resp-table-td" data-label="{PHP.L.Default}">
						{LANG_LIST_DEFAULT}
					</div>
				</div>

				<!-- END: LANG_LIST -->

			</div>

		</div>

	</div>
</div>

<!-- END: ADMIN_CONFIG_LANG -->