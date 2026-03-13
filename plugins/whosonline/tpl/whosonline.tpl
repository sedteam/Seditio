<!-- BEGIN: MAIN -->

<main id="plugins">

	<div class="container">

		<div class="section-title">

			{BREADCRUMBS}

			<h1>{PLUGIN_WHOSONLINE_TITLE}</h1>

			<div class="section-desc">

			</div>

		</div>

		<div class="section-body">

			<p>{PHP.L.plu_mostonline} {PLUGIN_WHOSONLINE_MOST_ONLINE}.<br />
				{PHP.L.plu_therescurrently} {PLUGIN_WHOSONLINE_THERESCURRENTLY} {PHP.L.plu_visitors} {PLUGIN_WHOSONLINE_TOTAL_VISITORS} {PHP.L.plu_members}</p>

			<div class="table cells striped resp-table">

				<div class="table-head resp-table-head">

					<div class="table-row resp-table-row">
						<div class="table-th coltop text-left" style="width:30px;"></div>
						<div class="table-th coltop text-left">{PHP.L.User}</div>
						<div class="table-th coltop text-left" style="width:15%;">{PHP.L.Group}</div>
						<div class="table-th coltop text-left" style="width:10%;">{PHP.L.Country}</div>
						<div class="table-th coltop text-left" style="width:15%;">{PHP.L.plu_lastseen1}</div>
						<!-- BEGIN: PLUGIN_WHOSONLINE_HEAD_ONLINE -->
						<div class="table-th coltop text-left" style="width:25%;">{PHP.L.plu_in}</div>
						<div class="table-th coltop text-left" style="width:10%;">{PHP.L.Ip}</div>
						<!-- END: PLUGIN_WHOSONLINE_HEAD_ONLINE -->
					</div>

				</div>

				<div class="table-body resp-table-body">

					<!-- BEGIN: PLUGIN_WHOSONLINE_USERS_ROW -->

					<div class="table-row resp-table-row">

						<div class="table-td text-left resp-table-td pl-whosonline-avatar">
							{PLUGIN_WHOSONLINE_ROW_AVATAR}
						</div>
						<div class="table-td text-left resp-table-td pl-whosonline-user" data-label="{PHP.L.User}">
							{PLUGIN_WHOSONLINE_ROW_USER}
						</div>
						<div class="table-td text-left resp-table-td pl-whosonline-group" data-label="{PHP.L.Group}">
							{PLUGIN_WHOSONLINE_ROW_MAINGRP}
						</div>
						<div class="table-td text-left resp-table-td pl-whosonline-country" data-label="{PHP.L.Country}">
							{PLUGIN_WHOSONLINE_ROW_COUNTRYFLAG}
						</div>
						<div class="table-td text-left resp-table-td pl-whosonline-lastseen" data-label="{PHP.L.plu_lastseen1}">
							{PLUGIN_WHOSONLINE_ROW_LASTSEEN}
						</div>
						<!-- BEGIN: PLUGIN_WHOSONLINE_USERS_ROW_ONLINE -->
						<div class="table-td text-left resp-table-td pl-whosonline-location" data-label="{PHP.L.plu_in}">
							{PLUGIN_WHOSONLINE_ROW_ONLINE_LOCATION}
						</div>

						<div class="table-td text-left resp-table-td pl-whosonline-ip" data-label="{PHP.L.Ip}">
							{PLUGIN_WHOSONLINE_ROW_ONLINE_IP}
						</div>
						<!-- END: PLUGIN_WHOSONLINE_USERS_ROW_ONLINE -->

					</div>

					<!-- END: PLUGIN_WHOSONLINE_USERS_ROW -->

					<!-- BEGIN: PLUGIN_WHOSONLINE_GUESTS_ROW -->

					<div class="table-row resp-table-row">

						<div class="table-td text-left resp-table-td pl-whosonline-avatar pl-whosonline-guest">

						</div>
						<div class="table-td text-left resp-table-td pl-whosonline-user" data-label="{PHP.L.User}">
							{PLUGIN_WHOSONLINE_ROW_USER}
						</div>
						<div class="table-td text-left resp-table-td pl-whosonline-group pl-whosonline-guest">

						</div>
						<div class="table-td text-left resp-table-td pl-whosonline-country pl-whosonline-guest">

						</div>
						<div class="table-td text-left resp-table-td pl-whosonline-lastseen" data-label="{PHP.L.plu_lastseen1}">
							{PLUGIN_WHOSONLINE_ROW_LASTSEEN}
						</div>
						<!-- BEGIN: PLUGIN_WHOSONLINE_GUESTS_ROW_ONLINE -->
						<div class="table-td text-left resp-table-td pl-whosonline-location" data-label="{PHP.L.plu_in}">
							{PLUGIN_WHOSONLINE_ROW_ONLINE_LOCATION}
						</div>

						<div class="table-td text-left resp-table-td pl-whosonline-ip" data-label="{PHP.L.Ip}">
							{PLUGIN_WHOSONLINE_ROW_ONLINE_IP}
						</div>
						<!-- END: PLUGIN_WHOSONLINE_GUESTS_ROW_ONLINE -->

					</div>

					<!-- END: PLUGIN_WHOSONLINE_GUESTS_ROW -->

				</div>

			</div>

		</div>

	</div>

</main>

<!-- END: MAIN -->