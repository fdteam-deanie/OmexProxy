<template>
	<footer class="footer no_border">
		<div class="container flex jc_between">
			<div class="footer_col">
				<div class="logo_container">
					<router-link to="/" class="logo">
						<h1>omex</h1>
					</router-link>
				</div>
				<div class="mobile_hidden">
					<h4>Contact Us</h4>
					<ul class="social">
						<a href="#">
							<div class="icon_social icon_instagram"></div>
						</a>
						<a href="#">
							<div class="icon_social icon_twitter"></div>
						</a>
						<a href="#">
							<div class="icon_social icon_mail"></div>
						</a>
						<a href="#">
							<div class="icon_social icon_facebook"></div>
						</a>
					</ul>
					<p class="copy">&copy; Omex | All rights reserved</p>
				</div>
			</div>
			<div class="footer_col">
				<h4>About Us</h4>
				<nav>
					<ul>
						<li><a href="#">Terms of Service</a></li>
						<li><a href="#">Refund Policy</a></li>
						<li><a href="#">Aml Compliance Program</a></li>
						<li><a href="#">Shipping Terms</a></li>
						<li><a href="#">Privacy Policy</a></li>
					</ul>
				</nav>
			</div>
			<div class="footer_col">
				<h4>Pricing</h4>
				<nav>
					<ul>
						<li><a href="#">Dynamic Residential Proxy</a></li>
						<li><a href="#">Rotating ISP Proxy</a></li>
						<li><a href="#">Static Residential Proxy</a></li>
						<li><a href="#">Unlimited Residential Proxy</a></li>
						<li><a href="#">Data Center Proxy</a></li>
					</ul>
				</nav>
			</div>
			<div class="footer_col">
				<h4>Get Proxies</h4>
				<nav>
					<ul>
						<li><a href="#">API</a></li>
						<li><a href="#">User & Pass</a></li>
						<li><a href="#">AuthS5</a></li>
						<li><a href="#">ProxyFree</a></li>
						<li><a href="#">Proxy</a></li>
					</ul>
				</nav>
			</div>
		</div>
	</footer>
</template>
<script>
	export default {
		mounted() {
			console.log('AppFooter');
		}
	}
</script>