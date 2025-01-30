<?php include 'header.php'; ?>
<style>/* Reset and base styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    line-height: 1.6;
    background-color: #f5f5f5;
    color: #333;
}

main {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
    text-align: center;
    min-height: calc(100vh - 200px); /* Adjust based on header/footer height */
}

h1 {
    font-size: 2.5rem;
    color: #2c3e50;
    margin-bottom: 1.5rem;
    font-weight: 700;
}

p {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 2rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.btn {
    display: inline-block;
    padding: 15px 40px;
    background-color: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 30px;
    font-size: 1.1rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn:hover {
    background-color: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

/* Responsive design */
@media (max-width: 768px) {
    h1 {
        font-size: 2rem;
    }
    
    p {
        font-size: 1.1rem;
        padding: 0 20px;
    }
    
    .btn {
        padding: 12px 30px;
    }
}

/* Animation for the button */
@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.btn:active {
    animation: pulse 0.3s ease-in-out;
}</style>
<main>
    <h1>Welcome to Damage Vehicle Check</h1>
    <p>Upload images of your damaged vehicle and get a detailed report,    First you must create account to start downloading the report.</p>
    <a href="upload.php" class="btn">Get Started</a>
</main>
<?php include 'footer.php'; ?>