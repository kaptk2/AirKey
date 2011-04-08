	<body>
		<div class='container'>
			<div id='logo'>
			<img src="<?php echo site_url('static/images/AirKey_Logo_Grdnt.png'); ?>" width=279 height=87 alt="AirKey Logo">
			</div>
			<div id='networkStats'>
				<h3>Network at a Glance</h3>
				<p>
					<span class="caps">Total Access Points: </span>22<br/>
					<span class="caps">Pending Commands: </span>2
				</p>
			</div>
			<hr />
		</div>
		<div class='container'>
			<div id='statusBar'>
				<div id="status">
					<span class="caps">Network Status: </span>A Okay!
				</div>
				<div id="search">
					<form method="post" action="<?php echo site_url('search'); //TODO?>">
						<label>Search: </label>
						<input type="text" size="20" name="search" />
						<input type="submit" value="Search">
					</form>
				</div>
			</div>
		</div>
		<div class='container'>
			<hr />
			<div id='navigationBar'>
			<ul class='tabs'>
				<li class='label'><span class="caps">Navigation:</span></li>
				<li><a href="#" class='selected'>Tab 1</a></li>
				<li><a href="#">Tab 2</a></li>
				<li><a href="#">Tab 3</a></li>
			</ul>
			</div>
		</div>
