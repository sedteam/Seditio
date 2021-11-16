<!-- BEGIN: MAIN -->

<main id="forums">
	
	<div class="container">
	
		<div class="section-title">
		
			{BREADCRUMBS}	

			<h1>{FORUMS_EDITPOST_SHORTTITLE}</h1>
			<div class="section-desc">
				{FORUMS_EDITPOST_SUBTITLE}
			</div>
			
		</div>

		<div class="section-body">

			<form action="{FORUMS_EDITPOST_SEND}" method="post" name="editpost">

				<!-- BEGIN: FORUMS_EDITPOST_ERROR -->

				<div class="error">
					{FORUMS_POSTS_EDITPOST_ERROR_BODY}
				</div>

				<!-- END: FORUMS_EDITPOST_ERROR -->

				<ul class="form responsive-form">

					<!-- BEGIN: FORUMS_EDITPOST_FIRST -->

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.forumsnewtopic.Title}</label></div>
						<div class="form-field">{FORUMS_EDITPOST_TITLE}</div>
					</li>
						
					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.forumsnewtopic.Desc}</label></div>
						<div class="form-field">{FORUMS_EDITPOST_DESC}</div>
					</li>			

					<!-- END: FORUMS_EDITPOST_FIRST -->
				  
					<li class="form-row">
						<div class="form-field-100">
							{FORUMS_EDITPOST_TEXT}		
						</div>
					</li>
					
					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn">{PHP.skinlang.forumseditpost.Update}</button>		
						</div>
					</li>						

				</ul>

			</form>

		</div>
		
	</div>
	
</main>

<!-- END: MAIN -->