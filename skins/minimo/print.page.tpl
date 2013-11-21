<!-- BEGIN: PRINT --><!-- BEGIN: HEADER -->{PRINT_HEADER_DOCTYPE}
<html>
<head>
{PRINT_HEADER_METAS}
<title>{PRINT_HEADER_TITLE} - {PHP.L.Printversion}</title>
<link href="skins/{PHP.skin}/{PHP.skin}.print.css" type="text/css" rel="stylesheet" />
<link rel="canonical" href="{PRINT_HEADER_CANONICAL_URL}" />
</head>
<body>
<!-- END: HEADER -->

<div id="print">

<div id="header">

<div class="leftcolumn">

			<div id="logo">
				<a href="/" class="logos"><img src="skins/{PHP.skin}/img/logos.png" /></a>
				<div id="slogan">It's time will come soon!</div>
			</div>

</div>

<div class="rightcolumn">
     <input type="button" class="submit btn btn-big" value="{PHP.L.Print}" onClick="this.style.display='none'; window.print()" />
</div>

</div>

<div id="title"><h2>{PRINT_PAGE_TITLE}</h2></div>
<div id="bolded-line"></div>

<div id="subtitle">
	{PRINT_PAGE_DESC}<br />
	{PHP.skinlang.page.Submittedby} {PRINT_PAGE_OWNER} | {PHP.skinlang.page.Date} {PRINT_PAGE_DATE}<br />

	<!-- BEGIN: PRINT_PAGE_ADMIN -->

	{PRINT_PAGE_ADMIN_UNVALIDATE} | {PRINT_PAGE_ADMIN_EDIT} ({PRINT_PAGE_ADMIN_COUNT})<br />

	<!-- END: PRINT_PAGE_ADMIN -->
</div>

<div id="page">
               
	{PRINT_PAGE_TEXT}

	<!-- BEGIN: PRINT_PAGE_MULTI -->

		<div class="paging">
		   <ul class="pagination">
			<li class="prev">{PRINT_PAGE_MULTI_PREV}</li>
			{PRINT_PAGE_MULTI_TABNAV}
			<li class="next">{PRINT_PAGE_MULTI_NEXT}</li>
		  </ul>
		  {PRINT_PAGE_MULTI_SELECT} 
		</div>

		<div class="block">
			<h5>{PHP.skinlang.page.Summary}</h5>
			{PRINT_PAGE_MULTI_TABTITLES}
		</div>

	<!-- END: PRINT_PAGE_MULTI -->

	<!-- BEGIN: PRINT_PAGE_FILE -->

		<div class="download">

			<a href="{PRINT_PAGE_FILE_URL}">Download : {PRINT_PAGE_SHORTTITLE} {PRINT_PAGE_FILE_ICON}</a><br/>
			Size: {PRINT_PAGE_FILE_SIZE}KB, downloaded {PRINT_PAGE_FILE_COUNT} times

		</div>

	<!-- END: PRINT_PAGE_FILE -->

</div>


<!-- BEGIN: FOOTER -->
<div id="footer">

  {PRINT_FOOTER_BOTTOMLINE}<br />
  {PRINT_FOOTER_COPYRIGHT}

</div>

</div>

</body>
</html>
<!-- END: FOOTER -->

<!-- END: PRINT -->