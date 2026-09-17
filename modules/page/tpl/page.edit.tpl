<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{PAGEEDIT_PAGETITLE}</h1>

			<div class="section-desc">
				{PAGEEDIT_SUBTITLE}
			</div>

		</div>

		<div class="section-body">

			<!-- BEGIN: PAGEEDIT_ERROR -->

			{PAGEEDIT_ERROR_BODY}

			<!-- END: PAGEEDIT_ERROR -->

			<form action="{PAGEEDIT_FORM_SEND}" method="post" name="update">

				<div class="sedtabs">

					<ul class="tabs">
						<li><a href="{PHP.sys.request_uri}#tab1" class="selected">{PHP.L.Page}</a></li>
						<li><a href="{PHP.sys.request_uri}#tab2">{PHP.L.Meta}</a></li>
						<li><a href="{PHP.sys.request_uri}#tab3">{PHP.L.Options}</a></li>
						{PAGEEDIT_I18N_TABS_HEADERS}
					</ul>

					<div class="tab-box">

						<div id="tab1" class="tabs">

							<ul class="form responsive-form">

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_category}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_CAT}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_title}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_TITLE}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_description}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_DESC}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_author}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_AUTHOR}</div>
								</li>

							<li class="form-row">
								<div class="form-label"><label>{PHP.L.pageedit_alias}</label></div>
								<div class="form-field">{PAGEEDIT_FORM_ALIAS}</div>
							</li>

							<li class="form-row">
								<div class="form-label"><label>{PHP.L.Tags}</label></div>
								<div class="form-field">
									{PAGEEDIT_FORM_TAGS}
									<div class="descr">{PAGEEDIT_FORM_TAGS_HINT}</div>
								</div>
							</li>

							<li class="form-row">
								<div class="form-field-100">{PHP.L.pageedit_bodyofthepage}<br /><br />{PAGEEDIT_FORM_TEXT}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_thumbs}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_THUMB}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PAGEEDIT_FORM_SLIDER_TITLE}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_SLIDER}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_pageid}</label></div>
									<div class="form-field">#{PAGEEDIT_FORM_ID}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_deletethispage}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_DELETE}</div>
								</li>

							</ul>

						</div>

						<div id="tab2" class="tabs">

							<ul class="form responsive-form">

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.mt_title} H1</label></div>
									<div class="form-field">{PAGEEDIT_FORM_SEOH1}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.mt_title}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_SEOTITLE}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.mt_description}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_SEODESC}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.mt_keywords}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_SEOKEYWORDS}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.mt_index} :</label></div>
									<div class="form-field">
										{PAGEEDIT_FORM_SEOINDEX}
										<div class="help">{PHP.L.mt_index_help}</div>
									</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.mt_follow} :</label></div>
									<div class="form-field">
										{PAGEEDIT_FORM_SEOFOLLOW}
										<div class="help">{PHP.L.mt_follow_help}</div>
									</div>
								</li>

							</ul>

						</div>

						<div id="tab3" class="tabs">

							<ul class="form responsive-form">

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_date}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_DATE}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_begin}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_BEGIN}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_expire}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_EXPIRE}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_pagehitcount}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_PAGECOUNT}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_extrakey}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_KEY}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_owner}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_OWNERID}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_allowcomments}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_ALLOWCOMMENTS}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_allowratings}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_ALLOWRATINGS}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_filedownload}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_FILE}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_url}</label></div>
									<div class="form-field">
										{PAGEEDIT_FORM_URL}
										<div class="descr">{PHP.L.pageedit_urlhint}</div>
									</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_filesize}</label></div>
									<div class="form-field">
										{PAGEEDIT_FORM_SIZE}
										<div class="descr">{PHP.L.pageedit_filesizehint}</div>
									</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.L.pageedit_filehitcount}</label></div>
									<div class="form-field">
										{PAGEEDIT_FORM_FILECOUNT}
										<div class="descr">{PHP.L.pageedit_filehitcounthint}</div>
									</div>
								</li>

							</ul>

						</div>

						{PAGEEDIT_I18N_TABS_BODY}

					</div>

				</div>

				<div class="help">{PHP.L.pageedit_formhint} </div>

				<div class="centered">

					<button type="submit" class="submit btn btn-big">{PHP.L.pageedit_update}</button>

					<!-- BEGIN: PAGEEDIT_PUBLISH -->
					<button type="submit" class="submit btn btn-big" name="rpagepublish" onclick="this.value='{PAGEEDIT_FORM_PUBLISH_STATE}'; return true" />{PAGEEDIT_FORM_PUBLISH_TITLE}</button>
					<!-- END: PAGEEDIT_PUBLISH -->

				</div>

			</form>

		</div>

	</div>

</main>

<!-- END: MAIN -->