<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-wrapper {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .section-title {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
        .submit-btn {
            margin-top: 30px;
            width: 100%;
        }
        .file-requirements {
            font-size: 0.8em;
            color: #6c757d;
            margin-top: 5px;
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        .progress {
            height: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 0%;" id="form-progress"></div>
        </div>
    
        <form id="jobApplicationForm" action="process_application.php" method="POST" enctype="multipart/form-data">
    <div class="form-wrapper">
        <!-- Step 1: Basic Information -->
        <div class="form-step active" id="step1">
            <h2 class="text-center mb-4">Basic Information</h2>
            <form action="process_application.php" method="POST">
                <div class="mb-3">
                    <label for="full-name" class="form-label">Full Name *</label>
                    <input type="text" class="form-control" id="full-name" name="full-name" required minlength="3">
                    <div class="invalid-feedback">Please enter your full name (at least 3 characters).</div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address *</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="mb-3">
                    <label for="contact-number" class="form-label">Contact Number *</label>
                    <input type="tel" class="form-control" id="contact-number" name="contact-number" required pattern="[0-9]{10,15}">
                    <div class="invalid-feedback">Please enter a valid phone number (10-15 digits).</div>
                </div>
                <div class="mb-3">
                    <label for="job-category" class="form-label">Job Category *</label>
                    <select class="form-select" id="job-category" name="job-category" required>
                        <option value="">Select a category</option>
                        <option value="teaching">Teaching</option>
                        <option value="non-teaching">Non-Teaching</option>
                    </select>
                    <div class="invalid-feedback">Please select a job category.</div>
                </div>
                <div class="mb-3">
                    <label for="job-position" class="form-label">Job Position *</label>
                    <select class="form-select" id="job-position" name="job-position" required>
                        <option value="">Select a position</option>
                        <option value="math-teacher">Math Teacher</option>
                        <option value="science-teacher">Science Teacher</option>
                        <option value="english-teacher">English Teacher</option>
                        <option value="admin-staff">Admin Staff</option>
                        <option value="it-support">IT Support</option>
                    </select>
                    <div class="invalid-feedback">Please select a job position.</div>
                </div>
                <button type="button" class="btn btn-primary next-step" data-next="step2">Continue</button>
            </form>
        </div>

        <!-- Step 2: Background Information -->
        <div class="form-step" id="step2">
            <h2 class="text-center mb-4">Background Information</h2>
            <p class="text-muted text-center mb-4">Please provide your educational and professional background details</p>

            <form action="process_application.php" method="POST">
                
                <div class="form-section">
                    <h3 class="section-title">Educational Background</h3>
                    
                    <div class="mb-3">
                        <label for="elementary" class="form-label required-field">Elementary School</label>
                        <input type="text" class="form-control" id="elementary" name="elementary" required>
                        <div class="invalid-feedback">Please provide your elementary school.</div>
                        <div class="form-text">Name of the elementary school you attended</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="high_school" class="form-label required-field">High School</label>
                        <input type="text" class="form-control" id="high_school" name="high_school" required>
                        <div class="invalid-feedback">Please provide your high school.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="senior_high" class="form-label required-field">Senior High School</label>
                        <input type="text" class="form-control" id="senior_high" name="senior_high" required>
                        <div class="invalid-feedback">Please provide your senior high school.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="college" class="form-label required-field">College/University</label>
                        <input type="text" class="form-control" id="college" name="college" required>
                        <div class="invalid-feedback">Please provide your college/university.</div>
                        <div class="form-text">If you didn't attend college, please write "N/A"</div>
                    </div>
                </div>

                <!-- Work Experience Section -->
                <div class="form-section">
                    <h3 class="section-title">Work Experience</h3>
                    
                    <div class="mb-3">
                        <label for="company1" class="form-label required-field">Company Name</label>
                        <input type="text" class="form-control" id="company1" name="company1" required>
                        <div class="invalid-feedback">Please provide a company name.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="position1" class="form-label required-field">Position</label>
                        <input type="text" class="form-control" id="position1" name="position1" required>
                        <div class="invalid-feedback">Please provide your position.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="duration1" class="form-label required-field">Duration (Years)</label>
                        <input type="number" class="form-control" id="duration1" name="duration1" min="0" max="50" required>
                        <div class="invalid-feedback">Please provide a valid duration (0-50 years).</div>
                        <div class="form-text">How many years you worked at this company</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="company2" class="form-label">Company Name (Optional)</label>
                        <input type="text" class="form-control" id="company2" name="company2">
                    </div>
                    
                    <div class="mb-3">
                        <label for="position2" class="form-label">Position (Optional)</label>
                        <input type="text" class="form-control" id="position2" name="position2">
                    </div>
                    
                    <div class="mb-3">
                        <label for="duration2" class="form-label">Duration (Years, Optional)</label>
                        <input type="number" class="form-control" id="duration2" name="duration2" min="0" max="50">
                        <div class="invalid-feedback">Please provide a valid duration (0-50 years).</div>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary prev-step" data-prev="step1">Back</button>
                <button type="button" class="btn btn-primary next-step" data-next="step3">Continue</button>
                </div>
            </form>
        </div>

        <!-- Step 3: Documentation Upload -->
        <div class="form-step" id="step3">
            <h2 class="text-center mb-4">Documentation Upload</h2>
            <form action="process_application.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="resume" class="form-label">Resume (PDF) *</label>
                    <input type="file" class="form-control" id="resume" name="resume" accept=".pdf" required>
                    <div class="file-requirements">Maximum file size: 5MB</div>
                </div>
                <div class="mb-3">
                    <label for="cover_letter" class="form-label">Cover Letter (PDF) *</label>
                    <input type="file" class="form-control" id="cover_letter" name="cover_letter" accept=".pdf" required>
                    <div class="file-requirements">Maximum file size: 5MB</div>
                </div>
                <div class="mb-3">
                    <label for="terms_of_reference" class="form-label">Terms of Reference (PDF) *</label>
                    <input type="file" class="form-control" id="terms_of_reference" name="terms_of_reference" accept=".pdf" required>
                    <div class="file-requirements">Maximum file size: 5MB</div>
                </div>
                <div class="mb-3">
                    <label for="eligibility" class="form-label">Proof of Eligibility (PDF/Image) *</label>
                    <input type="file" class="form-control" id="eligibility" name="eligibility" accept=".pdf,.jpg,.jpeg,.png" required>
                    <div class="file-requirements">Accepted formats: PDF, JPG, PNG (max 5MB)</div>
                </div>
                <button type="button" class="btn btn-secondary prev-step" data-prev="step2">Back</button>
                <button type="submit" class="btn btn-success">Submit Application</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formSteps = document.querySelectorAll('.form-step');
            const progressBar = document.getElementById('form-progress');
            const nextButtons = document.querySelectorAll('.next-step');
            const prevButtons = document.querySelectorAll('.prev-step');
            const form = document.getElementById('jobApplicationForm');
            
            // Initialize form steps
            function initFormSteps() {
                formSteps.forEach((step, index) => {
                    if (index === 0) {
                        step.classList.add('active');
                    } else {
                        step.classList.remove('active');
                    }
                });
                updateProgressBar();
            }
            
            // Update progress bar
            function updateProgressBar() {
                const activeStep = document.querySelector('.form-step.active');
                const stepNumber = parseInt(activeStep.id.replace('step', ''));
                const progress = (stepNumber / formSteps.length) * 100;
                progressBar.style.width = progress + '%';
                progressBar.setAttribute('aria-valuenow', progress);
            }
            
            // Handle next button clicks
            nextButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const currentStep = this.closest('.form-step');
                    const nextStepId = this.getAttribute('data-next');
                    const nextStep = document.getElementById(nextStepId);
                    
                    // Validate current step before proceeding
                    if (validateStep(currentStep)) {
                        currentStep.classList.remove('active');
                        nextStep.classList.add('active');
                        updateProgressBar();
                        
                        // Scroll to top of next step
                        nextStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                });
            });
            
            // Handle previous button clicks
            prevButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const currentStep = this.closest('.form-step');
                    const prevStepId = this.getAttribute('data-prev');
                    const prevStep = document.getElementById(prevStepId);
                    
                    currentStep.classList.remove('active');
                    prevStep.classList.add('active');
                    updateProgressBar();
                    
                    // Scroll to top of previous step
                    prevStep.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            });
            
            // Validate a form step
            function validateStep(step) {
                let isValid = true;
                const inputs = step.querySelectorAll('input[required], select[required], textarea[required]');
                
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                        
                        // Scroll to first invalid input
                        if (isValid === false) {
                            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            input.focus();
                        }
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                
                return isValid;
            }
            
            // Real-time validation
            form.addEventListener('input', function(e) {
                if (e.target.hasAttribute('required')) {
                    if (e.target.checkValidity()) {
                        e.target.classList.remove('is-invalid');
                    }
                }
            });
            
            // Initialize form
            initFormSteps();
        });
    </script>
</body>
</html>