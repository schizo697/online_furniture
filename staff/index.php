<?php 
session_start();
include('../conn.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<?php include('includes/topbar.php'); ?>
<!-- Script para sa pie -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<?php include('includes/sidebar.php')?>
  <!-- Header -->
  <?php include('includes/header.php'); ?>

  <!-- Main Content -->
  <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Staff Dashboard</h3>
                <h6 class="op-7 mb-2">MPM: Furniture Shop</h6>
              </div>
              <div class="ms-md-auto py-2 py-md-0">
                <!-- <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                <a href="#" class="btn btn-primary btn-round">Add Customer</a> -->
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-info bubble-shadow-small"
                        >
                          <i class="fas fa-user-check"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Customers</p>
                          <h4 class="card-title">1303</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-couch"></i>
                                    </div>
                                </div>
                                <?php 
                                    // SQL query to count the number of suppliers
                                    $sql = "SELECT COUNT(*) AS furniture_count FROM `furniture`";
                                    $result = mysqli_query($conn, $sql);
                                    $furniture_count = 0;
                                    if ($result) {
                                        $row = mysqli_fetch_assoc($result);
                                        $furniture_count = $row['furniture_count'];
                                    } else {
                                        echo "<p>Error fetching supplier data: " . mysqli_error($conn) . "</p>";
                                    }
                                ?>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Products</p>
                                        <h4 class="card-title"><?php echo $furniture_count; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Code for Pie Chartss -->
                <div class="col-sm-6 col-md-6">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <?php
                                        // Query to count pending orders (osid = 1)
                                        $sql_pending = "SELECT COUNT(*) AS pending_count FROM `orders` WHERE `osid` = 1";
                                        $result_pending = mysqli_query($conn, $sql_pending);
                                        $pending_count = 0;
                                        if ($result_pending) {
                                            $row_pending = mysqli_fetch_assoc($result_pending);
                                            $pending_count = $row_pending['pending_count'];
                                        } else {
                                            echo "<p>Error fetching pending orders: " . mysqli_error($conn) . "</p>";
                                        }

                                        // Query to count successfully delivered  (osid = 3)
                                        $sql_delivered = "SELECT COUNT(*) AS delivered_count FROM `orders` WHERE `osid` = 3";
                                        $result_delivered = mysqli_query($conn, $sql_delivered);
                                        $delivered_count = 0;
                                        if ($result_delivered) {
                                            $row_delivered = mysqli_fetch_assoc($result_delivered);
                                            $delivered_count = $row_delivered['delivered_count'];
                                        } else {
                                            echo "<p>Error fetching delivered orders: " . mysqli_error($conn) . "</p>";
                                        }

                                        // Query to count returned orders (osid = 4)
                                        $sql_returned = "SELECT COUNT(*) AS returned_count FROM `orders` WHERE `osid` = 4";
                                        $result_returned = mysqli_query($conn, $sql_returned);
                                        $returned_count = 0;
                                        if ($result_returned) {
                                            $row_returned = mysqli_fetch_assoc($result_returned);
                                            $returned_count = $row_returned['returned_count'];
                                        } else {
                                            echo "<p>Error fetching returned orders: " . mysqli_error($conn) . "</p>";
                                        }
                                        ?>

                                        <p class="card-category">Order Status</p>
                                        <canvas id="orderStatusChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart Script -->
             <!-- Pending (Yellow) Delivered (Green) Returned (Red) -->
            <script>               
                console.log('Pending: <?php echo $pending_count; ?>');
                console.log('Delivered: <?php echo $delivered_count; ?>');
                console.log('Returned: <?php echo $returned_count; ?>');

                var ctx = document.getElementById('orderStatusChart').getContext('2d');
                var orderStatusChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ['Pending', 'Delivered', 'Returned'],
                        datasets: [{
                            label: 'Order Status',
                            data: [
                                <?php echo $pending_count; ?>, 
                                <?php echo $delivered_count; ?>, 
                                <?php echo $returned_count; ?>
                            ],
                            backgroundColor: [
                                'rgba(233, 244, 5, 63)', 
                                'rgba(39, 221, 3, 110)', 
                                'rgba(221, 10, 3, 2)' 
                            ],
                            borderColor: [
                                'rgba(233, 244, 5, 63)', 
                                'rgba(39, 221, 3, 110)', 
                                'rgba(221, 10, 3, 2)'  
                            ],
                            borderWidth: 1
                        }]
                    },
                    
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>

  <!-- Footer -->
  <?php include('includes/footer.php'); ?>

  <!-- Scripts -->
  <?php include('includes/script.php'); ?>
</body>
</html>
