<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{PAGEADD_PAGETITLE}</h1>

			<div class="section-desc">
				{PAGEADD_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<!-- BEGIN: PAGEADD_ERROR -->

			{PAGEADD_ERROR_BODY}

			<!-- END: PAGEADD_ERROR -->

			<form action="{PAGEADD_FORM_SEND}" method="post" name="newpage">

				<div class="sedtabs">

					<ul class="tabs">
						<li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Page}</a></li>
						<li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.Meta}</a></li>
						<li><a href="{PHP.sys.request_uri}#tab3">{PHP.L.Options}</a></li>
					</ul>

					<div class="tab-box">

						<div id="tab1" class="tabs">

							<ul class="form responsive-form">

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Category}</label></div>
									<div class="form-field">{PAGEADD_FORM_CAT}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Title}</label></div>
									<div class="form-field">{PAGEADD_FORM_TITLE}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Description}</label></div>
									<div class="form-field">{PAGEADD_FORM_DESC}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Author}</label></div>
									<div class="form-field">{PAGEADD_FORM_AUTHOR}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Alias}</label></div>
									<div class="form-field">{PAGEADD_FORM_ALIAS}</div>
								</li>


								<!-- BEGIN: PAGEADD_PARSING -->

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Parsing}</label></div>
									<div class="form-field">{PAGEADD_FORM_TYPE}</div>
								</li>

								<!-- END: PAGEADD_PARSING -->

								<li class="form-row">
									<div class="form-field-100">{PHP.skinlang.pageadd.Bodyofthepage}<br /><br />{PAGEADD_FORM_TEXT}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Thumbs}</label></div>
									<div class="form-field">{PAGEADD_FORM_THUMB}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PAGEADD_FORM_SLIDER_TITLE}</label></div>
									<div class="form-field">{PAGEADD_FORM_SLIDER}</div>
								</li>

							</ul>

						</div>
						<div id="tab2" class="tabs">

							<ul class="form responsive-form">

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.mt_title} H1</label></div>
									<div class="form-field">{PAGEADD_FORM_SEOH1}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.mt_title}</label></div>
									<div class="form-field">{PAGEADD_FORM_SEOTITLE}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.mt_description}</label></div>
									<div class="form-field">{PAGEADD_FORM_SEODESC}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.mt_keywords}</label></div>
									<div class="form-field">{PAGEADD_FORM_SEOKEYWORDS}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.mt_index} :</label></div>
									<div class="form-field">
										{PAGEADD_FORM_SEOINDEX}
										<div class="help">{PHP.L.mt_index_help}</div>
									</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.mt_follow} :</label></div>
									<div class="form-field">
										{PAGEADD_FORM_SEOFOLLOW}
										<div class="help">{PHP.L.mt_follow_help}</div>
									</div>
								</li>

							</ul>

						</div>
						<div id="tab3" class="tabs">

							<ul class="form responsive-form">

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Begin}</label></div>
									<div class="form-field">{PAGEADD_FORM_BEGIN}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Expire}</label></div>
									<div class="form-field">{PAGEADD_FORM_EXPIRE}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Extrakey}</label></div>
									<div class="form-field">{PAGEADD_FORM_KEY}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Owner}</label></div>
									<div class="form-field">{PAGEADD_FORM_OWNER}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Allowcomments}</label></div>
									<div class="form-field">{PAGEADD_FORM_ALLOWCOMMENTS}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Allowratings}</label></div>
									<div class="form-field">{PAGEADD_FORM_ALLOWRATINGS}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.File}</label></div>
									<div class="form-field">
										{PAGEADD_FORM_FILE}
										<div class="descr">{PHP.skinlang.pageadd.Filehint}</div>
									</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.URL}</label></div>
									<div class="form-field">
										{PAGEADD_FORM_URL}
										<div class="descr">{PHP.skinlang.pageadd.URLhint}</div>
									</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageadd.Filesize}</label></div>
									<div class="form-field">
										{PAGEADD_FORM_SIZE}
										<div class="descr">{PHP.skinlang.pageadd.Filesizehint}</div>
									</div>
								</li>

							</ul>

						</div>

					</div>

				</div>

				<div class="help">{PHP.skinlang.pageadd.Formhint}</div>

				<div class="centered">
					<button type="submit" class="submit btn btn-big">{PHP.skinlang.pageadd.Submit}</button>
					<!-- BEGIN: PAGEADD_PUBLISH -->
					<button type="submit" class="submit btn btn-big" name="newpagepublish" onclick="this.value='OK'; return true">{PHP.skinlang.pageadd.Publish}</button>
					<!-- END: PAGEADD_PUBLISH -->
				</div>

			</form>

		</div>

	</div>

</main>

<!-- END: MAIN -->