<!-- BEGIN: IPSEARCH -->

<h4>{PHP.L.adm_searchthisuser}:</h4>

<form id="search" action="{IPSEARCH_FORM_SEND}" method="post">
	{IPSEARCH_FORM_IPFIELD}
	<button type="submit" class="submit btn" />{PHP.L.Search}</button>
</form>

<!-- BEGIN: IPSEARCH_RESULTS -->

<p>{PHP.L.adm_dnsrecord}: {IPSEARCH_RESULT_DNS}</p>

<p>Found {IPSEARCH_RESULT_TOTALMATCHES1} matche(s) for {IPSEARCH_RESULT_IPMASK1}:</p>

<ul class="arrow_list">
	<!-- BEGIN: IPSEARCH_IPMASK1 -->
	<li>{IPSEARCH_IPMASK1}: {IPSEARCH_LASTIP_IPMASK1}</li>
	<!-- END: IPSEARCH_IPMASK1 -->
</ul>

<p>Found {IPSEARCH_RESULT_TOTALMATCHES2} match(es) for {IPSEARCH_RESULT_IPMASK2}.*:</p>

<ul class="arrow_list">
	<!-- BEGIN: IPSEARCH_IPMASK2 -->
	<li>{IPSEARCH_IPMASK2}: {IPSEARCH_LASTIP_IPMASK2}</li>
	<!-- END: IPSEARCH_IPMASK2 -->
</ul>

<p>Found {IPSEARCH_RESULT_TOTALMATCHES3} matche(s) for {IPSEARCH_RESULT_IPMASK3}.*.*:</p>

<ul class="arrow_list">
	<!-- BEGIN: IPSEARCH_IPMASK3 -->
	<li>{IPSEARCH_IPMASK3}: {IPSEARCH_LASTIP_IPMASK3}</li>
	<!-- END: IPSEARCH_IPMASK3 -->
</ul>

<!-- END: IPSEARCH_RESULTS -->

<!-- END: IPSEARCH -->