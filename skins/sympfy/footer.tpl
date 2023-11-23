<!-- BEGIN: FOOTER -->

<footer id="footer">

	<div class="footer-top">

		<div class="container container-footer">

			<div class="footer-wrapper">

				<div class="footer-about-col">

					<h3>About</h3>

					<p>Seditio is open source software licensed under the LGPL-3.0. The license permits the
						free use of the software even for commercial projects.</p>

					<p>You can track the development of Seditio on GitHub and you are welcome to join in
						if you want. If you think you have found a security problem in Seditio, please report it
						responsibly according to our security policy.</p>

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

<script src="skins/{PHP.skin}/js/jquery.min.js"></script>
<script src="skins/{PHP.skin}/js/jquery.plugins.min.js"></script>
<script src="skins/{PHP.skin}/js/app.js"></script>

<!-- BEGIN: USER -->
{PHP.out.uploader_footer}
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