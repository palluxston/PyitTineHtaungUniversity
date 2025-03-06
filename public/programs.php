<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pyit Tine Htaung University - Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- External CSS -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/header_style.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <!-- FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    /* Admissions Section Styles */
.admissions-section {
    margin-top: 60px;
    padding: 40px;
    background: #f8f9fa;
    border-radius: 20px;
}

.admissions-section h2 {
    text-align: center;
    color: #004080;
    margin-bottom: 40px;
}

.admission-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 50px;
}

.admission-card {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.admission-card:hover {
    transform: translateY(-10px);
}

.admission-card h3 {
    color: #004080;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.admission-card h3 i {
    color: #0066cc;
}

.admission-card ul {
    list-style: none;
    padding: 0;
}

.admission-card ul li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

/* Application Process Styles */
.application-process {
    margin-top: 50px;
}

.application-process h3 {
    text-align: center;
    color: #004080;
    margin-bottom: 40px;
}

.process-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
}

.step {
    text-align: center;
    padding: 30px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    position: relative;
}

.step-number {
    width: 40px;
    height: 40px;
    background: #004080;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-weight: bold;
}

.step h4 {
    color: #004080;
    margin-bottom: 15px;
}

/* CTA Section */
.program-cta {
    text-align: center;
    margin-top: 60px;
    padding: 60px;
    background: linear-gradient(135deg, #004080 0%, #0066cc 100%);
    border-radius: 20px;
    color: white;
}

.program-cta h3 {
    font-size: 2em;
    margin-bottom: 20px;
}

.cta-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-top: 30px;
}

.btn-secondary {
    background: transparent;
    border: 2px solid white;
    color: white;
}

.btn-secondary:hover {
    background: white;
    color: #004080;
}

@media (max-width: 768px) {
    .admission-grid {
        grid-template-columns: 1fr;
    }
    
    .process-steps {
        grid-template-columns: 1fr;
    }
    
    .cta-buttons {
        flex-direction: column;
        gap: 15px;
    }
}

