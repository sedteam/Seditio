<!-- BEGIN: MAIN -->

<main id="forums">
	
	<div class="container">
	
		<div class="section-title">
		
			{BREADCRUMBS}	

			<h1>{FORUMS_NEWTOPIC_SHORTTITLE}</h1>
			
			<div class="section-desc">
				{FORUMS_NEWTOPIC_SUBTITLE}
			</div>
			
		</div>

		<div class="section-body">

			<!-- BEGIN: FORUMS_NEWTOPIC_ERROR -->

			<div class="error">
				{FORUMS_NEWTOPIC_ERROR_BODY}
			</div>

			<!-- END: FORUMS_NEWTOPIC_ERROR -->

			<form action="{FORUMS_NEWTOPIC_SEND}" method="post" name="newtopic">


				<ul class="form responsive-form">

					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.forumsnewtopic.Title}</label></div>
						<div class="form-field">{FORUMS_NEWTOPIC_TITLE}</div>
					</li>
						
					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.forumsnewtopic.Desc}</label></div>
						<div class="form-field">{FORUMS_NEWTOPIC_DESC}</div>
					</li>	

					<!-- BEGIN: PRIVATE -->
					
					<li class="form-row">
						<div class="form-label"><label>{PHP.skinlang.forumsnewtopic.privatetopic}</label></div>
						<div class="form-field">
							{FORUMS_NEWTOPIC_ISPRIVATE}
							<div class="descr">{PHP.skinlang.forumsnewtopic.privatetopic2}</div>
						</div>
					</li>	

					<!-- END: PRIVATE -->					
									  
					<li class="form-row">
						<div class="form-field-100">
							{FORUMS_NEWTOPIC_TEXT}	
						</div>
					</li>
					
					<li class="form-row">
						<div class="form-field-100 text-center">
							<button type="submit" class="submit btn">{PHP.skinlang.forumsnewtopic.Submit}</button>		
						</div>
					</li>						

				</ul>

			</form>

		</div>
		
	</div>
	
</main>

<!-- END: MAIN -->
