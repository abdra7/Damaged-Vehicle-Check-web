<?php include 'header.php'; ?>
<main>
    <h1>Ratings & Suggestions</h1>
    <form action="ratings.php" method="post">
        <label for="rating">Rating:</label>
        <select name="rating" id="rating" required>
            <option value="5">5 Stars</option>
            <option value="4">4 Stars</option>
            <option value="3">3 Stars</option>
            <option value="2">2 Stars</option>
            <option value="1">1 Star</option>
        </select>
        <br>
        <label for="suggestion">Suggestion:</label>
        <textarea name="suggestion" id="suggestion" rows="4" required></textarea>
        <br>
        <button type="submit" class="btn">Submit</button>
    </form>
</main>
<?php include 'footer.php'; ?>