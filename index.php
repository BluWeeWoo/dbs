<?php
session_start();

if (!isset($_SESSION['admin_ID'])) {
    header("Location: login.php");
    exit();
}

require_once('classes/database.php');
$con = new database();
$sweetAlertConfig="";

if(isset($_POST['add_student'])){
  $firstname = $_POST['first_name'];
  $lastname = $_POST['last_name'];
  $email = $_POST['email']; 
  $admin_id =  $_SESSION['admin_ID'];
  $userID = $con->addStudent($firstname, $lastname, $email, $admin_id);

  if ($userID) {
    $sweetAlertConfig = "
      <script>
      Swal.fire({
        icon: 'success',
        title: 'Student Successfully Enrolled',
        text: 'Your account has been created successfully',
        confirmButtonText: 'OK'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'index.php'; // or wherever appropriate
        }
      });
      </script>";
  } else {
    $sweetAlertConfig = "
      <script>
      Swal.fire({
        icon: 'error',
        title: 'Registration Failed',
        text: 'Please try again later',
        confirmButtonText: 'OK'
      });
      </script>";
  }
}

  
if(isset($_POST['add_course'])){
  $coursename = $_POST['course_name'];
  $admin_id =  $_SESSION['admin_ID'];

  // Assuming you have a method like this
  $courseID = $con->addCourse($coursename, $admin_id);

  if($courseID){
    $sweetAlertConfig = "
    <script>
    Swal.fire({
      icon: 'success',
      title: 'Course Successfully Created',
      text: 'The course has been added successfully.',
      confirmButtonText: 'OK'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = 'index.php';
      }
    });
    </script>";
  } else {
    $sweetAlertConfig = "
    <script>
    Swal.fire({
      icon: 'error',
      title: 'Course Creation Failed',
      text: 'Please try again later',
      confirmButtonText: 'OK'
    });
    </script>";
  }
}
  
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student & Course CRUD (PHP PDO)</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
   <link rel="stylesheet" href="./package/dist/sweetalert2.css">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4 text-center">Student Records</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">Add New Student</button>
    <table class="table table-bordered table-hover bg-white text-center" >
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
<?php
// Fetch students from the database
$students = $con->getStudents(); // Assuming you have a method to fetch all students
foreach ($students as $student) {
  

?>
        <tr>
          <td><?php echo $student['student_id']?> </td>
          <td><?php echo $student['student_FN'] . ' ' . $student['student_LN']?></td>
          <td><?php echo $student['student_email']?></td>

          <td>
          <div class="btn-group" role="group">
            <form action="update_student.php" method="POST">
              <input type="hidden" name="student_id" value="<?php echo $student['student_id']?>">
              <button type="submit" class="btn btn-sm btn-warning">Edit</button> 


</form>
              
          </div>




            
            <button class="btn btn-sm btn-danger">Delete</button>
          </td>
        </tr>
        <?php
}
?>
      </tbody>
    </table>

    <h2 class="mb-4 mt-5">Courses</h2>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addCourseModal">Add Course</button>
    <table class="table table-bordered table-hover bg-white text-center">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Course Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
