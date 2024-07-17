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
                    <h3 class="fw-bold mb-3">Furniture Type</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0"></div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List</h4>
                            <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                                <i class="fa fa-plus"></i> Add Type
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                    <?php
                        if(isset($_POST['addType'])) {
                            $type = $_POST['type'];

                            $sql = "INSERT INTO furniture_type (type) VALUES ('$type')";
                             
                            if(mysqli_query($conn, $sql)) {
                                echo '<script>window.location.href= "furniture_type.php?success=true";</script>';
                                exit(); 
                            } else {
                                echo '<script>window.location.href="furniture_type.php?error=true";</script>';
                                exit();
                            }  
                        }

                        if(isset($_POST['editType'])) {
                            $id = $_POST['id'];
                            $type = $_POST['type'];

                            $sql = "UPDATE furniture_type SET type='$type' WHERE fid='$id'";
                             
                            if(mysqli_query($conn, $sql)) {
                                echo '<script>window.location.href= "furniture_type.php?success=true";</script>';
                                exit(); 
                            } else {
                                echo '<script>window.location.href="furniture_type.php?error=true";</script>';
                                exit();
                            }  
                        }

                        if(isset($_POST['archiveType'])) {
                            $id = $_POST['id'];

                            $sql = "DELETE FROM furniture_type WHERE fid='$id'";
                             
                            if(mysqli_query($conn, $sql)) {
                                echo '<script>window.location.href= "furniture_type.php?success=true";</script>';
                                exit(); 
                            } else {
                                echo '<script>window.location.href="furniture_type.php?error=true";</script>';
                                exit();
                            }  
                        }
                    ?>
                        <!-- Add Modal -->
                        <form action="" method="POST">
                        <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold"> New</span>
                                            <span class="fw-light"> Furniture </span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Type of Furniture</label>
                                                        <input id="addType" name="type" type="text" class="form-control" placeholder="" />
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" name="addType" class="btn btn-primary">Add</button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>

                        <!-- Edit Modal -->
                        <form action="" method="POST">
                        <div class="modal fade" id="editRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <h5 class="modal-title">
                                            <span class="fw-mediumbold"> Edit</span>
                                            <span class="fw-light"> Furniture </span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default">
                                                        <label>Type of Furniture</label>
                                                        <input id="editType" name="type" type="text" class="form-control" placeholder="" />
                                                        <input id="editId" name="id" type="hidden" />
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="submit" name="editType" class="btn btn-primary">Save</button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>

                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Type of Furniture</th>
                                        <th style="width: 10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $sql = "SELECT * FROM furniture_type";
                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $id = $row['fid'];
                                            $type = $row['type'];
                                ?>
                                    <tr>
                                        <td><?php echo $id; ?></td>
                                        <td><?php echo $type; ?></td>
                                        <td>
                                            <div class="form-button-action">
                                                <button type="button" data-bs-toggle="tooltip" title="Edit Task" class="btn btn-link btn-primary btn-lg" onclick="editType('<?php echo $id; ?>', '<?php echo $type; ?>')">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" data-bs-toggle="tooltip" title="Archive" class="btn btn-link btn-danger" onclick="archiveType('<?php echo $id; ?>')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='2'>No records found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <!-- <?php include('includes/footer.php'); ?> -->

    <!-- Scripts -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Sweet Alert -->
    <script src="admin/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    <script src="assets/js/demo.js"></script>

    <script>
        $(document).ready(function () {
            $("#add-row").DataTable({
                pageLength: 5,
            });

            var action = 
                '<td><div class="form-button-action">' +
                '<button type="button" data-bs-toggle="tooltip" title="Edit Task" class="btn btn-link btn-primary btn-lg">' +
                '<i class="fa fa-edit"></i></button> ' +
                '<button type="button" data-bs-toggle="tooltip" title="Remove" class="btn btn-link btn-danger">' +
                '<i class="fa fa-times"></i></button></div></td>';

            $("#addRowButton").click(function () {
                $("#add-row").dataTable().fnAddData([
                    $("#addName").val(),
                    $("#addPosition").val(),
                    $("#addOffice").val(),
                    action,
                ]);
                $("#addRowModal").modal("hide");
            });
        });

        function editType(id, type) {
            $('#editId').val(id);
            $('#editType').val(type);
            $('#editRowModal').modal('show');
        }

        function archiveType(id) {
            if(confirm("Are you sure you want to archive this type?")) {
                $.post('', {archiveType: true, id: id}, function(response) {
                    window.location.href = "furniture_type.php?success=true";
                });
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    function showModal(){
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Furniture Type Added Successfully',
            showConfirmButton: false
        });
    }

    function checkExistParam() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success') && urlParams.get('success') === 'true') {
            showModal();
        }
    }
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function editType(id, type) {
        $('#editId').val(id);
        $('#editType').val(type);
        $('#editRowModal').modal('show');
    }

    function archiveType(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to archive this type!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, archive it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('', { archiveType: true, id: id }, function(response) {
                    Swal.fire({
                        title: 'Archived!',
                        text: 'The furniture type has been archived.',
                        icon: 'success'
                    }).then(() => {
                        window.location.href = "furniture_type.php?success=true";
                    });
                });
            }
        });
    }
</script>

    window.onload = checkExistParam; 
    </script>
</body>
</html>
