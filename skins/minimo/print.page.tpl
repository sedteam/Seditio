<!-- BEGIN: MAIN -->{PHP.cfg.doctype}
<html>
<head>
<title>{PAGE_SHORTTITLE} - {PHP.L.Printversion}</title>
<base href="{PHP.sys.abs_url}" />
<link href="skins/{PHP.skin}/{PHP.skin}.print.css" type="text/css" rel="stylesheet" />
<link rel="canonical" href="{PHP.out.canonical_url}" />
</head>
<body>

<div id="print">

<div id="header">

<div class="leftcolumn">

			<div id="logo">
				<a href="/" class="logos"><img src="skins/{PHP.skin}/img/logo.png" /></a>
				<div id="slogan">It's time will come soon!</div>
			</div>

</div>

<div class="rightcolumn">
     <input type="button" class="submit btn btn-big" value="{PHP.L.Print}" onClick="this.style.display='none'; window.print()" />
</div>

</div>

<div id="title">

	<h2>{PAGE_TITLE}</h2>

</div>

<div id="subtitle">
	{PAGE_DESC}
	<div class="clear">
	{PHP.skinlang.page.Submittedby} {PAGE_OWNER} &nbsp; {PHP.skinlang.page.Date} {PAGE_DATE}<br />

	<!-- BEGIN: PAGE_ADMIN -->

	{PAGE_ADMIN_UNVALIDATE} &nbsp; {PAGE_ADMIN_EDIT} &nbsp; {PAGE_ADMIN_CLONE} &nbsp; ({PAGE_ADMIN_COUNT})<br />

	<!-- END: PAGE_ADMIN -->
	</div>
</div>

<div id="page">
               
	{PAGE_TEXT}

	<!-- BEGIN: PAGE_MULTI -->

		<div class="paging">
		   <ul class="pagination">
			<li class="prev">{PAGE_MULTI_PREV}</li>
			{PAGE_MULTI_TABNAV}
			<li class="next">{PAGE_MULTI_NEXT}</li>
		  </ul>
		  {PAGE_MULTI_SELECT} 
		</div>

		<div class="block">
			<h5>{PHP.skinlang.page.Summary}</h5>
			{PAGE_MULTI_TABTITLES}
		</div>

	<!-- END: PAGE_MULTI -->

	<!-- BEGIN: PAGE_FILE -->

		<div class="download">

			<a href="{PAGE_FILE_URL}">Download : {PAGE_SHORTTITLE} {PAGE_FILE_ICON}</a><br />
			Size: {PAGE_FILE_SIZE}KB, downloaded {PAGE_FILE_COUNT} times

		</div>

	<!-- END: PAGE_FILE -->

</div>

<div id="footer">

  {PHP.out.bottomline}<br />
  {PHP.out.copyright}

</div>

</div>

</body>
</html>
<!-- END: MAIN -->