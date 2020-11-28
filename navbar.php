            <!-- Sidebar Holder -->
            <nav id="sidebar">
                <div class="sidebar-header">
                    <a href="home.php"><h3>Machine Tracking</h3></a>
                </div>

                <ul class="list-unstyled components">
                    <p>Menu</p>
                    <li>
                        <a href="searchpc.php">Search Computer</a>
                    </li>
                    <li class="active">
                        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false">Lab</a>
                        <ul class="collapse list-unstyled" id="homeSubmenu">
                            <li><a href="addlab.php">Add Lab</a></li>
                            <li><a href="viewlab.php">View Lab</a></li>
                            <li><a href="deletelab.php">Delete Lab</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="managemachine.php">Manage Machine</a>
                    </li>

                    <li class="">
                        <a href="#devicemenu" data-toggle="collapse" aria-expanded="false">Device</a>
                        <ul class="collapse list-unstyled" id="devicemenu">
                            <li><a href="adddevice.php">Add Device</a></li>
                            <li><a href="viewdev.php">View Device</a></li>
                        </ul>
                    </li>
                    
                    <li class="">
                        <a href="#specmenu" data-toggle="collapse" aria-expanded="false">Specification</a>
                        <ul class="collapse list-unstyled" id="specmenu">
                            <li><a href="addspec.php">Add Specification</a></li>
                            <li><a href="deletespec.php">Delete Specification</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="viewmchistory.php">Computer History</a>
                    </li>
                    <li>
                        <a href="viewsummary.php">Stock Table</a>
                    </li>
                    <li class="">
                        <a href="#membermenu" data-toggle="collapse" aria-expanded="false">Members</a>
                        <ul class="collapse list-unstyled" id="membermenu">
                            <li><a href="add_member.php">Add Member</a></li>
                            <li><a href="view_member.php">View Members</a></li>
                            <li><a href="delete_member.php">Delete Member</a></li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="#complaintmenu" data-toggle="collapse" aria-expanded="false">Register a complaint</a>
                        <ul class="collapse list-unstyled" id="complaintmenu">
                            <li><a href="complaint_form.php">Complaint for PC</a></li>
                            <li><a href="device_complaint_form.php">Complaint for Hardware</a></li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="#requestmenu" data-toggle="collapse" aria-expanded="false">Send a Request</a>
                        <ul class="collapse list-unstyled" id="requestmenu">
                            <li><a href="request_form.php">Request for a PC</a></li>
                            <li><a href="issue_request.php">Request for Hardware</a></li>
                        </ul>
                    </li>
                    <li class="">
                        <a href="#contactmenu" data-toggle="collapse" aria-expanded="false">Contact</a>
                        <ul class="collapse list-unstyled" id="contactmenu">
                            <li><a href="#add_member.php">phadnis.anurag@gmail.com</a></li>
                            <li><a href="#view_member.php">krishlalwani1@gmail.com</a></li>

                        </ul>
                    </li>
                    <li class="">
                        <a href="techreport.php">Generate Technician Report</a>
                    </li>

                    <hr>
                    <li>
                        <a href="logout.php">Logout</a>
                    </li>
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
