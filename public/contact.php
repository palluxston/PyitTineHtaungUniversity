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
</head>
<style>
    /* Enhanced Contact Page Styles */
.contact-section {
    padding: 60px 0;
}

.department-links {
    margin-bottom: 60px;
}

.department-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.department-card {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    text-decoration: none;
    color: #333;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.department-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.department-card i {
    font-size: 2.5em;
    color: #004080;
    margin-bottom: 15px;
}

.contact-info .contact-card {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.contact-info .contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.contact-card div p {
    color: #666;
    margin: 5px 0;
    transition: color 0.3s ease;
}

.contact-info .contact-card:hover div p {
    color: #333;
}

.contact-card i {
    font-size: 1.5em;
    color: #004080;
    margin-right: 20px;
    margin-top: 5px;
    transition: all 0.3s ease;
}

.contact-card h3 {
    color: #004080;
    margin-bottom: 10px;
    transition: color 0.3s ease;
}

.contact-info .contact-card:hover h3 {
    color: #0066cc;
}

.direction-link {
    display: inline-block;
    color: #004080;
    margin-top: 10px;
    text-decoration: none;
}

.direction-link:hover {
    text-decoration: underline;
}

.contact-form {
    background: #fff;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #004080;
    outline: none;
}

.submit-btn {
    background: #004080;
    color: #fff;
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
    transition: background 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.submit-btn:hover {
    background: #003366;
}

.alert {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.faq-section {
    margin-top: 60px;
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 30px;
}

.faq-item {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.faq-item h3 {
    color: #004080;
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    .contact-container {
        grid-template-columns: 1fr;
    }
    
    .department-grid {
        grid-template-columns: 1fr;
    }
    
    .faq-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<body>
  <!-- Top Information Bar -->
  <div class="top-bar">
    <div class="container">
      <span><i class="fas fa-phone-alt"></i> +95 123 456 789</span>
      <span><i class="fas fa-envelope"></i> info@pyittinehtaung.edu.mm</span>
    </div>
  </div>
  <!--Header Section-->
    <header class="site-header">
        <div class="container header-container">
            <div class="logo">
                <img src="../images/logo_new1.png" alt="Pyit Tine Htaung University Logo">
                Pyit Tine Htaung University
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

    <section class="hero" style="background-image: url('../images/contact.jpg');">
        <div class="hero-overlay">
            <div class="container hero-content">
                <h1>Contact Us</h1>
                <p>We're here to answer any questions you may have</p>
            </div>
        </div>
    </section>

    <section class="contact-section">
        <div class="container">
            <!-- Department Quick Links -->
            <div class="department-links">
                <h2>Quick Department Contacts</h2>
                <div class="department-grid">
                    <a href="#admissions" class="department-card">
                        <i class="fas fa-user-graduate"></i>
                        <h3>Admissions</h3>
                        <p>For enrollment and application inquiries</p>
                    </a>
                    <a href="#academic" class="department-card">
                        <i class="fas fa-book"></i>
                        <h3>Academic Affairs</h3>
                        <p>For current students and academic matters</p>
                    </a>
                    <a href="#financial" class="department-card">
                        <i class="fas fa-dollar-sign"></i>
                        <h3>Financial Aid</h3>
                        <p>For scholarship and funding questions</p>
                    </a>
                    <a href="#international" class="department-card">
                        <i class="fas fa-globe"></i>
                        <h3>International Office</h3>
                        <p>For international student support</p>
                    </a>
                </div>
            </div>

            <div class="contact-container">
                <div class="contact-info">
                    <h2>Get In Touch</h2>
                    <div class="contact-card">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h3>Location</h3>
                            <p>123 University Road</p>
                            <p>Yangon, Myanmar</p>
                            <a href="https://maps.google.com" target="_blank" class="direction-link">Get Directions</a>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h3>Phone</h3>
                            <p>Main: +95 123 456 789</p>
                            <p>Admissions: +95 123 456 790</p>
                            <p>Emergency: +95 123 456 791</p>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h3>Email</h3>
                            <p>General: info@pyittinehtaung.edu.mm</p>
                            <p>Admissions: admissions@pyittinehtaung.edu.mm</p>
                            <p>Support: support@pyittinehtaung.edu.mm</p>
                        </div>
                    </div>
                    <div class="contact-card">
                        <i class="fas fa-clock"></i>
                        <div>
                            <h3>Office Hours</h3>
                            <p>Monday - Friday: 8:00 AM - 5:00 PM</p>
                            <p>Saturday: 9:00 AM - 1:00 PM</p>
                            <p>Sunday: Closed</p>
                        </div>
                    </div>
                </div>

                <div class="contact-form">
                    <h2>Send Us a Message</h2>
                    <?php
                    if (isset($_POST['submit'])) {
                        require_once '../connect.php';
                        
                        try {
                            // Sanitize inputs
                            $name = htmlspecialchars($_POST['name']);
                            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                            $subject = htmlspecialchars($_POST['subject']);
                            $message = htmlspecialchars($_POST['message']);
                            
                            // Prepare SQL statement
                            $sql = "INSERT INTO contact_submissions (full_name, email, subject, message) VALUES (:name, :email, :subject, :message)";
                            $stmt = $conn->prepare($sql);
                            
                            // Bind parameters
                            $stmt->bindParam(':name', $name);
                            $stmt->bindParam(':email', $email);
                            $stmt->bindParam(':subject', $subject);
                            $stmt->bindParam(':message', $message);
                            
                            // Execute the statement
                            if ($stmt->execute()) {
                                echo '<div class="alert alert-success">Thank you for your message. We will get back to you soon!</div>';
                            } else {
                                echo '<div class="alert alert-error">Sorry, there was an error sending your message. Please try again.</div>';
                            }
                        } catch(PDOException $e) {
                            echo '<div class="alert alert-error">Database Error: ' . $e->getMessage() . '</div>';
                        }
                    }
                    ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <select id="subject" name="subject" required>
                                <option value="">Select a subject</option>
                                <option value="Admission Inquiry">Admission Inquiry</option>
                                <option value="Academic Question">Academic Question</option>
                                <option value="Financial Aid">Financial Aid</option>
                                <option value="Financial Aid">Password Change Request</option>
                                <option value="International Student">International Student</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" name="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="faq-section">
                <h2>Frequently Asked Questions</h2>
                <div class="faq-grid">
                    <div class="faq-item">
                        <h3>How can I apply for admission?</h3>
                        <p>Visit our Admissions page or contact the admissions office directly for detailed information about the application process.</p>
                    </div>
                    <div class="faq-item">
                        <h3>What financial aid options are available?</h3>
                        <p>We offer various scholarships, grants, and loan programs. Contact our financial aid office for more information.</p>
                    </div>
                    <div class="faq-item">
                        <h3>How can international students apply?</h3>
                        <p>International students should contact our International Office for specific requirements and support throughout the application process.</p>
                    </div>
                    <div class="faq-item">
                        <h3>What housing options are available?</h3>
                        <p>We offer both on-campus and off-campus housing options. Contact our housing office for current availability.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


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