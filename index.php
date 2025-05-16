<?php
// Initialize session and database connection
session_start();
require_once 'auth_check.php'; // Contains your db_connect() and other auth functions

// Set default page variables
$pageTitle = "Job Application System";
$userLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['name'] ?? '';
$userEmail = $_SESSION['user_email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="static/styles.css">    
        <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding-top: 80px; 
        }
        .modal {
        background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #ffffff; 
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-primary.disabled {
            background-color: #aaa;
            cursor: not-allowed;
        }
        .containermodal {
        width: 100%;             
        max-width: 1000px;        
        margin: 0 auto;            
        padding: 0 15px;          
        box-sizing: border-box; 
        }

        h2, h5 {
            color: #007bff;
            
        }
        ul {
            margin-bottom: 20px;
            padding-left: 20px;
        }
        li {
            line-height: 1.6;
        }
        .modal-lg {
            max-width: 90%; 
        }

        .modal {
            background-color: rgba(0, 0, 0, 0.5); 
        }
        /* Success animation */
        .success-animation {
            margin: 0 auto;
            width: 80px;
        }

        .checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: block;
            stroke-width: 5;
            stroke: #28a745;
            stroke-miterlimit: 10;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }

        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 5;
            stroke-miterlimit: 10;
            stroke: #28a745;
            fill: none;
            animation: stroke .6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }

        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke .3s cubic-bezier(0.65, 0, 0.45, 1) .8s forwards;
        }

        @keyframes stroke {
            100% { stroke-dashoffset: 0; }
        }

        @keyframes scale {
            0%, 100% { transform: none; }
            50% { transform: scale3d(1.1, 1.1, 1); }
        }

        @keyframes fill {
            100% { box-shadow: inset 0 0 0 100px rgba(40, 167, 69, 0.1); }
        }
        .dropdown-menu {
            min-width: 250px;
            border: 1px solid rgba(0,0,0,0.1);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .dropdown-item {
            padding: 0.5rem 1rem;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .navbar {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        </style>
</head>
<body>

<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>Application Submitted!
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="success-animation">
                        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                        </svg>
                    </div>
                    <h4 class="text-success mt-3">Successfully Submitted!</h4>
                </div>
                
                <div class="confirmation-details">
                    <p><strong>Reference ID:</strong> <span class="text-primary"><?= htmlspecialchars($applicant_id) ?></span></p>
                    <p><strong>Confirmation sent to:</strong> <?= htmlspecialchars($applicant_email) ?></p>
                    
                    <div class="alert alert-light mt-3">
                        <h6>What happens next?</h6>
                        <ul class="small">
                            <li>Our team will review your application</li>
                            <li>You'll receive status updates via email</li>
                            <li>Process typically takes 5-7 business days</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Got it!</button>
                <a href="application-status.php?id=<?= $applicant_id ?>" class="btn btn-outline-primary">
                    Track Status
                </a>
            </div>
        </div>
    </div>
</div>
    <div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="privacyModalLabel">Privacy Policy Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="containermodal">
                        <h2 class="mb-3">Rules</h2>
                        <ul class="list-group">
                            <li class="list-group-item"><b>Consent is Mandatory:</b> Explicitly obtain consent before data collection or sharing.</li>
                            <li class="list-group-item"><b>Data Minimization:</b> Collect only necessary data for specific purposes.</li>
                            <li class="list-group-item"><b>Secure Storage:</b> Use encryption and security measures to protect stored data.</li>
                            <li class="list-group-item"><b>Transparency:</b> Provide clear privacy policies accessible at all times.</li>
                            <li class="list-group-item"><b>Accountability:</b> Audit data practices and designate a data protection officer.</li>
                            <li class="list-group-item"><b>User Rights:</b> Allow users to access, correct, and delete their data easily.</li>
                            <li class="list-group-item"><b>Data Retention Limits:</b> Retain data only for as long as necessary.</li>
                            <li class="list-group-item"><b>Third-Party Compliance:</b> Ensure third-party compliance with privacy standards.</li>
                        </ul>

                        <h2 class="mt-4 mb-3">Principles</h2>
                        <ul class="list-group">
                            <li class="list-group-item"><b>Respect for Privacy:</b> Treat privacy as a fundamental human right.</li>
                            <li class="list-group-item"><b>Purpose Specification:</b> Clearly define the purpose of data collection.</li>
                            <li class="list-group-item"><b>Security by Design:</b> Incorporate security measures throughout data handling.</li>
                            <li class="list-group-item"><b>Fairness and Integrity:</b> Prevent misuse or exploitation of user data.</li>
                            <li class="list-group-item"><b>User Empowerment:</b> Equip users with tools to control their data.</li>
                            <li class="list-group-item"><b>Compliance with Legal Standards:</b> Follow global privacy laws like GDPR and CCPA.</li>
                            <li class="list-group-item"><b>Continuous Improvement:</b> Adapt to new privacy risks and technologies.</li>
                            <li class="list-group-item"><b>No Harm Policy:</b> Ensure data practices do not harm users.</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="acceptButton" class="btn btn-primary disabled">Accept Policy</button>
                </div>
            </div>
        </div>
    </div>

    <header class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="logo.png" alt="Logo" height="50">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="howtoapply.php">Apply</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about-us">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#job-section">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="adminlogin.php">Admin</a></li>
                </ul>
                <div class="user-menu d-flex align-items-center">
                <?php if (is_logged_in()) : ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-2"></i>
                            <span class="user-name"><?= htmlspecialchars(get_user_name()) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <div class="dropdown-header px-3 py-2">
                                    <div class="fw-bold"><?= htmlspecialchars(get_user_name()) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars(get_user_email()) ?></small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user me-2"></i> Profile
                                </a>
                            
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else : ?>
                    <div class="btn-group" role="group">
                        <a href="login.php" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                        <a href="register.php" class="btn btn-primary">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            </div>
        </div>
    </header>
    <main class="container mt-4">
        <h1>Welcome to our Job Portal</h1>
        <?php if (is_logged_in()) : ?>
            <p>Welcome back, <?= htmlspecialchars(get_user_name()) ?>!</p>
        <?php else : ?>
            <p>Please login or register to apply for jobs.</p>
        <?php endif; ?>
    </main>
    <div class="job-section" id="job-section">
        <div class="job-title">Available Jobs:</div>
        <div class="job-carousel">
            <div class="job-container">
                <div class="job-card"><strong>ADMINISTRATIVE OFFICER</strong><p>NON-TEACHING</p></div>
                <div class="job-card"><strong>TEACHER I</strong><p>ELEMENTARY LEVEL</p></div>
                <div class="job-card"><strong>GUIDANCE COUNSELOR</strong><p>STUDENT SERVICES</p></div>
                <div class="job-card"><strong>ACCOUNTANT</strong><p>FINANCE DEPARTMENT</p></div>
                <div class="job-card"><strong>IT SUPPORT SPECIALIST</strong><p>TECHNOLOGY SERVICES</p></div>
                <div class="job-card"><strong>LIBRARIAN</strong><p>RESOURCE MANAGEMENT</p></div>
                <div class="job-card"><strong>SCHOOL NURSE</strong><p>HEALTH & WELLNESS</p></div>
                <div class="job-card"><strong>CAMPUS ENGINEER</strong><p>FACILITIES MANAGEMENT</p></div>
                <div class="job-card"><strong>REGISTRAR OFFICER</strong><p>STUDENT RECORDS</p></div>
                <div class="job-card"><strong>ADMINISTRATIVE OFFICER</strong><p>NON-TEACHING</p></div>
                <div class="job-card"><strong>TEACHER I</strong><p>ELEMENTARY LEVEL</p></div>
            </div>
        </div>
    </div>

<div class="job-application container mt-5">
    <div class="row align-items-center">

        <div class="col-md-6 text-start">
            <h1 class="main-title">
                <span class="blue-text">Human</span> Resource <span class="blue-text">Office</span>
            </h1>
            <h3 class="subheading">Join Our Team at Jobyment!</h3>
            <p class="description">
                At Jobyment, we help recent graduates transition from campus to career. 
                If you're passionate about supporting fellow graduates as they take their next step, 
                join us in making a difference!
            </p>

            <div class="action-buttons">
            <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>

                <button class="see-jobs-btn"><a href="#we-are-hiring" class="see-jobs-link">See Below Jobs</a></button>
                
            </div>
        </div>

        <div class="col-md-6 text-end">
            <img src="picture.jfif" alt="Human Resource" class="job-image">
        </div>
    </div>
</div>

<div id="we-are-hiring">
<div class="hiring-header text-center mb-4">
    <h1>
        <span class="blue-text">WE</span> ARE <span class="blue-text">HIRING</span>
    </h1>
</div>
<div id="page-1" class="columns-rows-page">
    <div class="row">
        <div class="col-md-6">
            <div class="content-box">
                <h4>Admin Officer</h4>
                <p>Responsible for administrative tasks and office coordination.</p>
                <div class="action-buttons">
                    <button class="view-btn"><a href="qualification/view1.html">View Qualification</a></button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-box">
                <h4>Teacher I</h4>
                <p>Elementary level teaching position focused on student learning.</p>
                <div class="action-buttons">
                    <button class="view-btn"><a href="qualification/view2.html">View Qualification</a></button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="content-box">
                <h4>Guidance Counselor</h4>
                <p>Supports student emotional well-being and academic guidance.</p>
                <div class="action-buttons">
                    <button class="view-btn"><a href="qualification/view3.html">View Qualification</a></button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-box">
                <h4>IT Support</h4>
                <p>Provides technical assistance and ensures IT systems are operational.</p>
                <div class="action-buttons">
                    <button class="view-btn"><a href="qualification/view4.html">View Qualification</a></button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="content-box">
                <h4>Accountant</h4>
                <p>Handles financial records, budgets, and auditing processes.</p>
                <div class="action-buttons">
                    <button class="view-btn"><a href="qualification/view5.html">View Qualification</a></button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-box">
                <h4>Librarian</h4>
                <p>Manages library resources and supports research activities.</p>
                <div class="action-buttons">
                    <button class="view-btn"><a href="qualification/view6.html">View Qualification</a></button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="page-2" class="columns-rows-page d-none">
    <div class="row">
        <div class="col-md-6">
            <div class="content-box">
                <h4>HR Officer</h4>
                <p>Oversees recruitment and employee relations within the organization.</p>
                <div class="action-buttons">
                    <button class="view-btn">View Qualification</button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-box">
                <h4>Marketing Specialist</h4>
                <p>Develops and implements marketing strategies to drive engagement.</p>
                <div class="action-buttons">
                    <button class="view-btn">View Qualification</button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="content-box">
                <h4>Project Manager</h4>
                <p>Coordinates project schedules and ensures timely delivery.</p>
                <div class="action-buttons">
                    <button class="view-btn">View Qualification</button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-box">
                <h4>Data Analyst</h4>
                <p>Analyzes data to inform business decisions and strategy planning.</p>
                <div class="action-buttons">
                    <button class="view-btn">View Qualification</button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="content-box">
                <h4>Graphic Designer</h4>
                <p>Creates visual concepts for branding and marketing materials.</p>
                <div class="action-buttons">
                    <button class="view-btn">View Qualification</button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-box">
                <h4>Content Writer</h4>
                <p>Produces engaging content for web and print media.</p>
                <div class="action-buttons">
                    <button class="view-btn">View Qualification</button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="page-3" class="columns-rows-page d-none">
    <div class="row">
        <div class="col-md-6">
            <div class="content-box">
                <h4>Customer Support</h4>
                <p>Assists customers by addressing inquiries and resolving issues.</p>
                <div class="action-buttons">
                    <button class="view-btn">View Qualification</button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-box">
                <h4>Operations Manager</h4>
                <p>Manages daily operations to ensure organizational efficiency.</p>
                <div class="action-buttons">
                    <button class="view-btn">View Qualification</button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="content-box">
                <h4>HR Manager</h4>
                <p>Leads HR initiatives, including recruitment and training programs.</p>
                <div class="action-buttons">
                    <button class="view-btn">View Qualification</button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-box">
                <h4>Finance Analyst</h4>
                <p>Analyzes financial data to support strategic decision-making.</p>
                <div class="action-buttons">
                    <button class="view-btn">View Qualification</button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="content-box">
                <h4>Supply Chain Manager</h4>
                <p>Coordinates supply chain operations to optimize efficiency.</p>
                <div class="action-buttons">
                    <button class="view-btn">View Qualification</button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-box">
                <h4>Software Engineer</h4>
                <p>Develops and maintains software solutions for business needs.</p>
                <div class="action-buttons">
                    <button class="view-btn">View Qualification</button>
                    <button onclick="window.location.href='forms.html'" class="btn btn-primary">Apply Now</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="navigation-controls">
    <button class="arrow-btn left-arrow">&larr;</button>
    <span class="nav-dot active"></span>
    <span class="nav-dot"></span>
    <span class="nav-dot"></span>
    <button class="arrow-btn right-arrow">&rarr;</button>
</div>

<section class="about-us" id="about-us">
    <h1>About Us</h1>
    <p>
        The Human Resource Office is dedicated to helping job seekers find meaningful career opportunities through Jobyment.
        We support applicants in their job search by providing guidance, resources, and job placement assistance.
        Our team is committed to empowering individuals with the tools and connections needed to secure the right job.
        Whether you're starting your career or looking for new opportunities, we're here to help you succeed.
    </p>
    <br>

    <h2 style="font-weight: bold; color: #000000;">Contact Us</h2>
    <div class="contact-container">

        <div class="contact-left">
            <p>Email: <a href="mailto:jobyment@gmail.com">jobyment@gmail.com</a></p><br>
            <p>Landline: 0657-455-765</p>
        </div>

        <div class="contact-right">
            <p>Mobile Number: +639 1322 39310</p><br>
            <p>Address: 1226 123 Los Baños, Laguna</p>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-left">
            <img src="whitelogo.png" alt="Jobyment Logo" class="footer-logo">
        </div>

        <div class="footer-center">
            <h3>JOBYMENT</h3>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Document</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Admin</a></li>
            </ul>
        </div>

        <div class="footer-right">
            <h3>CONTACT US</h3>
            <p>Email: <a href="mailto:jobapplication@gmail.com" class="no-underline">jobyment@gmail.com</a></p>
            <p>Mobile: +123445566778</p>
            <p>Address: 123 Los Baños, Laguna</p>
            <p>Landline: 0657-455-765</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>Looking for a job? Browse our open positions today!</p>
    </div>
    <div class="footers-bottom">
        <p>© 2025 JOBYMENT. All rights reserved.</p>
    </div>
    
</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="static/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>