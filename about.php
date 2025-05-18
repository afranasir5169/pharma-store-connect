
<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - PharmaCare</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <!-- Hero Section -->
        <section class="about-hero">
            <div class="container">
                <h1>About PharmaCare</h1>
                <p>Your trusted health partner since 2005</p>
            </div>
        </section>
        
        <!-- Our Story -->
        <section class="about-section">
            <div class="container">
                <div class="about-content">
                    <div class="about-text">
                        <h2>Our Story</h2>
                        <p>PharmaCare was founded in 2005 with a simple mission: to make healthcare more accessible, affordable, and convenient for everyone. What began as a small family-owned pharmacy has grown into a trusted healthcare provider with multiple locations and an online presence serving thousands of customers nationwide.</p>
                        <p>Our founder, Dr. Sarah Chen, started PharmaCare after witnessing firsthand the challenges many people faced when trying to access quality healthcare products and services. She envisioned a pharmacy that would not only dispense medications but also provide education, support, and personalized care to every customer.</p>
                    </div>
                    <div class="about-image">
                        <img src="images/placeholder.jpg" alt="PharmaCare Store">
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Our Mission -->
        <section class="about-section bg-light">
            <div class="container">
                <div class="about-content reverse">
                    <div class="about-text">
                        <h2>Our Mission</h2>
                        <p>At PharmaCare, we are committed to improving the health and wellbeing of our community. We believe that everyone deserves access to quality healthcare products and expert advice to help them make informed decisions about their health.</p>
                        <p>Our mission is to:</p>
                        <ul>
                            <li>Provide high-quality medications and healthcare products at affordable prices</li>
                            <li>Deliver exceptional customer service with compassion and respect</li>
                            <li>Offer expert advice and education to empower our customers</li>
                            <li>Support our community through health initiatives and outreach programs</li>
                            <li>Embrace innovation to continually improve the healthcare experience</li>
                        </ul>
                    </div>
                    <div class="about-image">
                        <img src="images/placeholder.jpg" alt="PharmaCare Mission">
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Our Team -->
        <section class="about-section">
            <div class="container">
                <h2 class="text-center">Our Leadership Team</h2>
                <div class="team-grid">
                    <div class="team-member">
                        <div class="team-image">
                            <img src="images/placeholder.jpg" alt="Dr. Sarah Chen">
                        </div>
                        <h3>Dr. Sarah Chen</h3>
                        <p class="team-role">Founder & CEO</p>
                        <p>With over 20 years of experience in healthcare, Dr. Chen leads our company with passion and expertise.</p>
                    </div>
                    
                    <div class="team-member">
                        <div class="team-image">
                            <img src="images/placeholder.jpg" alt="Dr. Michael Roberts">
                        </div>
                        <h3>Dr. Michael Roberts</h3>
                        <p class="team-role">Chief Pharmacist</p>
                        <p>Dr. Roberts oversees all pharmaceutical operations and ensures the highest quality standards.</p>
                    </div>
                    
                    <div class="team-member">
                        <div class="team-image">
                            <img src="images/placeholder.jpg" alt="Lisa Johnson">
                        </div>
                        <h3>Lisa Johnson</h3>
                        <p class="team-role">Customer Experience Director</p>
                        <p>Lisa is dedicated to creating exceptional experiences for every PharmaCare customer.</p>
                    </div>
                    
                    <div class="team-member">
                        <div class="team-image">
                            <img src="images/placeholder.jpg" alt="David Kim">
                        </div>
                        <h3>David Kim</h3>
                        <p class="team-role">Operations Manager</p>
                        <p>David ensures our stores run efficiently while maintaining our commitment to quality service.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Values -->
        <section class="about-section bg-light">
            <div class="container">
                <h2 class="text-center">Our Values</h2>
                <div class="values-grid">
                    <div class="value-item">
                        <div class="value-icon">❤️</div>
                        <h3>Care</h3>
                        <p>We genuinely care about our customers' health and well-being, treating each person with compassion and respect.</p>
                    </div>
                    
                    <div class="value-item">
                        <div class="value-icon">🔒</div>
                        <h3>Integrity</h3>
                        <p>We maintain the highest ethical standards in all our business practices and always put our customers' interests first.</p>
                    </div>
                    
                    <div class="value-item">
                        <div class="value-icon">📚</div>
                        <h3>Knowledge</h3>
                        <p>We are committed to ongoing education and sharing our expertise to help customers make informed health decisions.</p>
                    </div>
                    
                    <div class="value-item">
                        <div class="value-icon">🤝</div>
                        <h3>Community</h3>
                        <p>We actively participate in community initiatives and strive to make a positive impact on public health.</p>
                    </div>
                    
                    <div class="value-item">
                        <div class="value-icon">💡</div>
                        <h3>Innovation</h3>
                        <p>We continuously seek new ways to improve our products, services, and the overall healthcare experience.</p>
                    </div>
                    
                    <div class="value-item">
                        <div class="value-icon">🌿</div>
                        <h3>Sustainability</h3>
                        <p>We are committed to environmentally responsible practices in all aspects of our business.</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Call to Action -->
        <section class="cta-section">
            <div class="container">
                <h2>Experience the PharmaCare Difference</h2>
                <p>Visit one of our stores or shop online today.</p>
                <div class="cta-buttons">
                    <a href="shop.php" class="btn btn-primary">Shop Now</a>
                    <a href="contact.php" class="btn btn-ghost">Contact Us</a>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <style>
        .about-hero {
            background-color: var(--primary-color);
            color: white;
            padding: 5rem 0;
            text-align: center;
        }
        
        .about-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .about-hero p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .about-section {
            padding: 5rem 0;
        }
        
        .bg-light {
            background-color: #f9f9f9;
        }
        
        .about-content {
            display: flex;
            gap: 3rem;
            align-items: center;
        }
        
        .about-content.reverse {
            flex-direction: row-reverse;
        }
        
        .about-text {
            flex: 1;
        }
        
        .about-text h2 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
        }
        
        .about-text p {
            margin-bottom: 1rem;
            line-height: 1.6;
        }
        
        .about-text ul {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .about-text li {
            margin-bottom: 0.5rem;
        }
        
        .about-image {
            flex: 1;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .text-center {
            text-align: center;
        }
        
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .team-member {
            text-align: center;
        }
        
        .team-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .team-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .team-member h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }
        
        .team-role {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .value-item {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
        }
        
        .value-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .value-item h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .cta-section {
            background-color: var(--primary-color);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }
        
        .cta-section h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        
        .cta-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        
        @media (max-width: 991px) {
            .about-content {
                flex-direction: column;
            }
            
            .about-content.reverse {
                flex-direction: column;
            }
            
            .about-image {
                margin-top: 2rem;
            }
            
            .team-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }
    </style>
</body>
</html>
