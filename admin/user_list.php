<?php 
session_start();
include '../conn.php';

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
    <!-- Include jsPDF and autoTable library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.26/jspdf.plugin.autotable.min.js"></script>
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
                    <h3 class="fw-bold mb-3">User Report</h3>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <button onclick="printTable()" class="btn btn-primary">Print Table</button>
                    <button onclick="generatePDF()" class="btn btn-secondary">Download PDF</button>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">List of User</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="add-row" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>Address</th>
                                        <th>Phone Number</th>
                                        <th>Type of User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    $sql = "SELECT uid, username, email, firstname, lastname, gender, contact, address, levelid, status
                                    FROM useraccount 
                                    JOIN userinfo ON useraccount.infoid = userinfo.infoid 
                                    WHERE useraccount.status = 1 AND useraccount.levelid IN (1, 2, 3)";
                                    $result = mysqli_query($conn, $sql);

                                    if ($result && mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $uid = $row['uid'];
                                            $firstname = $row['firstname'];
                                            $lastname = $row['lastname'];
                                            $name = $firstname . ' ' . $lastname;
                                            $username = $row['username'];
                                            $email = $row['email'];
                                            $gender = $row['gender'];
                                            $contact = $row['contact'];
                                            $address = $row['address'];
                                            $level = $row['levelid'];
                                            $type = '';
                                            switch ($level) {
                                                case 1:
                                                    $type = 'Admin';
                                                    break;
                                                case 2:
                                                    $type = 'Staff';
                                                    break;
                                                case 3:
                                                        $type = 'Customer';
                                                        break;
                                                default:
                                                    $type = 'Unknown';
                                                    break;
                                            }
                                        ?> 
                                    <tr>                                     
                                        <td><?php echo $name ?></td>
                                        <td><?php echo $address ?></td>                              
                                        <td><?php echo $contact ?></td>
                                        <td><?php echo $type ?></td>
                                    </tr>
                                    <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No records found</td></tr>";
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
    <?php include('includes/footer.php'); ?>
    <?php include ('includes/tables.php');?>
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('.archive-button').click(function() {
        var userid = $(this).data('account-id');
        $('#userid').val(userid);
    });
});

$(document).ready(function() {
    $('.edit-button').click(function() {
        var userID = $(this).data('account-id');
        var fname = $(this).data('account-fname');
        var lname = $(this).data('account-lname'); 
        var gender = $(this).data('account-gender');
        var contact = $(this).data('account-contact');
        var address = $(this).data('account-address');
        var email = $(this).data('account-email');

        $('#userID').val(userID);
        $('#editFirstName').val(fname);
        $('#editLastName').val(lname);
        $('#editContact').val(contact);
        $('#editAddress').val(address);
        $('#editEmail').val(email);
    });
});

function showAlert(type, message) {
    Swal.fire({
        icon: type,
        text: message,
    });
}

function checkURLParams() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('exist') && urlParams.get('exist') === 'true') {
        showAlert('warning', 'Username Already Exists');
    } else if (urlParams.has('success') && urlParams.get('success') === 'true') {
        showAlert('success', 'Account added successfully');
    } else if (urlParams.has('error') && urlParams.get('error') === 'true') {
        showAlert('error', 'Something went wrong!');
    } else if (urlParams.has('update') && urlParams.get('update') === 'true') {
        showAlert('success', 'Account updated successfully');
    } else if (urlParams.has('errorpassword') && urlParams.get('errorpassword') === 'true') {
        showAlert('error', 'Password do not match');
    } else if (urlParams.has('archive') && urlParams.get('archive') === 'true') {
        showAlert('success', 'Account archived successfully');
    }
}

window.onload = checkURLParams;

function printTable() {
    // Create a new window
    var printWindow = window.open('', '', 'height=600,width=800');
    
    // Get the HTML of the table
    var tableHTML = document.querySelector("#add-row").outerHTML;
    
    // Write HTML to the new window
    printWindow.document.write('<html><head><title>Print Table</title>');
    printWindow.document.write('<style>table {width: 100%; border-collapse: collapse;} th, td {border: 1px solid black; padding: 8px; text-align: left;} th {background-color: #f2f2f2;}</style>');
    printWindow.document.write('</head><body >');
    printWindow.document.write('<h2>User Report</h2>');
    printWindow.document.write(tableHTML);
    printWindow.document.write('</body></html>');
    
    // Close the document to finish loading
    printWindow.document.close();
    
    // Wait for the document to be fully loaded before printing
    printWindow.onload = function() {
        printWindow.print();
    };
}

async function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Add title
    doc.setFontSize(18);
    doc.text("User Report", 14, 20);

    // Add table
    const table = document.querySelector("#add-row");
    const rows = Array.from(table.querySelectorAll("tbody tr")).map(tr => {
        return Array.from(tr.querySelectorAll("td")).map(td => td.innerText);
    });
    const columns = Array.from(table.querySelectorAll("thead th")).map(th => th.innerText);
    
    doc.autoTable({
        head: [columns],
        body: rows
    });

    // Save PDF
    doc.save('user-report.pdf');
}
</script>

</html>
