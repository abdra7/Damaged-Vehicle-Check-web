<?php include 'header.php'; ?>
<main>
<link rel="stylesheet" href="style.css">
    <div class="container">
        <h1>Damage Reports</h1>
        
        <!-- Sorting Controls -->
        <div class="sort-controls">
            <select id="sortSelect" class="sort-select">
                <option value="date_desc">Newest First</option>
                <option value="date_asc">Oldest First</option>
                <option value="severity">Severity Level</option>
                <option value="status">Status</option>
            </select>
            <button class="btn btn-primary" onclick="sortReports()">Sort</button>
        </div>

        <!-- Reports Grid -->
        <div class="report-grid">
            <!-- Report Card Example -->
            <div class="report-card">
                <div class="report-header">
                    <span class="report-status resolved">Resolved</span>
                    <span class="report-date">2024-03-15</span>
                </div>
                <div class="report-body">
                    <h3>Front Bumper Damage</h3>
                    <div class="damage-severity">
                        <span class="severity-indicator medium"></span>
                        Medium Severity
                    </div>
                    <p class="report-description">Collision with stationary object resulting in cracked bumper and misalignment.</p>
                    <div class="damage-images">
                        <img src="placeholder.jpg" alt="Damage photo" class="damage-thumbnail">
                        <img src="placeholder.jpg" alt="Damage photo" class="damage-thumbnail">
                    </div>
                </div>
                <div class="report-footer">
                    <button class="btn btn-secondary">View Details</button>
                    <button class="btn btn-primary">Download Report</button>
                </div>
            </div>

            <!-- Add more report cards here -->
        </div>
    </div>
</main>
<script>
    // Basic sorting functionality
    function sortReports() {
        const sortValue = document.getElementById('sortSelect').value;
        const reportGrid = document.querySelector('.report-grid');
        const reports = [...reportGrid.children];
        
        reports.sort((a, b) => {
            // Implement actual sorting logic based on your data
            return 0; 
        });
        
        reportGrid.append(...reports);
    }
</script>

<?php include 'footer.php'; ?>