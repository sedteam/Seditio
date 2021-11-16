<!-- BEGIN: MAIN -->

<section id="slider-section">

	<div id="slider">
	
		<div class="slide-item" style="background-image: url(img/slide1.jpg);">			
			<div class="slide-body">
				<div class="slider-container">
					
					<div class="slider-content">
						<div class="slider-info">
							<div class="slider-category"><a href="/">Новости</a></div>
							<div class="slider-date">20.04.2020</div>
						</div>
						<div class="slider-title">
							<h2><a href="">Оказывается, поведение пауков изучено еще не полностью</a></h2>
						</div>
						<div class="slider-desc">
							<p>Как известно, представители рода Черных вдов отличаются своим уникальным 
							поведением, которое предвещает так называемый половой каннибализм: после 
							копуляции самка поедает самца. Североамериканские пауки Latrodectus geometricus 
							не стали исключением.</p>
						</div>
						<div class="slider-info">
							<div class="slider-author"><a href="/"><img class="slider-author-avatar" src="portfolio/1.jpg" /><span>Admin</span></a></div>
							<div class="slider-comments"><i class="ic-message-circle"></i><a href="/">0</a></div>
						</div>
					</div>												
					
				</div>	
			</div>	
		</div>
		
		<div class="slide-item" style="background-image: url(img/slide2.jpg);">			
			<div class="slide-body">
				<div class="slider-container">

					<div class="slider-content">
						<div class="slider-info">
							<div class="slider-category"><a href="/">Новости</a></div>
							<div class="slider-date">20.04.2020</div>
						</div>
						<div class="slider-title">
							<h2><a href="">Дельфины устанавливают между собой график охоты</a></h2>
						</div>
						<div class="slider-desc">
							<p>Как известно, представители рода Черных вдов отличаются своим уникальным 
							поведением, которое предвещает так называемый половой каннибализм: после 
							копуляции самка поедает самца. Североамериканские пауки Latrodectus geometricus 
							не стали исключением.</p>
						</div>
						<div class="slider-info">
							<div class="slider-author"><a href="/"><img class="slider-author-avatar" src="portfolio/1.jpg" /><span>Admin</span></a></div>
							<div class="slider-comments"><i class="ic-message-circle"></i><a href="/">0</a></div>
						</div>
					</div>	

				</div>	
			</div>					
		</div>
		
	</div>
	<div class="home-slider-arrows">
		<button class="slick-down slick-arrow" aria-label="Down" type="button" style="display: block;">Down</button>
	</div>

</section>

<main id="home">
	
	<div class="container">
	
		<div class="section-title">
			<h2>News</h2>
		</div>
		
		<div class="section-menu">
			<ul class="inline-menu">
				<li class="active"><a href="">News category</a></li>         		
				<li><a href="">News category 1</a></li>          		
				<li><a href="">News category 2</a></li>         		        		
			</ul>				
		</div>
	
		<div id="primary-container">
		
			<div id="primary">
			
				{INDEX_NEWS}
				
			</div>
			
			<aside id="sidebar">
			
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