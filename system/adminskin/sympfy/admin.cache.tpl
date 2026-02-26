<!-- BEGIN: ADMIN_CACHE -->

<div class="title">
	<span><i class="ic-cache"></i></span>
	<h2>{PHP.L.adm_internalcache}</h2>
</div>

<ul class="arrow_list">
	<li><a href="{CACHE_REFRESH_URL}">{PHP.L.Refresh}</a></li>
	<li><a href="{CACHE_PURGE_URL}">{PHP.L.adm_purgeall}</a></li>
	<li><a href="{CACHE_SHOWALL_URL}">{PHP.L.adm_showall}</a></li>
</ul>

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.adm_internalcache}</h3>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left">{PHP.L.Item}</div>
					<div class="table-th coltop text-left">{PHP.L.Expire}</div>
					<div class="table-th coltop text-left">{PHP.L.Size}</div>
					<div class="table-th coltop text-left">{PHP.L.Value}</div>
					<div class="table-th coltop text-center">{PHP.L.Delete}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<!-- BEGIN: CACHE_LIST -->

				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td cache-title" data-label="{PHP.L.Item}">
						{CACHE_LIST_NAME}
					</div>
					<div class="table-td text-left resp-table-td cache-expire" data-label="{PHP.L.Expire}">
						{CACHE_LIST_EXPIRE}
					</div>
					<div class="table-td text-left resp-table-td cache-size" data-label="{PHP.L.Size}">
						{CACHE_LIST_SIZE}
					</div>
					<div class="table-td text-left resp-table-td cache-value" data-label="{PHP.L.Value}">
						{CACHE_LIST_VALUE}
					</div>
					<div class="table-td text-center resp-table-td cache-delete" style="width:50px;">
						<a href="{CACHE_LIST_DELETE_URL}" class="btn btn-small"><i class="ic-trash"></i></a>
					</div>
				</div>

				<!-- END: CACHE_LIST -->

				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td cache-delete"></div>
					<div class="table-td text-left resp-table-td cache-title"></div>
					<div class="table-td text-left resp-table-td cache-expire"></div>
					<div class="table-td text-left resp-table-td cache-size" data-label="All {PHP.L.Size}">
						{CACHE_SIZE}
					</div>
					<div class="table-td text-left resp-table-td cache-value"></div>
				</div>

			</div>

		</div>

	</div>

</div>

<div class="content-box">

	<div class="content-box-header">
		<h3>{PHP.L.adm_urlcache}</h3>
	</div>

	<div class="content-box-content content-table">

		<div class="table cells striped resp-table">

			<div class="table-head resp-table-head">
				<div class="table-row resp-table-row">
					<div class="table-th coltop text-left">{PHP.L.adm_urlcache_file}</div>
					<div class="table-th coltop text-left">{PHP.L.Date}</div>
					<div class="table-th coltop text-left">{PHP.L.Size}</div>
					<div class="table-th coltop text-center">{PHP.L.Options}</div>
				</div>
			</div>

			<div class="table-body resp-table-body">

				<div class="table-row resp-table-row">
					<div class="table-td text-left resp-table-td" data-label="{PHP.L.adm_urlcache_file}">
						sed_urls.php
					</div>
					<div class="table-td text-left resp-table-td" data-label="{PHP.L.Date}">
						{URLCACHE_DATE}
					</div>
					<div class="table-td text-left resp-table-td" data-label="{PHP.L.Size}">
						{URLCACHE_SIZE}
					</div>
					<div class="table-td text-center resp-table-td" style="width:120px;" data-label="{PHP.L.Options}">
						<a href="{URLCACHE_REGENERATE_URL}" class="btn btn-small" title="{PHP.L.adm_urlcache_regenerate}"><i class="ic-refresh"></i></a>
						<a href="{URLCACHE_DELETE_URL}" class="btn btn-small" title="{PHP.L.adm_urlcache_delete}"><i class="ic-trash"></i></a>
					</div>
				</div>

			</div>

		</div>

	</div>

</div>

<!-- END: ADMIN_CACHE -->