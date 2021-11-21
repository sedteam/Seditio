<!-- BEGIN: MAIN -->

<main id="page">
	
	<div class="container">
	
		<div class="section-title">
		
			{BREADCRUMBS}		

			<h1>{PAGE_SHORTTITLE}</h1>
			
			<div class="page-date"><i class="ic-clock"></i> {PAGE_DATE}</div>
			
		</div>
		
		<!-- BEGIN: PAGE_ADMIN -->
		<div class="section-admin">
			{PAGE_ADMIN_UNVALIDATE} &nbsp; {PAGE_ADMIN_EDIT} &nbsp; {PAGE_ADMIN_CLONE} &nbsp; ({PAGE_ADMIN_COUNT})<br />
		</div>
		<!-- END: PAGE_ADMIN -->		
	
		<div id="primary-container">
		
			<div id="primary">
						
				<div class="page">  
					
					<!-- BEGIN: PAGE_THUMB -->
					<div class="page-thumb">					
						<figure class="post-container">	  
							<a class="post-img-link" href="/">
								<img class="post-img" src="{PAGE_THUMB|resize_image(%s, 1100, 800)}" alt="{PAGE_SHORTTITLE}" />
							</a>
						</figure>						
					</div>
					<!-- END: PAGE_THUMB -->
					
					<div class="page-text">
					
						{PAGE_TEXT}
					
					</div>
					<div class="page-share">
					
						<div class="post-info">
							<div class="post-author">{PAGE_OWNER_AVATAR}{PAGE_OWNER}</div>
							<div class="post-comments"><i class="ic-message-circle"></i><a href="{PAGE_COMURL}">{PAGE_COMCOUNT}</a></div>
						</div>					
					
					</div>
					
					<!-- BEGIN: PAGE_FILE -->

					<div class="page-download">

						<a href="{PAGE_FILE_URL}">Download : {PAGE_SHORTTITLE} {PAGE_FILE_ICON}</a><br/>
						Size: {PAGE_FILE_SIZE}KB, downloaded {PAGE_FILE_COUNT} times

					</div>

					<!-- END: PAGE_FILE -->					
					
				</div>	
				
				<div class="comments"> 
				
					
				
				</div>

			</div>
			
			<aside id="sidebar">
			
			
				<div class="sidebar-box">
				
					<!-- BEGIN: SIMILARPAGES -->
					
					<div class="sidebar-title">
						<h3>Recent Items</h3>
					</div>
					
					<div class="sidebar-body">	

						<!-- BEGIN: SIMILARPAGES_ROW -->

						<div class="post-sidebar-small-item post-sidebar-first">  
							<div class="post-item">  
								<figure class="post-container">	  
									
									<!-- BEGIN: SIMILARPAGES_ROW_THUMB -->
									<a class="post-img-link" href="{SIMILARPAGES_ROW_URL}" data-page="{SIMILARPAGES_ROW_ID}" data-cat="{SIMILARPAGES_ROW_CAT}">
										<img class="post-img" src="{SIMILARPAGES_ROW_THUMB|crop_image(%s, 600, 500)}" alt="{SIMILARPAGES_ROW_TITLE}" />
									</a>
									<!-- END: SIMILARPAGES_ROW_THUMB -->

									<figcaption class="post-content">
										<div class="post-title">
											<h3><a href="{SIMILARPAGES_ROW_URL}" data-page="{SIMILARPAGES_ROW_ID}" data-cat="{SIMILARPAGES_ROW_CAT}">{SIMILARPAGES_ROW_TITLE}</a></h3>
										</div>
										<div class="post-info">
											<div class="post-author">{SIMILARPAGES_ROW_OWNER_AVATAR}{SIMILARPAGES_ROW_OWNER}</div>
											<div class="post-comments"><i class="ic-message-circle"></i><a href="{SIMILARPAGES_ROW_COMURL}">{SIMILARPAGES_ROW_COMCOUNT}</a></div>									
										</div>
									</figcaption>
									
								</figure>
							</div>	
						</div>	
						
						<!-- END: SIMILARPAGES_ROW -->
						
					</div>
					
					<!-- END: SIMILARPAGES -->					

				</div>				
			
				<div class="sidebar-box">
				
					<div class="sidebar-title">
						<h3>{PHP.skinlang.index.Activity}</h3>
					</div>
					
					<div class="sidebar-menu">
						<ul class="inline-menu tabs-nav">
							<li class="active"><a href="#tab-1">{PHP.skinlang.index.Lastcomments}</a></li>         		
							<li><a href="#tab-2">{PHP.skinlang.index.Lasttopics}</a></li> 
							<li><a href="#tab-3">{PHP.skinlang.index.Lastpages}</a></li> 							
						</ul>				
					</div>					
					
					<div id="tab-1" class="tab" style="display: block;">
					
						<!-- BEGIN: LATEST_COMMENTS -->
						
							<ul class="recent-items">
						
							<!-- BEGIN: LATEST_COMMENTS_ROW -->

								<li class="recent-item">
									<div class="recent-info">
										<div class="recent-author">{LATEST_COMMENTS_ROW_AVATAR}{LATEST_COMMENTS_ROW_AUTHORLINK}</div>
										<div class="recent-date">{LATEST_COMMENTS_ROW_DATE}</div>						 
									</div>
									<div class="recent-title">
										{LATEST_COMMENTS_ROW_LNK} {PHP.cfg.separator} {LATEST_COMMENTS_ROW_TEXT}
									</div>
								</li>							
							
							<!-- END: LATEST_COMMENTS_ROW -->
							
							</ul>
						
						<!-- END: LATEST_COMMENTS -->
					
					</div>
					
					<div id="tab-2" class="tab" style="display: none;">
					
						<!-- BEGIN: LATEST_TOPICS -->
						
							<ul class="recent-items">
						
							<!-- BEGIN: LATEST_TOPICS_ROW -->

								<li class="recent-item">
									<div class="recent-info">
										<div class="recent-author"><a href="{LATEST_TOPICS_ROW_USERURL}">{LATEST_TOPICS_ROW_AVATAR}<span>{LATEST_TOPICS_ROW_AUTHOR}</span></a></div>
										<div class="recent-date">{LATEST_TOPICS_ROW_DATE}</div>						 
									</div>
									<div class="recent-title">
										{LATEST_TOPICS_ROW_FORUMPATH} {PHP.cfg.separator} <a href="{LATEST_TOPICS_ROW_URL}">{LATEST_TOPICS_ROW_SHORTTITLE}</a> 
										<span class="recent-comments"><i class="ic-message-circle"></i><a href="{LATEST_TOPICS_ROW_URL}">{LATEST_TOPICS_ROW_TOPIC_COUNT}</a></span>
									</div>
								</li>							
							
							<!-- END: LATEST_TOPICS_ROW -->
							
							</ul>
						
						<!-- END: LATEST_TOPICS -->					
					
					</div>	
					
					<div id="tab-3" class="tab" style="display: none;">
					
						<!-- BEGIN: LATEST_PAGES -->
						
							<ul class="recent-items">
						
							<!-- BEGIN: LATEST_PAGES_ROW -->

								<li class="recent-item">
									<div class="recent-info">
										<div class="recent-author"><a href="{LATEST_PAGES_ROW_USERURL}">{LATEST_PAGES_ROW_AVATAR}<span>{LATEST_PAGES_ROW_AUTHOR}</span></a></div>
										<div class="recent-date">{LATEST_PAGES_ROW_DATE}</div>						 
									</div>
									<div class="recent-title">
										{LATEST_PAGES_ROW_CATPATH} {PHP.cfg.separator} <a href="{LATEST_PAGES_ROW_URL}">{LATEST_PAGES_ROW_SHORTTITLE}</a> 
										<span class="recent-comments"><i class="ic-message-circle"></i><a href="{LATEST_PAGES_ROW_COMMENTS_URL}">{LATEST_PAGES_ROW_COMMENTS_COUNT}</a></span>
									</div>
								</li>							
							
							<!-- END: LATEST_PAGES_ROW -->
							
							</ul>
						
						<!-- END: LATEST_PAGES -->
					
					</div>
					
				</div>
				
				<div class="sidebar-box">
				
					<div class="sidebar-title">
						<h3>{PHP.skinlang.index.Polls}</h3>
					</div>
					
					<div class="sidebar-body">	

						{PLUGIN_LATESTPOLL}

					</div>					

				</div>		

				<div class="sidebar-box">
				
					<div class="sidebar-title">
						<h3>{PHP.skinlang.index.Online}</h3>
					</div>
					
					<div class="sidebar-body">	

						<a href="{PHP.out.whosonline_link}">{PHP.out.whosonline}</a> : {PHP.out.whosonline_reg_list}

					</div>					

				</div>					
				
			</aside>

		</div>

	</div>
	
</main>
		
<!-- END: MAIN -->