/* Program Comparison Styles */
.program-comparison {
    margin: 60px 0;
    padding: 40px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.program-comparison h2 {
    text-align: center;
    color: #004080;
    margin-bottom: 30px;
}

.comparison-table {
    overflow-x: auto;
}

.comparison-table table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.comparison-table th, 
.comparison-table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.comparison-table th {
    background: #004080;
    color: white;
}

.comparison-table tr:hover {
    background: #f8f9fa;
}

/* Student Resources Styles */
.student-resources {
    margin: 60px 0;
}

.student-resources h2 {
    text-align: center;
    color: #004080;
    margin-bottom: 40px;
}

.resources-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.resource-card {
    background: #fff;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.resource-card:hover {
    transform: translateY(-10px);
}

.resource-card i {
    font-size: 2.5em;
    color: #004080;
    margin-bottom: 20px;
}

.resource-card h3 {
    color: #004080;
    margin-bottom: 15px;
}

.resource-card ul {
    list-style: none;
    padding: 0;
}

.resource-card ul li {
    padding: 8px 0;
    color: #666;
    border-bottom: 1px solid #eee;
}

.resource-card ul li:last-child {
    border-bottom: none;
}

@media (max-width: 768px) {
    .comparison-table {
        font-size: 14px;
    }
    
    .comparison-table th, 
    .comparison-table td {
        padding: 10px;
    }
    
    .resources-grid {
        grid-template-columns: 1fr;
    }
}

/* Enhanced Financial Aid Styles */
.aid-link {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #333;
    padding: 8px 0;
    transition: all 0.3s ease;
}

.aid-link i {
    margin-right: 10px;
    color: #004080;
}

.aid-link:hover {
    color: #004080;
    transform: translateX(10px);
}

.aid-detail {
    display: block;
    font-size: 0.85em;
    color: #666;
    margin-left: 25px;
}

.financial-aid-details {
    margin-top: 40px;
    padding: 30px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.financial-aid-details h3 {
    color: #004080;
    text-align: center;
    margin-bottom: 30px;
}

.aid-programs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.aid-program-card {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #004080;
}

.aid-program-card h4 {
    color: #004080;
    margin-bottom: 15px;
}

.aid-program-card ul {
    list-style: none;
    padding: 0;
    margin-bottom: 15px;
}

.aid-program-card ul li {
    padding: 8px 0;
    border-bottom: 1px dashed #ddd;
}

.eligibility {
    font-size: 0.9em;
    color: #666;
    padding-top: 10px;
    border-top: 1px solid #ddd;
}

.aid-application-info {
    margin-top: 40px;
}

.aid-application-info h4 {
    color: #004080;
    text-align: center;
    margin-bottom: 30px;
}

.aid-steps {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

.aid-step {
    flex: 1;
    min-width: 200px;
    text-align: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    position: relative;
}

.step-number {
    display: inline-block;
    width: 30px;
    height: 30px;
    background: #004080;
    color: white;
    border-radius: 50%;
    line-height: 30px;
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .aid-programs-grid {
        grid-template-columns: 1fr;
    }
    
    .aid-steps {
        flex-direction: column;
    }
    
    .aid-step {
        width: 100%;
    }
}

/* Student Life Styles */
.student-life {
    margin: 60px 0;
    padding: 40px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.student-life h2 {
    text-align: center;
    color: #004080;
    margin-bottom: 40px;
}

.life-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
}

.life-card {
    text-align: center;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 15px;
    transition: transform 0.3s ease;
}

.life-card:hover {
    transform: translateY(-10px);
}

.life-card i {
    font-size: 2.5em;
    color: #004080;
    margin-bottom: 20px;
}

.life-card h3 {
    color: #004080;
    margin-bottom: 15px;
}

.life-card ul {
    list-style: none;
    padding: 0;
}

.life-card ul li {
    padding: 8px 0;
    color: #666;
    border-bottom: 1px solid #eee;
}

.life-card ul li:last-child {
    border-bottom: none;
}

@media (max-width: 768px) {
    .life-grid {
        grid-template-columns: 1fr;
    }
}
  </style>
</head>
<body>
  <!-- Top Information Bar -->
  <div class="top-bar">
    <div class="container">
      <span><i class="fas fa-phone-alt"></i> +95 123 456 789</span>
      <span><i class="fas fa-envelope"></i> info@pyittinehtaung.edu.mm</span>
    </div>
  </div>

  <!-- Header Section -->
  <header class="site-header">
    <div class="container header-container">
      <div class="logo">    
        <img src="../images/logo_new1.png" alt="Pyit Tine Htaung University Logo"> Pyit Tine Htaung University
      </div>


      <nav class="site-nav">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="programs.php">Programs</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="research.php">Research</a></li>
          <li><a href="portal.php">Portal</a></li>
        </ul>
      </nav>
    </div>
  </header>

<section class="hero" style="background-image: url('../images/gallery2.jpg');">
    <div class="hero-overlay">
        <div class="container hero-content">
            <h1>Academic Programs</h1>
            <p>Explore our diverse range of undergraduate and graduate programs</p>
        </div>
    </div>
</section>

<section class="programs-section">
    <div class="container">
        <div class="program-detail">
            <div class="program-header">
                <div>
                    <h2>Computer Science</h2>
                    <p>Bachelor of Science (BSc)</p>
                </div>
                <i class="fas fa-laptop-code program-icon"></i>
            </div>
            <div class="program-content">
                <div class="program-description">
                    <h3>Program Overview</h3>
                    <p>Our Computer Science program prepares students for the digital future with comprehensive training in:</p>
                    <ul>
                        <li>Software Development</li>
                        <li>Artificial Intelligence</li>
                        <li>Data Science</li>
                        <li>Cybersecurity</li>
                    </ul>
                </div>
                <div class="program-requirements">
                    <h3>Requirements</h3>
                    <ul class="requirement-list">
                        <li><i class="fas fa-check"></i> High School Diploma</li>
                        <li><i class="fas fa-check"></i> Mathematics Background</li>
                        <li><i class="fas fa-check"></i> Basic Programming Knowledge</li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="program-detail">
            <div class="program-header">
                <div>
                    <h2>Medicine & Health Sciences</h2>
                    <p>Bachelor of Medicine (MBBS)</p>
                </div>
                <i class="fas fa-heartbeat program-icon"></i>
            </div>
            <div class="program-content">
                <div class="program-description">
                    <h3>Program Overview</h3>
                    <p>Comprehensive medical education focusing on:</p>
                    <ul>
                        <li>Clinical Practice</li>
                        <li>Medical Research</li>
                        <li>Healthcare Management</li>
                        <li>Public Health</li>
                    </ul>
                </div>
                <div class="program-requirements">
                    <h3>Requirements</h3>
                    <ul class="requirement-list">
                        <li><i class="fas fa-check"></i> Outstanding Academic Record</li>
                        <li><i class="fas fa-check"></i> Biology & Chemistry Background</li>
                        <li><i class="fas fa-check"></i> Medical Entrance Exam</li>
                    </ul>
                </div>
            </div>
        </div>



        <div class="program-detail">
            <div class="program-header">
                <div>
                    <h2>Business Administration</h2>
                    <p>Bachelor of Business Administration (BBA)</p>
                </div>
                <i class="fas fa-chart-line program-icon"></i>
            </div>
            <div class="program-content">
                <div class="program-description">
                    <h3>Program Overview</h3>
                    <p>Develop essential business skills and knowledge in:</p>
                    <ul>
                        <li>Management</li>
                        <li>Marketing</li>
                        <li>Finance</li>
                        <li>International Business</li>
                    </ul>
                </div>
                <div class="program-requirements">
                    <h3>Requirements</h3>
                    <ul class="requirement-list">
                        <li><i class="fas fa-check"></i> High School Diploma</li>
                        <li><i class="fas fa-check"></i> English Proficiency</li>
                        <li><i class="fas fa-check"></i> Basic Mathematics</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="program-detail">
            <div class="program-header">
                <div>
                    <h2>Engineering</h2>
                    <p>Bachelor of Engineering (BE)</p>
                </div>
                <i class="fas fa-cogs program-icon"></i>
            </div>
            <div class="program-content">
                <div class="program-description">
                    <h3>Program Overview</h3>
                    <p>Specialized engineering tracks available in:</p>
                    <ul>
                        <li>Civil Engineering</li>
                        <li>Electrical Engineering</li>
                        <li>Mechanical Engineering</li>
                        <li>Chemical Engineering</li>
                    </ul>
                </div>
                <div class="program-requirements">
                    <h3>Requirements</h3>
                    <ul class="requirement-list">
                        <li><i class="fas fa-check"></i> High School Diploma</li>
                        <li><i class="fas fa-check"></i> Strong Mathematics & Physics</li>
                        <li><i class="fas fa-check"></i> Entrance Exam Score</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="program-detail">
            <div class="program-header">
                <div>
                    <h2>Digital Arts & Design</h2>
                    <p>Bachelor of Fine Arts (BFA)</p>
                </div>
                <i class="fas fa-palette program-icon"></i>
            </div>
            <div class="program-content">
                <div class="program-description">
                    <h3>Program Overview</h3>
                    <p>Creative focus areas include:</p>
                    <ul>
                        <li>Graphic Design</li>
                        <li>UI/UX Design</li>
                        <li>3D Animation</li>
                        <li>Digital Media</li>
                    </ul>
                </div>
                <div class="program-requirements">
                    <h3>Requirements</h3>
                    <ul class="requirement-list">
                        <li><i class="fas fa-check"></i> High School Diploma</li>
                        <li><i class="fas fa-check"></i> Portfolio Submission</li>
                        <li><i class="fas fa-check"></i> Design Aptitude Test</li>
                    </ul>
                </div>
            </div>
        </div>

                <!-- Program Comparison Section -->
                <div class="program-comparison">
            <h2>Program Comparison</h2>
            <div class="comparison-table">
                <table>
                    <thead>
                        <tr>
                            <th>Program Feature</th>
                            <th>Undergraduate</th>
                            <th>Graduate</th>
                            <th>Professional</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Duration</td>
                            <td>4 Years</td>
                            <td>2 Years</td>
                            <td>1-2 Years</td>
                        </tr>
                        <tr>
                            <td>Class Size</td>
                            <td>30-40 Students</td>
                            <td>15-20 Students</td>
                            <td>10-15 Students</td>
                        </tr>
                        <tr>
                            <td>Internship</td>
                            <td>Optional</td>
                            <td>Required</td>
                            <td>Required</td>
                        </tr>
                        <tr>
                            <td>Research Project</td>
                            <td>Basic Level</td>
                            <td>Advanced Level</td>
                            <td>Industry Level</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Student Resources Section -->
        <div class="student-resources">
            <h2>Student Resources</h2>
            <div class="resources-grid">
                <div class="resource-card">
                    <i class="fas fa-book-reader"></i>
                    <h3>Academic Support</h3>
                    <ul>
                        <li>One-on-One Tutoring</li>
                        <li>Writing Center</li>
                        <li>Math Lab</li>
                        <li>Study Groups</li>
                    </ul>
                </div>
                <div class="resource-card">
                    <i class="fas fa-laptop"></i>
                    <h3>Technology</h3>
                    <ul>
                        <li>24/7 Computer Labs</li>
                        <li>Software Licenses</li>
                        <li>Tech Support</li>
                        <li>Online Resources</li>
                    </ul>
                </div>
                <div class="resource-card">
                    <i class="fas fa-hands-helping"></i>
                    <h3>Career Services</h3>
                    <ul>
                        <li>Career Counseling</li>
                        <li>Resume Workshops</li>
                        <li>Job Fairs</li>
                        <li>Industry Connections</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Add before the admissions-section -->
        <div class="student-life">
            <h2>Student Life</h2>
            <div class="life-grid">
                <div class="life-card">
                    <i class="fas fa-users"></i>
                    <h3>Student Organizations</h3>
                    <ul>
                        <li>Academic Clubs</li>
                        <li>Cultural Associations</li>
                        <li>Sports Teams</li>
                        <li>Student Government</li>
                    </ul>
                </div>
                <div class="life-card">
                    <i class="fas fa-home"></i>
                    <h3>Campus Living</h3>
                    <ul>
                        <li>Modern Dormitories</li>
                        <li>Dining Facilities</li>
                        <li>Recreation Centers</li>
                        <li>Study Spaces</li>
                    </ul>
                </div>
                <div class="life-card">
                    <i class="fas fa-globe"></i>
                    <h3>International Experience</h3>
                    <ul>
                        <li>Study Abroad Programs</li>
                        <li>Cultural Exchange</li>
                        <li>Language Partners</li>
                        <li>Global Events</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Admissions & Fees Section -->
        <div class="admissions-section">
            <h2>Admissions Information</h2>
            
            <div class="admission-grid">
                <div class="admission-card">
                    <h3><i class="fas fa-calendar-alt"></i> Key Dates</h3>
                    <ul>
                        <li>Application Opens: June 1, 2025</li>
                        <li>Early Decision: August 15, 2025</li>
                        <li>Regular Decision: October 30, 2025</li>
                        <li>Classes Begin: January 2026</li>
                    </ul>
                </div>

                <div class="admission-card">
                    <h3><i class="fas fa-dollar-sign"></i> Tuition & Fees</h3>
                    <ul>
                        <li>Undergraduate: $12,000/year</li>
                        <li>Graduate: $15,000/year</li>
                        <li>Registration Fee: $500</li>
                        <li>Lab Fees: Varies by program</li>
                    </ul>
                </div>

                <div class="admission-card">
                    <h3><i class="fas fa-hand-holding-usd"></i> Financial Aid</h3>
                    <ul>
                        <li><a href="#financial-aid-details" class="aid-link">
                            <i class="fas fa-medal"></i> Merit Scholarships
                            <span class="aid-detail">Up to 100% tuition coverage for outstanding students</span>
                        </a></li>
                        <li><a href="#financial-aid-details" class="aid-link">
                            <i class="fas fa-gift"></i> Need-based Grants
                            <span class="aid-detail">Financial assistance based on family income</span>
                        </a></li>
                        <li><a href="#financial-aid-details" class="aid-link">
                            <i class="fas fa-briefcase"></i> Work-Study Programs
                            <span class="aid-detail">Part-time campus employment opportunities</span>
                        </a></li>
                        <li><a href="#financial-aid-details" class="aid-link">
                            <i class="fas fa-university"></i> Student Loans
                            <span class="aid-detail">Low-interest education financing options</span>
                        </a></li>
                    </ul>
                </div>
            </div>

            <div class="application-process">
                <h3>Application Process</h3>
                <div class="process-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h4>Online Application</h4>
                        <p>Complete the online application form with personal and academic information</p>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <h4>Document Submission</h4>
                        <p>Submit transcripts, test scores, and letters of recommendation</p>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <h4>Interview</h4>
                        <p>Selected candidates will be invited for an interview</p>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <h4>Decision</h4>
                        <p>Receive admission decision within 4-6 weeks</p>
                    </div>
                </div>
            </div>

            <div class="financial-aid-details" id="financial-aid-details">
                <h3>Financial Support Programs</h3>
                <div class="aid-programs-grid">
                    <div class="aid-program-card">
                        <h4>Merit Scholarships</h4>
                        <ul>
                            <li>Academic Excellence Scholarship (Full tuition)</li>
                            <li>Dean's List Scholarship (75% tuition)</li>
                            <li>Department Specific Scholarships</li>
                            <li>International Student Scholarships</li>
                        </ul>
                        <p class="eligibility">Eligibility: GPA 3.5 or higher</p>
                    </div>

                    <div class="aid-program-card">
                        <h4>Need-based Grants</h4>
                        <ul>
                            <li>Family Income Support Grant</li>
                            <li>Emergency Financial Assistance</li>
                            <li>First-Generation Student Grant</li>
                            <li>Housing & Living Expense Grant</li>
                        </ul>
                        <p class="eligibility">Required: Financial Need Documentation</p>
                    </div>

                    <div class="aid-program-card">
                        <h4>Work-Study Opportunities</h4>
                        <ul>
                            <li>Library Assistant (10-20 hrs/week)</li>
                            <li>Research Assistant (15-25 hrs/week)</li>
                            <li>IT Help Desk (10-20 hrs/week)</li>
                            <li>Administrative Support (15-20 hrs/week)</li>
                        </ul>
                        <p class="eligibility">Earn: $12-18 per hour</p>
                    </div>

                    <div class="aid-program-card">
                        <h4>Student Loan Programs</h4>
                        <ul>
                            <li>Federal Student Loans</li>
                            <li>University Payment Plans</li>
                            <li>Private Education Loans</li>
                            <li>International Student Loans</li>
                        </ul>
                        <p class="eligibility">Interest Rates: 3.5-6.5%</p>
                    </div>
                    <div class="aid-program-card">
                        <h4>International Student Aid</h4>
                        <ul>
                            <li>Cultural Exchange Scholarship</li>
                            <li>Language Support Grant</li>
                            <li>International Merit Awards</li>
                            <li>Travel & Accommodation Support</li>
                        </ul>
                        <p class="eligibility">For: International Students with Strong Academic Records</p>
                    </div>

                    <div class="aid-program-card">
                        <h4>Research & Innovation Grants</h4>
                        <ul>
                            <li>Undergraduate Research Fellowship ($5,000/year)</li>
                            <li>Innovation Project Funding (Up to $10,000)</li>
                            <li>Lab Equipment Access Grant</li>
                            <li>Conference Travel Support</li>
                        </ul>
                        <p class="eligibility">Required: Research Proposal & Faculty Recommendation</p>
                    </div>
                </div>

                <div class="aid-application-info">
                    <h4>How to Apply for Financial Aid</h4>
                    <div class="aid-steps">
                        <div class="aid-step">
                            <span class="step-number">1</span>
                            <p>Submit FAFSA or International Student Aid Application</p>
                        </div>
                        <div class="aid-step">
                            <span class="step-number">2</span>
                            <p>Complete University Financial Aid Form</p>
                        </div>
                        <div class="aid-step">
                            <span class="step-number">3</span>
                            <p>Provide Required Documentation</p>
                        </div>
                        <div class="aid-step">
                            <span class="step-number">4</span>
                            <p>Review and Accept Aid Package</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Call to Action -->
        <div class="program-cta">
            <h3>Ready to Begin Your Journey?</h3>
            <p>Take the first step towards your future career</p>
            <div class="cta-buttons">
                <a href="#" class="btn btn-primary">Apply Now</a>
                <a href="contact.php" class="btn btn-secondary">Contact Admissions</a>
            </div>
        </div>
    </div>
</section>
  <!-- Footer Section -->
  <footer>
    <div class="container footer-container">
      <div class="footer-column">
        <h3>Contact Us</h3>
        <p>123 University Road, Yangon, Myanmar</p>
        <p>Phone: +95 123 456 789</p>
        <p>Email: info@pyitinehtaung.edu.mm</p>
      </div>
      <div class="footer-column">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="programs.php">Programs</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="portal.php">Portal</a></li>
        </ul>
      </div>
      <div class="footer-column">
        <h3>Follow Us</h3>
        <ul class="social-links">
          <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
          <li><a href="#"><i class="fab fa-twitter"></i></a></li>
          <li><a href="#"><i class="fab fa-instagram"></i></a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="container">
        <p>&copy; 2024 Pyit Tine Htaung University. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <!-- JavaScript -->
  <script src="js/script.js"></script>
</body>
</html>