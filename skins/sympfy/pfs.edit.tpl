<!-- BEGIN: MAIN -->

<main id="system">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{PFS_SHORTTITLE}</h1>

		</div>

		<div class="section-body">

			<!-- BEGIN: PFS_EDITFILE -->

			<form id="newfolder" action="{PFS_EDITFILE_SEND}" method="post">

				<ul class="form responsive-form">

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Date}:</label></div>
						<div class="form-field">{PFS_EDITFILE_DATE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Folder}:</label></div>
						<div class="form-field">{PFS_EDITFILE_FOLDER}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.URL}:</label></div>
						<div class="form-field">{PFS_EDITFILE_URL}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Size}:</label></div>
						<div class="form-field">{PFS_EDITFILE_SIZE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.File}:</label></div>
						<div class="form-field">{PFS_EDITFILE_FILE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Title}:</label></div>
						<div class="form-field">{PFS_EDITFILE_TITLE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Description}:</label></div>
						<div class="form-field">{PFS_EDITFILE_DESC}</div>
					</li>

				</ul>

				<div class="centered">
					<button type="submit" class="submit btn btn-big">{PHP.L.Update}</button>
				</div>

			</form>

			<!-- END: PFS_EDITFILE -->

			<!-- BEGIN: PFS_EDITFOLDER -->

			<form id="newfolder" action="{PFS_EDITFOLDER_SEND}" method="post">

				<ul class="form responsive-form">

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Title}:</label></div>
						<div class="form-field">{PFS_EDITFOLDER_TITLE}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Description}:</label></div>
						<div class="form-field">{PFS_EDITFOLDER_DESC}</div>
					</li>

					<li class="form-row">
						<div class="form-label"><label>{PHP.L.Type}:</label></div>
						<div class="form-field">{PFS_EDITFOLDER_TYPE}</div>
					</li>

				</ul>

				<div class="centered">
					<button type="submit" class="submit btn btn-big">{PHP.L.Update}</button>
				</div>

			</form>

			<!-- END: PFS_EDITFOLDER -->

		</div>

	</div>

</main>

<!-- END: MAIN -->