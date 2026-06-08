<?php
$conn = new mysqli("localhost", "root", "", "academic_system");
if ($conn->connect_error) die("DB Connection Failed");

// HANDLE REQUESTS
$action = $_GET['action'] ?? "";

/* ------------------ ADD STUDENT ------------------ */
if ($action == "add_student") {
    $name = $_POST['name'];
    $reg = $_POST['reg_no'];
    $course = $_POST['course'];

    $conn->query("INSERT INTO students(name, reg_no, course)
    VALUES('$name','$reg','$course')");
    exit;
}

/* ------------------ ADD COURSE ------------------ */
if ($action == "add_course") {
    $name = $_POST['course_name'];
    $code = $_POST['course_code'];

    $conn->query("INSERT INTO courses(course_name, course_code)
    VALUES('$name','$code')");
    exit;
}

/* ------------------ ADD GRADE ------------------ */
if ($action == "add_grade") {
    $student = $_POST['student_id'];
    $course = $_POST['course_id'];
    $grade = $_POST['grade'];

    $conn->query("INSERT INTO grades(student_id, course_id, grade)
    VALUES('$student','$course','$grade')");
    exit;
}

/* ------------------ FETCH DATA ------------------ */
if ($action == "get_students") {
    $res = $conn->query("SELECT * FROM students");
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['reg_no']}</td>
        <td>{$row['course']}</td>
        </tr>";
    }
    exit;
}

if ($action == "get_courses") {
    $res = $conn->query("SELECT * FROM courses");
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['course_name']}</td>
        <td>{$row['course_code']}</td>
        </tr>";
    }
    exit;
}

if ($action == "get_grades") {
    $res = $conn->query("SELECT * FROM grades");
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['student_id']}</td>
        <td>{$row['course_id']}</td>
        <td>{$row['grade']}</td>
        </tr>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Academic System</title>

<style>
body {
    font-family: Arial;
    background: #f4f6f9;
    text-align: center;
}

.container {
    margin-top: 30px;
}

button {
    padding: 10px;
    margin: 5px;
    border: none;
    background: #007bff;
    color: white;
    cursor: pointer;
}

button:hover {
    background: #0056b3;
}

section {
    display: none;
    margin-top: 20px;
}

table {
    width: 80%;
    margin: auto;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #ccc;
    padding: 8px;
}
input, select {
    padding: 8px;
    margin: 5px;
}
</style>

</head>
<body>

<div class="container">

<h1>Academic Record System</h1>

<button onclick="show('students')">Students</button>
<button onclick="show('courses')">Courses</button>
<button onclick="show('grades')">Grades</button>

<!-- STUDENTS -->
<section id="students">
<h2>Students</h2>

<input id="sname" placeholder="Name">
<input id="sreg" placeholder="Reg No">
<input id="scourse" placeholder="Course">
<button onclick="addStudent()">Add</button>

<table>
<tr><th>ID</th><th>Name</th><th>Reg No</th><th>Course</th></tr>
<tbody id="studentTable"></tbody>
</table>
</section>

<!-- COURSES -->
<section id="courses">
<h2>Courses</h2>

<input id="cname" placeholder="Course Name">
<input id="ccode" placeholder="Course Code">
<button onclick="addCourse()">Add</button>

<table>
<tr><th>ID</th><th>Name</th><th>Code</th></tr>
<tbody id="courseTable"></tbody>
</table>
</section>

<!-- GRADES -->
<section id="grades">
<h2>Grades</h2>

<input id="gid_student" placeholder="Student ID">
<input id="gid_course" placeholder="Course ID">
<input id="ggrade" placeholder="Grade">
<button onclick="addGrade()">Add</button>

<table>
<tr><th>ID</th><th>Student</th><th>Course</th><th>Grade</th></tr>
<tbody id="gradeTable"></tbody>
</table>
</section>

</div>

<script>
function show(id){
    document.querySelectorAll("section").forEach(s => s.style.display = "none");
    document.getElementById(id).style.display = "block";
    loadData();
}

/* LOAD DATA */
function loadData(){
    fetch("?action=get_students").then(r=>r.text()).then(d=>{
        document.getElementById("studentTable").innerHTML = d;
    });

    fetch("?action=get_courses").then(r=>r.text()).then(d=>{
        document.getElementById("courseTable").innerHTML = d;
    });

    fetch("?action=get_grades").then(r=>r.text()).then(d=>{
        document.getElementById("gradeTable").innerHTML = d;
    });
}

/* ADD STUDENT */
function addStudent(){
    fetch("?action=add_student", {
        method:"POST",
        body:new URLSearchParams({
            name: sname.value,
            reg_no: sreg.value,
            course: scourse.value
        })
    }).then(()=>loadData());
}

/* ADD COURSE */
function addCourse(){
    fetch("?action=add_course", {
        method:"POST",
        body:new URLSearchParams({
            course_name: cname.value,
            course_code: ccode.value
        })
    }).then(()=>loadData());
}

/* ADD GRADE */
function addGrade(){
    fetch("?action=add_grade", {
        method:"POST",
        body:new URLSearchParams({
            student_id: gid_student.value,
            course_id: gid_course.value,
            grade: ggrade.value
        })
    }).then(()=>loadData());
}

show("students");
</script>

</body>
</html>