<?php
session_start();
include 'db_connection.php';

//Simulation
$_SESSION['StudentID'] = 1001;
$_SESSION['Role'] = 'Student';


if (!isset($_SESSION['StudentID']) || $_SESSION['Role'] !== 'Student') {
    header("Location: login.php");
    exit;
}

$studentId = (int) $_SESSION['StudentID'];

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php';
$query = "
    SELECT 
        Courses.CourseCode, 
        Courses.CourseName, 
        Enrollments.Grade,
        Enrollments.GradeRemarks
    FROM 
        Enrollments
    INNER JOIN 
        Courses ON Enrollments.CourseID = Courses.CourseID
    WHERE 
        Enrollments.Grade IS NOT NULL
    LIMIT 1
";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $code    = $row['CourseCode'];
    $name    = $row['CourseName'];
    $gpa     = number_format($row['Grade'], 2);
    $remarks = $row['GradeRemarks'];
} else {
    $code = $name = $gpa = $remarks = "---";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Viewer</title>  
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="my_grades.css">
<body>
  <div class="container">
    <h1 class="page-title">MY GRADES</h1>

    <div class="card">
      <div class="column">
        <h3>Course Code</h3>
        <p><?php echo $code; ?></p>
      </div>
      <div class="divider"></div>
      <div class="column">
        <h3>Course Name</h3>
        <p><?php echo $name; ?></p>
      </div>
      <div class="divider"></div>
      <div class="column">
        <h3>GPA</h3>
        <p><?php echo $gpa; ?></p>
      </div>
      <div class="divider"></div>
      <div class="column">
        <h3>Remarks</h3>
        <p><?php echo $remarks; ?></p>
      </div>
    </div>
  </div>
</body>
</html>

