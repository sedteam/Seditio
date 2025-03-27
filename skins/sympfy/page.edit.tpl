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
					</ul>

					<div class="tab-box">

						<div id="tab1" class="tabs">

							<ul class="form responsive-form">

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Category}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_CAT}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Title}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_TITLE}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Description}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_DESC}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Author}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_AUTHOR}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Alias}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_ALIAS}</div>
								</li>

								<li class="form-row">
									<div class="form-field-100">{PHP.skinlang.pageedit.Bodyofthepage}<br /><br />{PAGEEDIT_FORM_TEXT}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Thumbs}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_THUMB}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PAGEEDIT_FORM_SLIDER_TITLE}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_SLIDER}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Pageid}</label></div>
									<div class="form-field">#{PAGEEDIT_FORM_ID}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Deletethispage}</label></div>
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
									<div class="form-label"><label>{PHP.skinlang.pageedit.Date}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_DATE}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Begin}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_BEGIN}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Expire}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_EXPIRE}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Pagehitcount}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_PAGECOUNT}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Extrakey}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_KEY}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Owner}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_OWNERID}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Allowcomments}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_ALLOWCOMMENTS}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Allowratings}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_ALLOWRATINGS}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Filedownload}</label></div>
									<div class="form-field">{PAGEEDIT_FORM_FILE}</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.URL}</label></div>
									<div class="form-field">
										{PAGEEDIT_FORM_URL}
										<div class="descr">{PHP.skinlang.pageedit.URLhint}</div>
									</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Filesize}</label></div>
									<div class="form-field">
										{PAGEEDIT_FORM_SIZE}
										<div class="descr">{PHP.skinlang.pageedit.Filesizehint}</div>
									</div>
								</li>

								<li class="form-row">
									<div class="form-label"><label>{PHP.skinlang.pageedit.Filehitcount}</label></div>
									<div class="form-field">
										{PAGEEDIT_FORM_FILECOUNT}
										<div class="descr">{PHP.skinlang.pageedit.Filehitcounthint}</div>
									</div>
								</li>

							</ul>

						</div>

					</div>

				</div>

				<div class="help">{PHP.skinlang.pageedit.Formhint} </div>

				<div class="centered">

					<button type="submit" class="submit btn btn-big">{PHP.skinlang.pageedit.Update}</button>

					<!-- BEGIN: PAGEEDIT_PUBLISH -->
					<button type="submit" class="submit btn btn-big" name="rpagepublish" onclick="this.value='{PAGEEDIT_FORM_PUBLISH_STATE}'; return true" />{PAGEEDIT_FORM_PUBLISH_TITLE}</button>
					<!-- END: PAGEEDIT_PUBLISH -->

				</div>

			</form>

		</div>

	</div>

</main>

<!-- END: MAIN -->