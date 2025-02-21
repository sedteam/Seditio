<!-- BEGIN: IPSEARCH -->

<div class="content-box">

	<div class="content-box-header">
		<h3><i class="ic-codesandbox"></i> {PHP.L.adm_searchthisuser}</h3>
	</div>

	<div class="content-box-content">

		<form id="search" action="{IPSEARCH_FORM_SEND}" method="post">
			{IPSEARCH_FORM_IPFIELD}
			<button type="submit" class="submit btn">{PHP.L.Search}</button>
		</form>

		<!-- BEGIN: IPSEARCH_RESULTS -->

			<p>{PHP.L.adm_dnsrecord}: {IPSEARCH_RESULT_DNS}</p>

			<!-- BEGIN: IPSEARCH_IPMASK -->
			<p>Found {IPSEARCH_RESULT_TOTALMATCHES} match(es) for {IPSEARCH_RESULT_IPMASK}:</p>

			<ul class="arrow_list">
				<!-- BEGIN: IPSEARCH_IPMASK_RESULTS -->
				<li>{IPSEARCH_IPMASK}: {IPSEARCH_LASTIP_IPMASK}</li>
				<!-- END: IPSEARCH_IPMASK_RESULTS -->
			</ul>
			
			<!-- END: IPSEARCH_IPMASK -->

		<!-- END: IPSEARCH_RESULTS -->

	</div>

</div>

<!-- END: IPSEARCH -->