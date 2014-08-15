<?php
/**
 * File main.php
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
?>

<?php $sPubPath = asset('packages/fintech-fab/actions-calc/'); ?>

<!DOCTYPE html>
<html class="no-js" lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>FintechFab::ActionsCalculator</title>

	<style>
		<?php require(__DIR__ . "/css/app.min.css"); ?>
	</style>

	<!-- select2 -->
	<link rel="stylesheet" href="//cdn.jsdelivr.net/select2/3.4.8/select2.css">
	<script src="//cdn.jsdelivr.net/select2/3.4.8/select2.min.js"></script>

	<!-- main app.js-->
	<script>
		<?php require(__DIR__ . "/js/app.js"); ?>
	</script>

</head>
<body>


<div class="row">
	<div class="large-12 columns">
		<div class="nav-bar right">
			<ul class="button-group">
				<li><a href="#" class="button">Link 1</a></li>
				<li><a href="#" class="button">Link 2</a></li>
				<li><a href="#" class="button">Link 3</a></li>
				<li><a href="#" class="button">Link 4</a></li>
			</ul>
		</div>
		<h1>Blog
			<small>This is my blog. It's awesome.</small>
		</h1>
		<hr />
	</div>
</div>


<div class="row">

	<div class="large-9 columns" role="content">

		<script>
	        $(document).ready(function() { $("#e1").select2(); });
	    </script>
		<select id="e1">
	        <option value="AL">Alabama</option>
	        <option value="WY">Wyoming</option>
	        <option value="WY">Wyoming</option>
	        <option value="WY">Wyoming</option>
	        <option value="WY">Wyoming</option>
	    </select>

		<article>

			<h3><a href="#">Blog Post Title</a></h3>
			<h6>Written by <a href="#">John Smith</a> on August 12, 2012.</h6>

			<div class="row">
				<div class="large-6 columns">
					<p>Bacon ipsum dolor sit amet nulla ham qui sint exercitation eiusmod commodo, chuck duis velit.
						Aute in reprehenderit, dolore aliqua non est magna in labore pig pork biltong. Eiusmod swine
						spare ribs reprehenderit culpa.</p>

					<p>Boudin aliqua adipisicing rump corned beef. Nulla corned beef sunt ball tip, qui bresaola enim
						jowl. Capicola short ribs minim salami nulla nostrud pastrami.</p>
				</div>
				<div class="large-6 columns">
					<img src="http://placehold.it/400x240&text=[img]" />
				</div>
			</div>

			<p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon
				nulla pork belly cupidatat meatloaf cow. Nulla corned beef sunt ball tip, qui bresaola enim jowl.
				Capicola short ribs minim salami nulla nostrud pastrami. Nulla corned beef sunt ball tip, qui bresaola
				enim jowl. Capicola short ribs minim salami nulla nostrud pastrami.</p>

			<p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon
				nulla pork belly cupidatat meatloaf cow. Nulla corned beef sunt ball tip, qui bresaola enim jowl.
				Capicola short ribs minim salami nulla nostrud pastrami.</p>

		</article>

		<hr />

		<article>

			<h3><a href="#">Blog Post Title</a></h3>
			<h6>Written by <a href="#">John Smith</a> on August 12, 2012.</h6>

			<div class="row">
				<div class="large-6 columns">
					<p>Bacon ipsum dolor sit amet nulla ham qui sint exercitation eiusmod commodo, chuck duis velit.
						Aute in reprehenderit, dolore aliqua non est magna in labore pig pork biltong. Eiusmod swine
						spare ribs reprehenderit culpa.</p>

					<p>Boudin aliqua adipisicing rump corned beef. Nulla corned beef sunt ball tip, qui bresaola enim
						jowl. Capicola short ribs minim salami nulla nostrud pastrami.</p>
				</div>
				<div class="large-6 columns">
					<img src="http://placehold.it/400x240&text=[img]" />
				</div>
			</div>

			<p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon
				nulla pork belly cupidatat meatloaf cow. Nulla corned beef sunt ball tip, qui bresaola enim jowl.
				Capicola short ribs minim salami nulla nostrud pastrami. Nulla corned beef sunt ball tip, qui bresaola
				enim jowl. Capicola short ribs minim salami nulla nostrud pastrami.</p>

			<p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon
				nulla pork belly cupidatat meatloaf cow. Nulla corned beef sunt ball tip, qui bresaola enim jowl.
				Capicola short ribs minim salami nulla nostrud pastrami.</p>

		</article>

	</div>


	<aside class="large-3 columns">

		<h5>Categories</h5>
		<ul class="side-nav">
			<li><a href="#">News</a></li>
			<li><a href="#">Code</a></li>
			<li><a href="#">Design</a></li>
			<li><a href="#">Fun</a></li>
			<li><a href="#">Weasels</a></li>
		</ul>

		<div class="panel">
			<h5>Featured</h5>

			<p>Pork drumstick turkey fugiat. Tri-tip elit turducken pork chop in. Swine short ribs meatball irure bacon
				nulla pork belly cupidatat meatloaf cow.</p>
			<a href="#">Read More →</a>
		</div>

	</aside>

</div>


<footer class="row">
	<div class="large-12 columns">
		<hr />
		<div class="row">
			<div class="large-6 columns">
				<p>© Copyright no one at all. Go to town.</p>
			</div>
			<div class="large-6 columns">
				<ul class="inline-list right">
					<li><a href="#">Link 1</a></li>
					<li><a href="#">Link 2</a></li>
					<li><a href="#">Link 3</a></li>
					<li><a href="#">Link 4</a></li>
				</ul>
			</div>
		</div>
	</div>
</footer>

<script>
	<?php require(__DIR__ . "/js/cf.js"); ?>
	$(document).foundation();
</script>

</body>
</html>