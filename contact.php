
<?php
require_once 'config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple form validation
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // In a real application, you would send an email here
        // For now, we'll just show a success message
        
        // Example email sending code (commented out)
        /*
        $to = 'contact@pharmacare.com';
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $email_message = "Name: $name\n";
        $email_message .= "Email: $email\n";
        $email_message .= "Subject: $subject\n";
        $email_message .= "Message:\n$message";
        
        mail($to, "Contact Form: $subject", $email_message, $headers);
        */
        
        // Store the message in the database (optional)
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        
        // If the contact_messages table doesn't exist, create it
        $conn->query("CREATE TABLE IF NOT EXISTS contact_messages (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            subject VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        if ($stmt->execute()) {
            $success = 'Thank you for contacting us! We will get back to you soon.';
            
            // Reset the form fields
            $name = $email = $subject = $message = '';
        } else {
            $error = 'Something went wrong. Please try again later.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - PharmaCare</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main>
        <section class="contact-hero">
            <div class="container">
                <h1>Contact Us</h1>
                <p>We're here to help with any questions or concerns</p>
            </div>
        </section>
        
        <section class="contact-section">
            <div class="container">
                <div class="contact-content">
                    <div class="contact-info">
                        <h2>Get In Touch</h2>
                        
                        <div class="contact-methods">
                            <div class="contact-method">
                                <div class="contact-icon">📞</div>
                                <h3>Phone</h3>
                                <p>Customer Service: (555) 123-4567</p>
                                <p>Prescription Line: (555) 123-4568</p>
                            </div>
                            
                            <div class="contact-method">
                                <div class="contact-icon">📧</div>
                                <h3>Email</h3>
                                <p>General Inquiries: <a href="mailto:info@pharmacare.com">info@pharmacare.com</a></p>
                                <p>Customer Support: <a href="mailto:support@pharmacare.com">support@pharmacare.com</a></p>
                            </div>
                            
                            <div class="contact-method">
                                <div class="contact-icon">🏢</div>
                                <h3>Visit Us</h3>
                                <p>123 Health Street</p>
                                <p>Wellness City, WC 12345</p>
                            </div>
                            
                            <div class="contact-method">
                                <div class="contact-icon">⏰</div>
                                <h3>Hours</h3>
                                <p>Monday-Friday: 8am - 9pm</p>
                                <p>Saturday-Sunday: 9am - 7pm</p>
                            </div>
                        </div>
                        
                        <div class="contact-map">
                            <h3>Our Location</h3>
                            <!-- Replace with your actual map embed code -->
                            <div class="map-placeholder">
                                <img src="images/placeholder.jpg" alt="Map Location">
                            </div>
                        </div>
                    </div>
                    
                    <div class="contact-form-wrapper">
                        <h2>Send a Message</h2>
                        
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <form class="contact-form" method="post">
                            <div class="form-group">
                                <label for="name">Your Name</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" name="subject" value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="5" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="faq-section">
            <div class="container">
                <h2 class="text-center">Frequently Asked Questions</h2>
                
                <div class="faq-items">
                    <div class="faq-item">
                        <div class="faq-question">How do I order prescription medications?</div>
                        <div class="faq-answer">
                            <p>To order prescription medications, you need to upload a valid prescription during the checkout process or visit one of our physical stores with your prescription. Our pharmacist will verify your prescription before processing your order.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">What is your shipping policy?</div>
                        <div class="faq-answer">
                            <p>We offer free standard shipping on all orders over $50. Standard shipping takes 3-5 business days, while express shipping (available for an additional fee) takes 1-2 business days.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">Can I return medications?</div>
                        <div class="faq-answer">
                            <p>For safety and regulatory reasons, we cannot accept returns of prescription medications unless they were dispensed in error or are defective. Non-prescription items can be returned within 30 days of purchase if unopened and in their original packaging.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item">
                        <div class="faq-question">Do you offer consultation services?</div>
                        <div class="faq-answer">
                            <p>Yes, our licensed pharmacists are available for consultation during business hours, both in-store and via phone. We can provide advice on medications, potential interactions, and general health questions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <style>
        .contact-hero {
            background-color: var(--primary-color);
            color: white;
            padding: 5rem 0;
            text-align: center;
        }
        
        .contact-hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .contact-hero p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .contact-section {
            padding: 5rem 0;
        }
        
        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
        }
        
        .contact-content h2 {
            font-size: 2rem;
            margin-bottom: 2rem;
            color: var(--primary-color);
        }
        
        .contact-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .contact-method {
            background-color: #f9f9f9;
            padding: 1.5rem;
            border-radius: 8px;
        }
        
        .contact-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .contact-method h3 {
            margin-bottom: 1rem;
            color: var(--dark-color);
        }
        
        .contact-method p {
            margin-bottom: 0.5rem;
        }
        
        .contact-method a {
            color: var(--primary-color);
        }
        
        .contact-map {
            margin-top: 2rem;
        }
        
        .contact-map h3 {
            margin-bottom: 1rem;
            color: var(--dark-color);
        }
        
        .map-placeholder {
            width: 100%;
            height: 300px;
            background-color: #f1f1f1;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .map-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .contact-form-wrapper {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .form-group textarea {
            resize: vertical;
        }
        
        .btn-block {
            display: block;
            width: 100%;
        }
        
        .faq-section {
            background-color: #f9f9f9;
            padding: 5rem 0;
        }
        
        .faq-section h2 {
            margin-bottom: 3rem;
        }
        
        .faq-items {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .faq-item {
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .faq-question {
            background-color: white;
            padding: 1.5rem;
            font-weight: 600;
            cursor: pointer;
            position: relative;
        }
        
        .faq-question::after {
            content: "+";
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            transition: transform 0.3s;
        }
        
        .faq-question.active::after {
            transform: translateY(-50%) rotate(45deg);
        }
        
        .faq-answer {
            background-color: white;
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .faq-answer.active {
            padding: 0 1.5rem 1.5rem;
            max-height: 500px;
        }
        
        @media (max-width: 991px) {
            .contact-content {
                grid-template-columns: 1fr;
            }
            
            .contact-info {
                order: 2;
            }
            
            .contact-form-wrapper {
                order: 1;
                margin-bottom: 2rem;
            }
        }
        
        @media (max-width: 767px) {
            .contact-methods {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <script>
        // FAQ accordion functionality
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const answer = this.nextElementSibling;
                    
                    // Toggle active class on question
                    this.classList.toggle('active');
                    
                    // Toggle answer visibility
                    if (answer.style.maxHeight) {
                        answer.style.maxHeight = null;
                        answer.style.padding = "0 1.5rem";
                    } else {
                        answer.style.maxHeight = answer.scrollHeight + 30 + "px";
                        answer.style.padding = "0 1.5rem 1.5rem";
                    }
                });
            });
        });
    </script>
</body>
</html>
