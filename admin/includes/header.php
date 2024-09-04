<?php
include('../conn.php'); // Include database connection

// Fetch username from database
if (isset($_SESSION['uid'])) {
    $user_id = $_SESSION['uid'];

    // Fetch username from the database based on user ID
    $sql_username = "SELECT * FROM useraccount 
                      JOIN userinfo ON userinfo.infoid = useraccount.infoid 
                      WHERE useraccount.infoid = '$user_id'";
    $result_username = mysqli_query($conn, $sql_username);

    if ($result_username && mysqli_num_rows($result_username) > 0) {
        $row_username = mysqli_fetch_assoc($result_username);
        $username = $row_username['username'];
        $email = $row_username['email'];
        $firstname = $row_username['firstname'];
    } else {
        // Default username if not found (you can handle this case accordingly)
        $username = "Guest";
    }
} else {
    // Handle case where session uid is not set (should ideally not reach here if your session management is correct)
    $username = "Guest";
}

$unread_notifications_query = "SELECT COUNT(*) AS unread_count FROM notification WHERE status = 'unread'";
$unread_notifications_result = mysqli_query($conn, $unread_notifications_query);
$unread_count = mysqli_fetch_assoc($unread_notifications_result)['unread_count'];


if(isset($_POST['submit'])) {
    $notificationId = $_POST['id'];
    
    $update_query = "UPDATE notification SET status = 'read' WHERE id = $notificationId";
    if(mysqli_query($conn, $update_query)) {
        
    } else {
        
    }
}
?> 
    
    <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.php" class="logo">
                <img
                  src="assets/img/kaiadmin/sti.png"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
    <!-- Navbar Header -->
     <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
             

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li
                  class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
                >
                  <a
                    class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                    <i class="fa fa-search"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                      <div class="input-group">
                        <input
                          type="text"
                          placeholder="Search ..."
                          class="form-control"
                        />
                      </div>
                    </form>
                  </ul>
                </li>
                <li class="nav-item topbar-icon dropdown hidden-caret">
    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-bell"></i>
        <span class="notification"><?php echo $unread_count; ?></span>
    </a>
    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
        <li>
            <div class="dropdown-title">
                You have <?php echo $unread_count; ?> new notifications
            </div>
        </li>
        <li>
            <div class="notif-scroll scrollbar-outer">
                <div class="notif-center">
                    <?php
                    // Query to get the latest notifications for the farmer
                    $notifications_query = "SELECT id, message, timestamp FROM notification ORDER BY timestamp DESC LIMIT 5";
                    $notifications_result = mysqli_query($conn, $notifications_query);

                    // Check if there are notifications
                    if(mysqli_num_rows($notifications_result) > 0) {
                        while ($notification = mysqli_fetch_assoc($notifications_result)) {
                            $notificationId = $notification['id'];
                    ?>
                            <form action="update_notification_status.php" method="post" style="display: inline;">
                                <input type="hidden" name="id" value="<?php echo $notificationId; ?>">
                                <button type="submit" class="dropdown-item" name="submit">
                                    <div class="notif-icon notif-primary">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div class="notif-content">
                                        <span class="block"><?php echo $notification['message']; ?></span>
                                        <span class="float-right text-muted text-sm time"><?php echo $notification['timestamp']; ?></span>
                                    </div>
                                </button>
                            </form>
                    <?php
                        }
                    } else {
                        // Display a message when there are no notifications
                    ?>
                        <div class="dropdown-item text-muted">No notifications</div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </li>
        <li>
            <a class="see-all" href="javascript:void(0);">See all notifications<i class="fa fa-angle-right"></i></a>
        </li>
    </ul>
</li>
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="assets/img/v.jpg"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <!-- fetch in the database the username -->
                      <span class="fw-bold"><?php echo htmlspecialchars($username); ?></span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                        
                          <div class="u-text">
                            <h4><?php echo htmlspecialchars($firstname); ?></h4>
                            <p class="text-muted"><?php echo htmlspecialchars($email); ?></p>
                        
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="profile.php">My Profile</a>
                        <a href="../index.php?logout=true">
                          <button type="button" class="dropdown-item">Logout</button>
                        </a>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>