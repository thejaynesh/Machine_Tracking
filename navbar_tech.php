
            <!-- Sidebar Holder -->
            <nav id="sidebar">
                <div class="sidebar-header">
                    <?php
                    if (!isset($_SESSION['id']))
                        echo '<a href="index.php"><h3>Machine Tracking</h3></a>';
                    else
                        echo '<a href="home.php"><h3>Machine Tracking</h3></a>';
                    ?>
                </div>

                <ul class="list-unstyled components">
                    <p>Menu</p>
                    
                     <li class="">
                        <a href="#complaintmenu" data-toggle="collapse" aria-expanded="false">Register a complaint</a>
                        <ul class="collapse list-unstyled" id="complaintmenu">
                            <li><a href="complaint_form.php">Complaint for PC</a></li>
                            <li><a href="device_complaint_form.php">Complaint for Hardware</a></li>
                        </ul>
                    </li>
                    
                    
                    <li class="">
                        <a href="#contactmenu" data-toggle="collapse" aria-expanded="false">Contact</a>
                        <ul class="collapse list-unstyled" id="contactmenu">
                            <li><a href="#add_member.php">phadnis.anurag@gmail.com</a></li>
                            <li><a href="#view_member.php">krishlalwani1@gmail.com</a></li>

                        </ul>
                    </li>
                    <?php
                    if(isset($_SESSION['id']))
                    {
                        echo "<hr>";
                        echo "<li>";
                            echo '<a href="logout.php">Logout</a>';
                        echo "</li>";
                    }
                    ?>
                     <hr>
                    <li class="">
                        <a href="#developer">Developed By:</a>
                        <ul class="list-unstyled" id="contactmenu">
                            <li><a href="#view_member.php">Krish Lalwani</a></li>
                            <li><a href="#add_member.php">Anurag Phadnis</a></li>
                            <li><a href="#view_member.php">Husain Attari</a></li> 
                            <li><a href="#add_member.php">Aaditya Rathour</a></li>
                        </ul>
                    </li>
                <hr>
                </ul>
            </nav>
    <div class="container" id="content">
    <div class="page-header">
    <button type="button" id="sidebarCollapse" class="navbar-btn">
        <span></span>
        <span></span>
        <span></span>
    </button>
