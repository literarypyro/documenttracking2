        <div class="menu-right">
            <ul class="notification-menu">
                <li>
                    <a href="#" class="btn btn-default dropdown-toggle" id='exception'>
                        <!--
						<img src="images/photos/user-avatar.png" alt="" />
                        -->
						<?php echo $_SESSION['division']; ?>
                    </a>
                </li>

				<li>
                    <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <!--
						<img src="images/photos/user-avatar.png" alt="" />
                        -->
						<?php echo $_SESSION['name']; ?>
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                        <li><a href="#"><i class="fa fa-user"></i>  Profile</a></li>
                        <li><a href="#"><i class="fa fa-cog"></i>  Settings</a></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out"></i> Log Out</a></li>
                    </ul>
                </li>

				
				
            </ul>
        </div>