// Fetch students from the database
$courses = $con->getCourses(); // Assuming you have a method to fetch all students
foreach ($courses as $course) {
  

?>
        <tr>
          <td><?php echo $course['course_id']?> </td>
          <td><?php echo $course['course_name']?></td>

          <td>
          <div class="btn-group" role="group">
            <form action="update_course.php" method="POST">
              <input type="hidden" name="course_id" value="<?php echo $course['course_id']?>">
              <button type="submit" class="btn btn-sm btn-warning">Edit</button> 

</form>
              
          </div>

            <button class="btn btn-sm btn-danger">Delete</button>
          </td>
        </tr>
        <?php
}
?>
      </tbody>
    </table>


    <h2 class="mb-4 mt-5">Enrollments</h2>
    <button class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#enrollStudentModal">Enroll Student</button>
    <table class="table table-bordered table-hover bg-white">
      <thead class="table-dark">
        <tr>
          <th>Enrollment ID</th>
          <th>Student Name</th>

          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Juan Dela Cruz</td>
          <td>BS Information Technology</td>
          <td>
            <button class="btn btn-sm btn-warning">Edit</button>
            <button class="btn btn-sm btn-danger">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Add Student Modal -->
  <div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
      <form class="modal-content" method="POST" action="">
        <div class="modal-header">
          <h5 class="modal-title">Add Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="first_name" class="form-control mb-2" placeholder="First Name" required>
          <input type="text" name="last_name" class="form-control mb-2" placeholder="Last Name" required>
          <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        </div>
        <div class="modal-footer">
          <button type="submit" name="add_student" class="btn btn-primary">Add</button>
        </div>
        <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
        <script src="./package/dist/sweetalert2.js"></script>
        <?php echo $sweetAlertConfig; ?>
      </form>
    </div>
  </div>

  <!-- Add Course Modal -->
  <div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog">
      <form class="modal-content"id="courseform" method="POST" action="">
        <div class="modal-header">
          <h5 class="modal-title">Add Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="course_name" id="course_name" class="form-control mb-2" placeholder="Course Name" required>
          <div class="invalid-feedback">Course name is required.</div>
        </div>
        <div class="modal-footer">
          <button type="submit" id="add_course" name="add_course" class="btn btn-success">Add Course</button>
        </div>
          <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
        <script src="./package/dist/sweetalert2.js"></script>
        <?php echo $sweetAlertConfig; ?>
      </form>
    </div>
  </div>

  <!-- Enroll Student Modal -->
  <div class="modal fade" id="enrollStudentModal" tabindex="-1">
    <div class="modal-dialog">
      <form class="modal-content" method="POST" action="enroll_student.php">
        <div class="modal-header">
          <h5 class="modal-title">Enroll Student to Course</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="student_id" class="form-control mb-2" placeholder="Student ID" required>
          <input type="text" disabled name="student_name" class="form-control mb-2" placeholder="Student Name" required>
          
          <select name="course_id" class="form-control" required>
            <option value="">Select Course</option>
            <option value="1">Computer Science</option>
            <option value="2">Information Technology</option>
            <option value="3">Software Engineering</option>
            <option value="4">Data Science</option>
          </select>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-info">Enroll</button>
        </div>
      </form>
    </div>
  </div>

  <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>

  <script>
  function validateField(field, validationFn) {
    field.addEventListener('input', () => {
      if (validationFn(field.value)) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
      } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
      }
    });
  }

  // Real-time username validation using AJAX
  const checkCourseAvailability = (courseField) => {
    courseField.addEventListener('input', () => {
      const course_name = courseField.value.trim();

      if (course_name === '') {
        courseField.classList.remove('is-valid');
        courseField.classList.add('is-invalid');
        courseField.nextElementSibling.textContent = 'Course is required.';
        add_course.disabled = true;//Disable button
        return;
      }

      // Send AJAX request to check username availability
      fetch('ajax/check_course.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `course_name=${encodeURIComponent(course_name)}`,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.exists) {
            courseField.classList.remove('is-valid');
            courseField.classList.add('is-invalid');
            courseField.nextElementSibling.textContent = 'Course is already taken.';
            add_course.disabled = true;//Disable button
          } else {
            courseField.classList.remove('is-invalid');
            courseField.classList.add('is-valid');
            courseField.nextElementSibling.textContent = '';
            add_course.disabled = false;//Enable button
          }
        })
        .catch((error) => {
          console.error('Error:', error);
          add_course.disabled = true;//Disable button
        });
    });
  };


  // Get form fields
  const course_name = document.getElementById('course_name');


  // Attach real-time validation to each field
  checkCourseAvailability(course_name);
 
  // Form submission validation
  document.getElementById('courseform').addEventListener('submit', function (e) {
    //e.preventDefault(); // Prevent form submission for validation

    let isValid = true;

    // Validate all fields on submit
    [course_name].forEach((field) => {
      if (!field.classList.contains('is-valid')) {
        field.classList.add('is-invalid');
        isValid = false;
      }
    });

    // If all fields are valid, submit the form
    if (isValid) {
      this.submit();
    }
  });
</script>
  
</body>
</html>


