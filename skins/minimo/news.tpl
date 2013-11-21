<!-- BEGIN: NEWS -->

<div id="title"><h2>{PHP.skinlang.news.title}</h2></div>
<div id="bolded-line"></div>

	<!-- BEGIN: PAGE_ROW -->

	<div class="post">  
    <div class="post-title"><h2><a href="{PAGE_ROW_URL}">{PAGE_ROW_SHORTTITLE}</a></h2></div>
		<div class="post-meta">
			<span><i class="icons grey tags"></i> {PAGE_ROW_CATPATH}</span> <span><i class="icons grey time"></i> {PAGE_ROW_DATE}</span> <span><i class="icons grey people"></i> {PAGE_ROW_OWNER}</span> <span><i class="icons grey icomments"></i> {PAGE_ROW_COMMENTS_URL}</span>
		</div>    
    <div class="post-text">
			{PAGE_ROW_TEXT}
    </div>
	</div>

	<!-- END: PAGE_ROW -->
  
  <div class="paging">
  <ul class="pagination">
    <li class="prev">{NEWS_PAGEPREV}</li>
    {NEWS_PAGINATION}
    <li class="next">{NEWS_PAGENEXT}</li>
  </ul>
  </div>
	
<!-- END: NEWS -->