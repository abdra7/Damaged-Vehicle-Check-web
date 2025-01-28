
<?php include 'header.php'; ?>
<main>
    <h1>Book an Appointment</h1>
    <form action="booking.php" method="post">
        
        <label for="date">Preferred Date:</label>
        <input type="date" name="date" id="date" required>
        <br>
        <div class="Workshops">
            <div class="radio-tile-group">
                <div class="input-container">
                    <input id="walk" class="radio-button" type="radio" name="transport" value="Walk">
                    <div class="radio-tile">
                        <label for="Workshops" class="radio-tile-label">Workshops 1</label>
                    </div>
                </div>
                <div class="input-container">
                    <input id="bike" class="radio-button" type="radio" name="transport" value="Bike">
                    <div class="radio-tile">
                        <label for="Workshops" class="radio-tile-label">Workshops 2</label>
                    </div>
                </div>
                
                <div class="input-container">
                    <input id="drive" class="radio-button" type="radio" name="transport" value="Drive">
                    <div class="radio-tile">
                        <label for="Workshops" class="radio-tile-label">Workshops 3</label>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <button type="submit" class="btn">Book Now</button>
    </form>
</main>
<?php include 'footer.php'; ?>