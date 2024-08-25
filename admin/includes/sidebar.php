<div class="wrapper">
  <!-- Sidebar -->
  <div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
      <!-- Logo Header -->
      <div class="logo-header" data-background-color="dark">
        <a href="index.php" class="logo">
          <img src="assets/img/logo.png" alt="navbar brand" class="navbar-brand" height="20" />
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
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
      <div class="sidebar-content">
        <ul class="nav nav-secondary">
          <li class="nav-item">
            <a href="index.php">
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="user_management.php">
              <i class="fas fa-user-tie"></i>
              <p>User Management</p>
            </a>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#base">
              <i class="fas fa-box"></i>
              <p>Inventory Management</p>

              <span class="caret"></span>
            </a>
            <div class="collapse" id="base">
              <ul class="nav nav-collapse">
                <!-- <li>
                      <a href="product_category.php">
                        <span class="sub-item">Product Category </span>
                      </a>
                    </li> -->

                <li>
                  <a href="product_listing.php">
                    <span class="sub-item">Product</span>
                  </a>
                </li>
                <li>
                  <a href="components/gridsystem.html">
                    <span class="sub-item">Stock Level</span>
                  </a>
                </li>
                <li>
                  <a href="furniture_type.php">
                    <span class="sub-item">Furniture Type</span>
                  </a>
                </li>
                <li>
                  <a href="inventoryrecord.php">
                    <span class="sub-item">Inventory Record</span>
                  </a>
                </li>
                <!-- <li>
                      <a href="components/notifications.html">
                        <span class="sub-item">Inventory Report</span>
                      </a>
                    </li>    -->
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#transaction">
              <i class="fas fa-receipt"></i>
              <p>Sales Transaction</p>


              <span class="caret"></span>
            </a>
            <div class="collapse" id="transaction">
              <ul class="nav nav-collapse">
                <li>
                  <a href="orders.php">
                    <span class="sub-item">Ordering</span>
                  </a>
                </li>
                <li>
                  <a href="sales_record.php">
                    <span class="sub-item">Sales Record</span>
                  </a>
                </li>
                <!-- <li>
                      <a href="components/gridsystem.html">
                        <span class="sub-item">Sales Report</span>
                      </a>
                    </li> -->
                <li>
                  <a href="payment.php">
                    <span class="sub-item">Payment</span>
                  </a>
                </li>
                <li>
                  <a href="components/notifications.html">
                    <span class="sub-item">Transaction History</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#customization">
              <i class="fas fa-cogs"></i>
              <p>Customization</p>

              <span class="caret"></span>
            </a>
            <div class="collapse" id="customization">
              <ul class="nav nav-collapse">
                <li>
                  <a href="materials.php">
                    <span class="sub-item">Materials</span>
                  </a>
                </li>
                <li>
                  <a href="customize.php">
                    <span class="sub-item">Customize Options</span>
                  </a>
                </li>
                <li>
                  <a href="estimation.php">
                    <span class="sub-item">Cost Estimation</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a href="paymentoption.php">
              <i class="fas fa-money-bill"></i>
              <p>Payment Option</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="shipping.php">
              <i class="fas fa-truck"></i>
              <p>Shipping</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#">
              <i class="fas fa-star"></i>
              <p>Reviews & Rating</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="return.php">
              <i class="fas fa-box"></i>
              <p>Return & Refund</p>
            </a>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#supplier">
              <i class="fas fa-users"></i>
              <p>Supplier</p>

              <span class="caret"></span>
            </a>
            <div class="collapse" id="supplier">
              <ul class="nav nav-collapse">
                <li>
                  <a href="supplier.php">
                    <span class="sub-item">Supplier</span>
                  </a>
                </li>
                <li>
                  <a href="supplies.php">
                    <span class="sub-item">Supplies</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a data-bs-toggle="collapse" href="#sidebarLayouts">
              <i class="fas fa-envelope"></i>
              <p>Reports</p>
              <span class="caret"></span>
            </a>
            <div class="collapse" id="sidebarLayouts">
              <ul class="nav nav-collapse">
                <li>
                  <a href="user_list.php">
                    <span class="sub-item">Users List</span>
                  </a>
                </li>
                <li>
                  <a href="inventory_report.php">
                    <span class="sub-item">Inventory Report</span>
                  </a>
                </li>
                <li>
                  <a href="sales_report.php">
                    <span class="sub-item">Sales Report</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!-- End Sidebar -->