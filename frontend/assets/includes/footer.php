    <!-- JavaScript Files -->
    <script src="frontend/assets/js/main.js"></script>
    <?php if(isset($additional_js)): ?>
        <?php foreach($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Chart.js for reports -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Water Refilling Station Management System. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
