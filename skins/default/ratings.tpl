<!-- BEGIN: RATINGS -->

<div class="block">

	{PHP.skinlang.ratings.Averagemembersrating} {RATINGS_AVERAGE}	&nbsp;&nbsp;{RATINGS_AVERAGEIMG}<br />
	{PHP.skinlang.ratings.Votes} {RATINGS_VOTERS} {RATINGS_SINCE}

</div>

<!-- BEGIN: RATINGS_NEWRATE -->

	<form action="{RATINGS_NEWRATE_FORM_SEND}" method="post" name="newrating">

	<div class="block">

		<h4>{PHP.skinlang.ratings.Rate}</h4>

		<p>
			{RATINGS_NEWRATE_FORM_RATE}
		</p>

	</div>

  <input type="submit" class="submit btn btn-big" value="{PHP.skinlang.ratings.Rateit}">

	</form>

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