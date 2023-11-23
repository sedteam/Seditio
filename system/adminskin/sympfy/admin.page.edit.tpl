<!-- BEGIN: MAIN -->

<div class="title">
	<span><i class="ic-pages"></i></span>
	<h2>{PAGEEDIT_PAGETITLE}</h2>
</div>

<!-- BEGIN: PAGEEDIT_ERROR -->

<div class="notification error png_bg">
	<a href="" class="close" title="{PHP.L.Close}"></a>
	<div>
		{PAGEEDIT_ERROR_BODY}
	</div>
</div>

<!-- END: PAGEEDIT_ERROR -->

<div class="content-box sedtabs">

	<div class="content-box-header">
		<h3 class="tab-title">{PHP.L.Page}</h3>
		<ul class="content-box-tabs">
			<li><a href="{PHP.sys.request_uri}#tab1" class="selected" data-tabtitle="{PHP.L.Page}">{PHP.L.Page}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab2" data-tabtitle="{PHP.L.Meta}">{PHP.L.Meta}</a></li>
			<li><a href="{PHP.sys.request_uri}#tab3" data-tabtitle="{PHP.L.Options}">{PHP.L.Options}</a></li>
		</ul>
	</div>

	<div class="content-box-content content-table">

		<form action="{PAGEEDIT_FORM_SEND}" method="post" name="update">

			<div class="tab-content default-tab" id="tab1">

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

			<div class="tab-content" id="tab2">

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

				</ul>

			</div>

			<div class="tab-content" id="tab3">

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

			<div class="help">{PHP.skinlang.pageedit.Formhint} </div>

			<div class="centered">
				<input type="submit" class="submit btn btn-big" value="{PHP.skinlang.pageedit.Update}">
				<!-- BEGIN: PAGEEDIT_PUBLISH -->
				<input type="submit" class="submit btn btn-big" name="rpagepublish" value="{PAGEEDIT_FORM_PUBLISH_TITLE}" onclick="this.value='{PAGEEDIT_FORM_PUBLISH_STATE}'; return true" />
				<!-- END: PAGEEDIT_PUBLISH -->
			</div>

		</form>

	</div>

</div>

<!-- END: MAIN -->