        <div class="left-side-inner">

            <!--sidebar nav start-->
            <ul class="nav nav-pills nav-stacked custom-nav">
                <li><a href="main_page.php"><i class="fa fa-home"></i> <span>Home</span></a></li>
                <li class="menu-list"><a href=""><i class="fa fa-tasks"></i> <span>New Action</span></a>
                    <ul class="sub-menu-list">
                        <li ><a href="new_action.php"> Create A Document</a></li>
                        <li><a href="search_archive.php"> Search in Archive</a></li>
 
                    </ul>
                </li>
                <li class="menu-list"><a href=""><i class="fa fa-file-text"></i> <span>Newly Received</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="main_documents.php"> Document Alerts</a></li>
                        <li><a href="copies_received.php"> Copies Received</a></li>
                    </ul>
                </li>
				
				<?php
				if($_SESSION['division_code']=="OGM"){
				?>
                <li><a href="ogm_panel.php"><i class="fa fa-cogs"></i> <span>Control Panel (OGM)</span></a>
                </li>
				<?php
				}
				?>
				<?php
				if($_SESSION['division_code']=="REC"){
				?>

                <li class="menu-list"><a href="#"><i class="fa fa-cogs"></i> <span>Records Officer</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="records_monitoring.php"> Monitoring Panel</a></li>
						<li><a href="records_outing.php"> Outgoing Documents</a></li>
                        <li><a href="monthly_report.html"> Monthly Report</a></li>
					</ul>
                </li>
				<?php
				}
				?>
				<!--
                <li class="menu-list nav-active"><a href=""><i class="fa fa-tasks"></i> <span>Forms</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="form_layouts.html"> Form Layouts</a></li>
                        <li><a href="form_advanced_components.html"> Advanced Components</a></li>
                        <li><a href="form_wizard.html"> Form Wizards</a></li>
                        <li><a href="form_validation.html"> Form Validation</a></li>
                        <li><a href="editors.html"> Editors</a></li>
                        <li><a href="inline_editors.html"> Inline Editors</a></li>
                        <li class="active"><a href="pickers.html"> Pickers</a></li>
                        <li><a href="dropzone.html"> Dropzone</a></li>
                    </ul>
                </li>
                <li class="menu-list"><a href=""><i class="fa fa-bar-chart-o"></i> <span>Charts</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="flot_chart.html"> Flot Charts</a></li>
                        <li><a href="morris.html"> Morris Charts</a></li>
                        <li><a href="chartjs.html"> Chartjs</a></li>
                        <li><a href="c3chart.html"> C3 Charts</a></li>
                    </ul>
                </li>
                <li class="menu-list"><a href="#"><i class="fa fa-th-list"></i> <span>Data Tables</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="basic_table.html"> Basic Table</a></li>
                        <li><a href="dynamic_table.html"> Advanced Table</a></li>
                        <li><a href="responsive_table.html"> Responsive Table</a></li>
                        <li><a href="editable_table.html"> Edit Table</a></li>
                    </ul>
                </li>

                <li class="menu-list"><a href="#"><i class="fa fa-map-marker"></i> <span>Maps</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="google_map.html"> Google Map</a></li>
                        <li><a href="vector_map.html"> Vector Map</a></li>
                    </ul>
                </li>
                <li class="menu-list"><a href=""><i class="fa fa-file-text"></i> <span>Extra Pages</span></a>
                    <ul class="sub-menu-list">
                        <li><a href="profile.html"> Profile</a></li>
                        <li><a href="invoice.html"> Invoice</a></li>
                        <li><a href="pricing_table.html"> Pricing Table</a></li>
                        <li><a href="timeline.html"> Timeline</a></li>
                        <li><a href="blog_list.html"> Blog List</a></li>
                        <li><a href="blog_details.html"> Blog Details</a></li>
                        <li><a href="directory.html"> Directory </a></li>
                        <li><a href="chat.html"> Chat </a></li>
                        <li><a href="404.html"> 404 Error</a></li>
                        <li><a href="500.html"> 500 Error</a></li>
                        <li><a href="registration.html"> Registration Page</a></li>
                        <li><a href="lock_screen.html"> Lockscreen </a></li>
                    </ul>
                </li>
                -->
				
				<li><a href="logout.php"><i class="fa fa-sign-in"></i> <span>Back to Login Page</span></a></li>
				
            </ul>
            <!--sidebar nav end-->
        </div>
	