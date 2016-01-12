<div class="sidebar-wrapper">
	<ul class="sidebar-nav list-unstyled">
		<li>
			<a class="<?php if ($pagename == "dashboard"){echo "active-nav";}?>" href="<?php echo BASE_URL; ?>dashboard/">Dashboard</a>
		</li>
		<li>
			<a class="<?php if ($pagename == "examenresultatentoevoegen"){echo "active-nav";}?>" href="<?php echo BASE_URL; ?>dashboard/examenresultatentoevoegen.php">Examenresultaten toevoegen</a>
		</li>
		<li>
			<a class="<?php if ($pagename == "resultaten"){echo "active-nav";}?>" href="<?php echo BASE_URL; ?>dashboard/resultaten.php">Resultaten en feedback</a>
		</li>
	</ul>
	<ul class="sidebar-nav settings-nav list-unstyled">
		<li>
			<a class="<?php if ($pagename == "settings"){echo "active-nav";}?>" href="<?php echo BASE_URL; ?>dashboard/settings.php">Settings</a>
		</li>
		<li>
			<a style="cursor:pointer" data-toggle='modal' data-target='#about'>Over</a>
		</li>
		<li>
			<a href="<?php echo BASE_URL; ?>includes/logout.php">Uitloggen</a>
		</li>
		<h5><small><center>© 2015 - 2016 Examenanalyse v1.0 BETA</center></small></h5>
	</ul>
</div>