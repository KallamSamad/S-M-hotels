<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$role = $_SESSION['role'] ?? 'guest';
?>

<nav class="navbar navbar-expand-lg nav">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="index.php">S&M</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarDropdowns">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarDropdowns">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">

        <li class="nav-item">
          <a class="nav-link text-white" href="index.php">Home</a>
        </li>

        <?php if ($role === "staff"): ?>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Hotels</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="viewHotel.php">View Hotels</a></li>
              <li><a class="dropdown-item" href="addHotel.php">Add New Hotel</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Rooms</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="viewRoom.php">View Rooms</a></li>
              <li><a class="dropdown-item" href="addRoom.php">Add New Room</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Bookings</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="staffBookings.php">View Bookings</a></li>
              <li><a class="dropdown-item" href="viewRoom.php">Add New Booking</a></li>
            </ul>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Feedback</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="viewFeedback.php">View Feedback</a></li>
              <li><a class="dropdown-item" href="staffBookings.php">Add Feedback</a></li>
            </ul>
          </li>

        <?php else: ?>

          <li class="nav-item">
            <a class="nav-link text-white" href="userBookings.php">Book a Room</a>
          </li>


          <li class="nav-item">
            <a class="nav-link text-white" href="viewBookings.php">My Bookings</a>
          </li>

          <li class="nav-item">
            <a class="nav-link text-white" href="userFeedback.php">My Feedback</a>
          </li>

        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link text-white" href="aboutus.php">About Us</a>
        </li>

      </ul>
    </div>
  </div>
</nav>
