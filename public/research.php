<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Research - Pyit Tine Htaung University</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header_style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
         .has-submenu {
            position: relative;
        }

        .has-submenu:hover .submenu {
            display: block;
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .submenu {
            display: block;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            min-width: 250px;
            z-index: 1000;
            padding: 10px 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(15px);
            transition: all 0.3s ease;
        }

        .submenu li {
            display: block;
            margin: 0;
        }

        .submenu a {
            padding: 12px 25px;
            display: block;
            color: #333;
            font-size: 0.95em;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .submenu a:hover {
            background: #f8f9fa;
            color: #004080;
            border-left: 3px solid #004080;
        }

        /* Hero Section */
        .research-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('../images/research-hero.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 180px 0 100px;
            text-align: center;
        }

        .research-hero h1 {
            font-size: 3.5em;
            margin-bottom: 20px;
            font-family: 'Playfair Display', serif;
            animation: fadeInUp 1s ease;
        }

        .research-hero p {
            font-size: 1.2em;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
            animation: fadeInUp 1s ease 0.2s;
            opacity: 0;
            animation-fill-mode: forwards;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #004080, #0066cc);
            color: white;
            padding: 60px 0;
            margin-top: -50px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
        }

        .stat-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
            color: #fff;
        }

        .stat-number {
            font-size: 2.8em;
            font-weight: bold;
            margin: 10px 0;
            font-family: 'Playfair Display', serif;
        }

        .stat-label {
            font-size: 1.1em;
            opacity: 0.9;
        }

        /* Research Areas */
        .research-areas {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5em;
            color: #004080;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
        }

        .section-title p {
            color: #666;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .research-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            padding: 0 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .research-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .research-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .research-image {
            height: 200px;
            overflow: hidden;
        }

        .research-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .research-card:hover .research-image img {
            transform: scale(1.1);
        }

        .research-content {
            padding: 25px;
        }

        .research-content h3 {
            color: #004080;
            margin-bottom: 15px;
            font-size: 1.4em;
        }

        .research-content p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* Enhanced Publications Section */
        .publications {
            padding: 80px 0;
            background: #fff;
        }

        .publications-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .publication-filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 40px;
        }

        .filter-btn {
            padding: 12px 25px;
            border: 2px solid #004080;
            border-radius: 30px;
            background: transparent;
            color: #004080;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.95em;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: #004080;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 64, 128, 0.2);
        }

        .publication-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .publication-card {
            background: #ffffff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .publication-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .publication-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .publication-category {
            background: #e8f0fe;
            color: #004080;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }

        .publication-date {
            color: #666;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .publication-card h3 {
            color: #1a1a1a;
            font-size: 1.3em;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .publication-card p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .publication-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            margin-top: 20px;
        }

        .author-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .author-info i {
            font-size: 2.5em;
            color: #004080;
        }

        .author-details {
            display: flex;
            flex-direction: column;
        }

        .author-name {
            color: #1a1a1a;
            font-weight: 500;
        }

        .author-title {
            color: #666;
            font-size: 0.85em;
        }

        .publication-journal {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #004080;
            font-size: 0.9em;
        }

        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #004080;
            font-weight: 500;
            text-decoration: none;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .read-more:hover {
            gap: 12px;
            color: #0066cc;
        }

        @media (max-width: 768px) {
            .publication-grid {
                grid-template-columns: 1fr;
            }

            .publication-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .filter-btn {
                padding: 10px 20px;
                font-size: 0.9em;
            }
        }
        /* Research Teams */
        .research-teams {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .team-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .team-card:hover {
            transform: translateY(-10px);
        }

        .team-image {
            height: 250px;
            overflow: hidden;
        }

        .team-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .team-info {
            padding: 20px;
        }

        .team-info h3 {
            color: #004080;
            margin-bottom: 5px;
        }

        .team-info .position {
            color: #666;
            font-style: italic;
            margin-bottom: 15px;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }

        .social-links a {
            color: #004080;
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            color: #0066cc;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .research-hero h1 {
                font-size: 2.5em;
            }

            .research-hero p {
                font-size: 1.1em;
            }

            .publication-filters {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .section-title h2 {
                font-size: 2em;
            }
        }



        /* Research Facilities */
        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .facility-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .facility-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .facility-info {
            padding: 25px;
        }

        .facility-features {
            list-style: none;
            padding: 0;
            margin-top: 15px;
        }

        .facility-features li {
            margin: 10px 0;
            color: #666;
        }

        .facility-features i {
            color: #004080;
            margin-right: 10px;
        }

        /* Research Opportunities */
        .opportunities-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .opportunity-tabs {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .tab-btn {
            padding: 12px 25px;
            border: none;
            background: #f8f9fa;
            color: #004080;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .tab-btn.active,
        .tab-btn:hover {
            background: #004080;
            color: white;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        .tab-content.active {
            display: block;
            margin-bottom: 20px;
        }

        .opportunity-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .opportunity-list li {
            margin: 15px 0;
            padding-left: 25px;
            position: relative;
        }

        .opportunity-list li::before {
            content: '→';
            position: absolute;
            left: 0;
            color: #004080;
        }

        /* Back to Top Button */
        #backToTop {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            background: #004080;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        #backToTop.show {
            opacity: 1;
            visibility: visible;
        }

        #backToTop:hover {
            background: #0066cc;
            transform: translateY(-5px);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
        <!-- Replace with your actual logo image -->
        <img src="../images/logo_new1.png" alt="Pyit Tine Htaung University Logo">Pyit Tine Htaung University
      </div>


      <nav class="site-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="programs.php">Programs</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li class="has-submenu">
                        <a href="research.php">Research</a>
                        <ul class="submenu">
                            <li><a href="research.php#centers">Research Centers</a></li>
                            <li><a href="research.php#publications">Publications</a></li>
                            <li><a href="research.php#opportunities">Opportunities</a></li>
                            <li><a href="research.php#partnerships">Partnerships</a></li>
                        </ul>
                    </li>
                    
                    <li><a href="portal.php">Portal</a></li>
                </ul>
        </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="research-hero">
        <div class="container">
            <h1>Research & Innovation</h1>
            <p>Advancing knowledge through groundbreaking research and fostering innovation for a better tomorrow</p>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-flask stat-icon"></i>
                <div class="stat-number">150+</div>
                <div class="stat-label">Active Research Projects</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-users stat-icon"></i>
                <div class="stat-number">300+</div>
                <div class="stat-label">Research Faculty</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-award stat-icon"></i>
                <div class="stat-number">50+</div>
                <div class="stat-label">Research Awards</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-globe stat-icon"></i>
                <div class="stat-number">45+</div>
                <div class="stat-label">Global Partners</div>
            </div>
        </div>
    </section>

    <!-- Research Areas -->
    <section class="research-areas" id="areas">
        <div class="section-title">
            <h2>Our Research Areas</h2>
            <p>Exploring innovative solutions across multiple disciplines</p>
        </div>
        <div class="research-grid">
            <div class="research-card">
                <div class="research-image">
                    <img src="../images/ai-research.jpg" alt="AI Research">
                </div>
                <div class="research-content">
                    <h3>Artificial Intelligence</h3>
                    <p>Advancing machine learning and deep learning technologies for real-world applications.</p>
                    <a href="#" class="btn">Learn More</a>
                </div>
            </div>
            <div class="research-card">
                <div class="research-image">
                    <img src="../images/biotech-research.jpg" alt="Biotechnology">
                </div>
                <div class="research-content">
                    <h3>Biotechnology</h3>
                    <p>Pioneering research in genetics, molecular biology, and biomedical engineering.</p>
                    <a href="#" class="btn">Learn More</a>
                </div>
            </div>
            <div class="research-card">
                <div class="research-image">
                    <img src="../images/renewable-energy.jpg" alt="Renewable Energy">
                </div>
                <div class="research-content">
                    <h3>Renewable Energy</h3>
                    <p>Developing sustainable solutions for clean energy production and storage.</p>
                    <a href="#" class="btn">Learn More</a>
                </div>
            </div>
        </div>
    </section>



    <!-- Publications Section -->
    <section class="publications" id="publications">
        <div class="section-title">
            <h2>Latest Publications</h2>
            <p>Discover our recent research findings and academic contributions</p>
        </div>
        <div class="publications-container">
            <div class="publication-filters">
                <button class="filter-btn active" data-filter="all">All Publications</button>
                <button class="filter-btn" data-filter="technology">Technology</button>
                <button class="filter-btn" data-filter="science">Science</button>
                <button class="filter-btn" data-filter="medicine">Medicine</button>
                <button class="filter-btn" data-filter="engineering">Engineering</button>
            </div>
            <div class="publication-grid">
                <div class="publication-card" data-category="technology">
                    <div class="publication-header">
                        <span class="publication-category">Technology</span>
                        <span class="publication-date"><i class="fas fa-calendar"></i> January 2024</span>
                    </div>
                    <h3>Advances in Quantum Computing Applications</h3>
                    <p>A comprehensive study on quantum computing implementations in cryptography and data security systems.</p>
                    <div class="publication-meta">
                        <div class="author-info">
                            <i class="fas fa-user-circle"></i>
                            <div class="author-details">
                                <span class="author-name">Dr. John Smith</span>
                                <span class="author-title">Lead Researcher</span>
                            </div>
                        </div>
                        <div class="publication-journal">
                            <i class="fas fa-book"></i>
                            <span>IEEE Quantum Computing</span>
                        </div>
                    </div>
                    <a href="#" class="read-more">Read Full Paper <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="publication-card" data-category="science">
                    <div class="publication-header">
                        <span class="publication-category">Science</span>
                        <span class="publication-date"><i class="fas fa-calendar"></i> February 2024</span>
                    </div>
                    <h3>Sustainable Energy Solutions</h3>
                    <p>Research on innovative renewable energy storage systems and grid integration methods.</p>
                    <div class="publication-meta">
                        <div class="author-info">
                            <i class="fas fa-user-circle"></i>
                            <div class="author-details">
                                <span class="author-name">Dr. Sarah Johnson</span>
                                <span class="author-title">Lead Researcher</span>
                            </div>
                        </div>
                        <div class="publication-journal">
                            <i class="fas fa-book"></i>
                            <span>Nature Energy</span>
                        </div>
                    </div>
                    <a href="#" class="read-more">Read Full Paper <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="publication-card" data-category="medicine">
                    <div class="publication-header">
                        <span class="publication-category">Medicine</span>
                        <span class="publication-date"><i class="fas fa-calendar"></i> March 2024</span>
                    </div>
                    <h3>AI in Healthcare Diagnostics</h3>
                    <p>Implementation of machine learning in medical diagnosis and patient care optimization.</p>
                    <div class="publication-meta">
                        <div class="author-info">
                            <i class="fas fa-user-circle"></i>
                            <div class="author-details">
                                <span class="author-name">Dr. Michael Chen</span>
                                <span class="author-title">Research Director</span>
                            </div>
                        </div>
                        <div class="publication-journal">
                            <i class="fas fa-book"></i>
                            <span>Medical AI Journal</span>
                        </div>
                    </div>
                    <a href="#" class="read-more">Read Full Paper <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="publication-card" data-category="engineering">
                    <div class="publication-header">
                        <span class="publication-category">Engineering</span>
                        <span class="publication-date"><i class="fas fa-calendar"></i> March 2024</span>
                    </div>
                    <h3>Smart Infrastructure Development</h3>
                    <p>Novel approaches to integrating IoT sensors in urban infrastructure monitoring.</p>
                    <div class="publication-meta">
                        <div class="author-info">
                            <i class="fas fa-user-circle"></i>
                            <div class="author-details">
                                <span class="author-name">Dr. Emily Brown</span>
                                <span class="author-title">Senior Engineer</span>
                            </div>
                        </div>
                        <div class="publication-journal">
                            <i class="fas fa-book"></i>
                            <span>Civil Engineering Review</span>
                        </div>
                    </div>
                    <a href="#" class="read-more">Read Full Paper <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="publication-card" data-category="technology">
                    <div class="publication-header">
                        <span class="publication-category">Technology</span>
                        <span class="publication-date"><i class="fas fa-calendar"></i> February 2024</span>
                    </div>
                    <h3>Blockchain in Supply Chain</h3>
                    <p>Implementing blockchain technology for transparent and efficient supply chain management.</p>
                    <div class="publication-meta">
                        <div class="author-info">
                            <i class="fas fa-user-circle"></i>
                            <div class="author-details">
                                <span class="author-name">Dr. Alex Wong</span>
                                <span class="author-title">Technology Lead</span>
                            </div>
                        </div>
                        <div class="publication-journal">
                            <i class="fas fa-book"></i>
                            <span>Blockchain Technology</span>
                        </div>
                    </div>
                    <a href="#" class="read-more">Read Full Paper <i class="fas fa-arrow-right"></i></a>
                </div>

                <div class="publication-card" data-category="science">
                    <div class="publication-header">
                        <span class="publication-category">Science</span>
                        <span class="publication-date"><i class="fas fa-calendar"></i> January 2024</span>
                    </div>
                    <h3>Climate Change Impact Analysis</h3>
                    <p>Statistical analysis of climate change effects on local ecosystems.</p>
                    <div class="publication-meta">
                        <div class="author-info">
                            <i class="fas fa-user-circle"></i>
                            <div class="author-details">
                                <span class="author-name">Dr. Lisa Green</span>
                                <span class="author-title">Environmental Scientist</span>
                            </div>
                        </div>
                        <div class="publication-journal">
                            <i class="fas fa-book"></i>
                            <span>Environmental Science</span>
                        </div>
                    </div>
                    <a href="#" class="read-more">Read Full Paper <i class="fas fa-arrow-right"></i></a>
                </div>

                <!-- Repeat similar structure for other publications -->
            </div>
        </div>
    </section>
    <!-- Research Teams -->
    <section class="research-teams" id="teams">
        <div class="section-title">
            <h2>Our Research Teams</h2>
            <p>Meet our dedicated researchers and faculty members</p>
        </div>
        <div class="team-grid">
            <div class="team-card">
                <div class="team-image">
                    <img src="../images/researcher1.jpg" alt="Dr. John Smith">
                </div>
                <div class="team-info">
                    <h3>Dr. John Smith</h3>
                    <div class="position">Head of AI Research</div>
                    <p>Leading research in artificial intelligence and machine learning.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
            <div class="team-card">
                <div class="team-image">
                    <img src="../images/researcher2.jpg" alt="Dr. Sarah Johnson">
                </div>
                <div class="team-info">
                    <h3>Dr. Sarah Johnson</h3>
                    <div class="position">Director of Biotechnology</div>
                    <p>Specializing in genetic engineering and molecular biology.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
            <div class="team-card">
                <div class="team-image">
                    <img src="../images/researcher3.jpg" alt="Dr. Michael Chen">
                </div>
                <div class="team-info">
                    <h3>Dr. Michael Chen</h3>
                    <div class="position">Lead Environmental Scientist</div>
                    <p>Researching sustainable energy solutions and climate change.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Research Facilities -->
    <section class="research-facilities" id="facilities">
        <div class="section-title">
            <h2>State-of-the-Art Facilities</h2>
            <p>Explore our advanced research laboratories and facilities</p>
        </div>
        <div class="facilities-grid">
            <div class="facility-card">
                <img src="../images/ai-lab.jpg" alt="AI Laboratory">
                <div class="facility-info">
                    <h3>AI & Computing Lab</h3>
                    <p>Advanced computing infrastructure for AI and machine learning research.</p>
                    <ul class="facility-features">
                        <li><i class="fas fa-check"></i> High-performance computing clusters</li>
                        <li><i class="fas fa-check"></i> GPU acceleration units</li>
                        <li><i class="fas fa-check"></i> Virtual reality systems</li>
                    </ul>
                </div>
            </div>
            <div class="facility-card">
                <img src="../images/biotech-lab.jpg" alt="Biotechnology Laboratory">
                <div class="facility-info">
                    <h3>Biotechnology Center</h3>
                    <p>State-of-the-art equipment for biological and medical research.</p>
                    <ul class="facility-features">
                        <li><i class="fas fa-check"></i> Gene sequencing equipment</li>
                        <li><i class="fas fa-check"></i> Cell culture facilities</li>
                        <li><i class="fas fa-check"></i> Microscopy suite</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Research Opportunities -->
    <section class="research-opportunities" id="opportunities">
        <div class="section-title">
            <h2>Research Opportunities</h2>
            <p>Join our research community and make an impact</p>
        </div>
        <div class="opportunities-container">
            <div class="opportunity-tabs">
                <button class="tab-btn active" data-tab="students">For Students</button>
                <button class="tab-btn" data-tab="faculty">For Faculty</button>
                <button class="tab-btn" data-tab="industry">Industry Partners</button>
            </div>
            <div class="tab-content active" id="students">
                <h3>Student Research Programs</h3>
                <ul class="opportunity-list">
                    <li>Undergraduate Research Fellowships</li>
                    <li>Graduate Research Assistantships</li>
                    <li>Summer Research Programs</li>
                    <li>International Research Exchange</li>
                </ul>
                <a href="#" class="btn">Apply Now</a>
            </div>
            <div class="tab-content" id="faculty">
                <h3>Faculty Research Support</h3>
                <ul class="opportunity-list">
                    <li>Research Grants</li>
                    <li>Equipment Funding</li>
                    <li>Conference Support</li>
                    <li>Publication Assistance</li>
                </ul>
                <a href="#" class="btn">Learn More</a>
            </div>
            <div class="tab-content" id="industry">
                <h3>Industry Collaboration</h3>
                <ul class="opportunity-list">
                    <li>Joint Research Projects</li>
                    <li>Technology Transfer</li>
                    <li>Consulting Services</li>
                    <li>Student Internships</li>
                </ul>
                <a href="#" class="btn">Partner With Us</a>
            </div>
        </div>
    </section>

      <!-- Footer Section -->
  <footer>
    <br><br>
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

    <!-- Back to Top Button -->
    <div id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </div>
    <script>
        const backToTop = document.getElementById('backToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });
        
        backToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Tab Functionality
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const tab = btn.dataset.tab;
                
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                btn.classList.add('active');
                document.getElementById(tab).classList.add('active');
            });
        });

        const filterBtns = document.querySelectorAll('.filter-btn');
        const publicationCards = document.querySelectorAll('.publication-card');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                btn.classList.add('active');
                
                const filterValue = btn.getAttribute('data-filter');
                
                publicationCards.forEach(card => {
                    // Show all cards if 'all' is selected
                    if (filterValue === 'all') {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 100);
                    } else {
                        // Show/hide cards based on category
                        if (card.getAttribute('data-category') === filterValue) {
                            card.style.display = 'block';
                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                            }, 100);
                        } else {
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(20px)';
                            setTimeout(() => {
                                card.style.display = 'none';
                            }, 300);
                        }
                    }
                });
            });
        });

    </script>

</body>
</html>