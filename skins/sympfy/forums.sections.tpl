<!-- BEGIN: MAIN -->

<main id="forums">
	
	<div class="container">
	
		<div class="section-title">
		
			{BREADCRUMBS}	

			<h1>{FORUMS_SECTIONS_SHORTTITLE}</h1>
			<div class="section-desc"></div>
			
		</div>

		<div class="section-body">

			<nav class="nav-pills">
				<ul>
					<li><a href="{FORUMS_SECTIONS_SEARCH}">{PHP.skinlang.forumssections.Searchinforums}</a></li>
					<li>{FORUMS_SECTIONS_MARKALL}</li>
					<li><span>{FORUMS_SECTIONS_GMTTIME}</span></li>
				</ul>
			</nav>

			<div class="table-cells forums-table">

				<div class="table-thead">
					<div class="table-td coltop"></div>
					<div class="table-td coltop">{PHP.skinlang.forumssections.Sections}  &nbsp;  &nbsp; <a href="{FORUMS_SECTIONS_FOLDALL}">{PHP.skinlang.forumssections.FoldAll}</a> / <a href="{FORUMS_SECTIONS_UNFOLDALL}">{PHP.skinlang.forumssections.UnfoldAll}</a></div>
					<div class="table-td coltop" style="width:250px;">{PHP.skinlang.forumssections.Lastpost}</div>
					<div class="table-td coltop" style="width:48px;">{PHP.skinlang.forumssections.Topics}</div>
					<div class="table-td coltop" style="width:48px;">{PHP.skinlang.forumssections.Posts}</div>
					<div class="table-td coltop" style="width:48px;">{PHP.skinlang.forumssections.Views}</div>
					<div class="table-td coltop" style="width:48px;">{PHP.skinlang.forumssections.Activity}</div>
				</div>

				<!-- BEGIN: FORUMS_SECTIONS_ROW -->

				<div class="table-tr cattop-tr">
					<div class="table-colspan-100 cattop">				
						<strong>{FORUMS_SECTIONS_ROW_CAT_TITLE}</strong>
					</div>
				</div>

				<div class="table-tbody" {FORUMS_SECTIONS_ROW_CAT_TOGGLE}>

				<!-- BEGIN: FORUMS_SECTIONS_ROW_SECTION -->

				<div class="table-tr">
				
					<div class="table-td" style="width:32px;">
						<img src="{FORUMS_SECTIONS_ROW_ICON}" alt="" />
					</div>

					<div class="table-td">
						<h4><a href="{FORUMS_SECTIONS_ROW_URL}">{FORUMS_SECTIONS_ROW_TITLE}</a></h4>
						<div class="desc">{FORUMS_SECTIONS_ROW_DESC}</div>
						
						<!-- BEGIN: FORUMS_SECTIONS_ROW_SUBFORUMS -->
						<div class="subforums">
							<ul class="subforums-list">	
								<!-- BEGIN: FORUMS_SECTIONS_ROW_SUBFORUMS_LIST -->
								<li><a href="{FORUMS_SECTIONS_ROW_SUBFORUMS_URL}">{FORUMS_SECTIONS_ROW_SUBFORUMS_TITLE}</a></li>	
								<!-- END: FORUMS_SECTIONS_ROW_SUBFORUMS_LIST -->
							</ul>    
						</div>
						<!-- END: FORUMS_SECTIONS_ROW_SUBFORUMS -->	
						
					</div>

					<div class="table-td">
						{FORUMS_SECTIONS_ROW_LASTPOST}<br />
						{FORUMS_SECTIONS_ROW_LASTPOSTDATE} {FORUMS_SECTIONS_ROW_LASTPOSTER}<br />
						{FORUMS_SECTIONS_ROW_TIMEAGO}
					</div>

					<div class="table-td">
						{FORUMS_SECTIONS_ROW_TOPICCOUNT_ALL}<br />
						<span class="desc">({FORUMS_SECTIONS_ROW_TOPICCOUNT})</span>
					</div>

					<div class="table-td">
						{FORUMS_SECTIONS_ROW_POSTCOUNT_ALL}<br />
						<span class="desc">({FORUMS_SECTIONS_ROW_POSTCOUNT})</span>
					</div>

					<div class="table-td">
						{FORUMS_SECTIONS_ROW_VIEWCOUNT_SHORT}
					</div>

					<div class="table-td">
						{FORUMS_SECTIONS_ROW_ACTIVITY}
					</div>

				</div>		

				<!-- END: FORUMS_SECTIONS_ROW_SECTION -->
				
				</div>

				<!-- END: FORUMS_SECTIONS_ROW -->

			</div>

		</div>
		
	</div>	

</main>

<!-- END: MAIN -->