<?php
include '../conn.php';
session_start();

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
</head>
<body>
    <?php include('includes/sidebar.php')?>

    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Main Content -->
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Cost Estimation</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>

            <!-- Cost Estimation Table and Form -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Manage Customization Cost Multipliers</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Customization Option</th>
                                            <th>Current Value</th>
                                            <th>Update Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Height Multiplier (per cm)</td>
                                            <td>1.2</td> <!-- Static value for demonstration -->
                                            <td><input type="number" step="0.01" class="form-control" name="height_multiplier" value="1.2" required></td>
                                        </tr>
                                        <tr>
                                            <td>Width Multiplier (per cm)</td>
                                            <td>1.1</td> <!-- Static value for demonstration -->
                                            <td><input type="number" step="0.01" class="form-control" name="width_multiplier" value="1.1" required></td>
                                        </tr>
                                        <tr>
                                            <td>Length Multiplier (per cm)</td>
                                            <td>1.3</td> <!-- Static value for demonstration -->
                                            <td><input type="number" step="0.01" class="form-control" name="length_multiplier" value="1.3" required></td>
                                        </tr>
                                        <tr>
                                            <td>Color Cost (for premium colors)</td>
                                            <td>20.00</td> <!-- Static value for demonstration -->
                                            <td><input type="number" step="0.01" class="form-control" name="color_cost" value="20.00" required></td>
                                        </tr>
                                        <tr>
                                            <td>Design Cost (for custom designs)</td>
                                            <td>50.00</td> <!-- Static value for demonstration -->
                                            <td><input type="number" step="0.01" class="form-control" name="design_cost" value="50.00" required></td>
                                        </tr>
                                        <tr>
                                            <td>Foot Part Cost (e.g., wheels, steel, metal)</td>
                                            <td>15.00</td> <!-- Static value for demonstration -->
                                            <td><input type="number" step="0.01" class="form-control" name="foot_part_cost" value="15.00" required></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary mt-3">Update Cost Multipliers</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php include('includes/footer.php'); ?> 
    </div>
</body>
</html>

