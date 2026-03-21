<!-- BEGIN: RATINGS -->
<div class="block">

	{PHP.skinlang.ratings.Averagemembersrating} {RATINGS_AVERAGE} &nbsp;&nbsp;{RATINGS_AVERAGEIMG}<br />
	{PHP.skinlang.ratings.Votes} {RATINGS_VOTERS} {RATINGS_SINCE}

</div>

<!-- BEGIN: RATINGS_NEWRATE -->

<!-- BEGIN: RATINGS_NEWRATE_AJAXMODE -->
<div class="rating-box" id="rat-{RATINGS_CODE}">
	<div class="star-rating-widget ratings-star-widget jq-stars" data-code="{RATINGS_CODE}" data-ajax-url="{RATINGS_AJAX_URL}" data-gradient-start="{RATINGS_GRADIENT_START}" data-gradient-end="{RATINGS_GRADIENT_END}" data-labels-json='{RATINGS_LABELS_JSON}' {RATINGS_WIDGET_ATTRS}></div>
</div>
<!-- END: RATINGS_NEWRATE_AJAXMODE -->

<!-- BEGIN: RATINGS_NEWRATE_FORMMODE -->
<form action="{RATINGS_NEWRATE_FORM_SEND}" method="post" name="newrating" data-gradient-start="{RATINGS_GRADIENT_START}" data-gradient-end="{RATINGS_GRADIENT_END}">

	<div class="block">

		<h4>{PHP.skinlang.ratings.Rate}</h4>

		<p>
			<div class="star-rating-widget ratings-star-widget jq-stars" data-input-id="newrate-{RATINGS_CODE}" data-labels-json='{RATINGS_LABELS_JSON}' {RATINGS_WIDGET_ATTRS}></div>
			<input type="hidden" name="newrate" id="newrate-{RATINGS_CODE}" value="0">
		</p>

	</div>

	<!-- BEGIN: RATINGS_SUBMIT_BTN -->
	<input type="submit" class="submit btn btn-big" value="{PHP.skinlang.ratings.Rateit}">
	<!-- END: RATINGS_SUBMIT_BTN -->

</form>
<!-- END: RATINGS_NEWRATE_FORMMODE -->

<!-- END: RATINGS_NEWRATE -->

<!-- BEGIN: RATINGS_EXTRA -->

<div class="block">

	{RATINGS_EXTRATEXT}

</div>

<!-- END: RATINGS_EXTRA -->

<!-- BEGIN: RATINGS_DISABLE -->

<div class="block">

	{RATINGS_DISABLETEXT}

</div>

<!-- END: RATINGS_DISABLE -->

<!-- END: RATINGS -->
