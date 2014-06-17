    <nav class="navbar navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <a class="navbar-brand" href="index.php">Document Tracking System</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="main_page.php">Home</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">New Action <i class="fa fa-angle-down"></i></a>
              <ul class="dropdown-menu">
					<li><a href='new_action.php'>Create new Document</a></li>
					<li><a href="search_archive.php">Search in Archive</a></li>
			
			  </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">Newly Received <i class="fa fa-angle-down"></i></a>
              <ul class="dropdown-menu">
                <li><a href="main_documents.php">Document Alerts</a></li>
                <li><a href="copies_received.php">Copies Received</a></li>
				<!--
                <li><a href="unsent_documents.php">Unsent Documents</a></li>
				-->
			</ul>
            </li>
			<?php if($_SESSION['division_code']=="OGM"){
			?>
			
            <li class="dropdown">
              <a href="ogm_panel.php" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">OGM Control Panel <i class="fa fa-angle-down"></i></a>
              <ul class="dropdown-menu">
                <li><a href="ogm_panel.php">OGM Page</a></li>
                <li><a href="#">Records Officer Page</a></li>


              </ul>
            </li>
		<?php	
		}	
		
		?>
			<?php if($_SESSION['division_code']=="REC"){
			?>

		
		            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">Records Officer <i class="fa fa-angle-down"></i></a>
              <ul class="dropdown-menu">
                <li><a href="records_monitoring.php">Monitoring Panel</a></li>
                <li><a href="records_outing.php">Outgoing Documents</a></li>
                <li><a href="monthly_report.php">Monthly Report</a></li>


              </ul>
            </li>

			<?php
			
			}
			?>
		
		
		
		
		
		
		
		
		<!--            <li><a href="contact.html">Contact</a></li>-->
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container -->
    </nav>