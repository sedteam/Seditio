<!-- BEGIN: FOOTER -->

<footer id="footer">

	<div class="footer-top">

		<div class="container container-footer">

			<div class="footer-wrapper">

				<div class="footer-about-col">

					<h3>About</h3>

					<p>Seditio is an ultra-fast, lightweight PHP Content Management Framework with a 20-year history. Built on a clean procedural core and the flexible XTemplate engine, it keeps design strictly separated from logic.</p>

					<p>The system is completely open-source (BSD 3-Clause License) and free for both personal and commercial projects. Track development, report security issues, or join our community on GitHub.</p>

				</div>
				<div class="footer-menu-col">
					<div class="footer-menu-table">
						<div class="footer-menu-table-col">
							<div class="footer-menu">
								{PHP.sed_menu.1.childrensonlevel}
							</div>
						</div>
						<div class="footer-menu-table-col">
							<div class="footer-menu">
								{PHP.sed_menu.4.childrens}
							</div>
						</div>
					</div>
				</div>
				<div class="footer-social-col">

					<ul class="socialmedia">
						<li class="socialmedia-li">
							<a title="Facebook" href="" class="socialmedia-a">
								<span class="ic-facebook"></span>
							</a>
						</li>
						<li class="socialmedia-li">
							<a title="Vkontakte" href="" class="socialmedia-a">
								<span class="ic-vk"></span>
							</a>
						</li>
						<li class="socialmedia-li">
							<a title="Instagram" href="" class="socialmedia-a">
								<span class="ic-instagram"></span>
							</a>
						</li>
					</ul>

					<!-- BEGIN: GUEST -->
					<ul class="footer-auth">
						<li><i class="ic-user"></i> <a href="{PHP.out.auth_link}">{PHP.skinlang.header.Login}</a></li>
						<li><i class="ic-plus"></i> <a href="{PHP.out.register_link}">{PHP.skinlang.header.Register}</a></li>
					</ul>
					<!-- END: GUEST -->

				</div>

			</div>

		</div>

	</div>

	<div class="footer-bottom">

		<div class="container">
			<div class="footer-bottom-table">
				<div class="footer-bottom-copyright">
					<p>{FOOTER_BOTTOMLINE}</p>
				</div>
				<div class="footer-bottom-dev">
					<p><a href="https://seditio.org" target="_blank" title="CMS Seditio">Merci, Olivier!</a></p>
				</div>
			</div>
			<div class="footer-stat">
				{FOOTER_CREATIONTIME}<br />{FOOTER_SQLSTATISTICS}
			</div>
		</div>

		{FOOTER_DEVMODE}

	</div>

</footer>

{FOOTER_COOKIENOTICE}
{FOOTER_JAVASCRIPT}
<script src="skins/{PHP.skin}/js/jquery.min.js"></script>
<script src="skins/{PHP.skin}/js/jquery.plugins.min.js"></script>
<script src="skins/{PHP.skin}/js/app.js"></script>

<!-- BEGIN: USER -->
<script>
	var L = {};
	L.pageadd = "{PHP.skinlang.admintooltip.pageadd}";
	L.pageedit = "{PHP.skinlang.admintooltip.pageedit}";
	L.pageeditcategory = "{PHP.skinlang.admintooltip.pageeditcategory}";
	L.pageeditoption = "{PHP.skinlang.admintooltip.pageeditoption}";	
</script>
<!-- END: USER -->

<!-- BEGIN: ADMIN -->
<script src="skins/{PHP.skin}/js/admintooltip.js"></script>
<!-- END: ADMIN -->

</body>

</html>

<!-- END: FOOTER -->