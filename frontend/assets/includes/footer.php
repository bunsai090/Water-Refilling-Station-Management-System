    <!-- Chart.js MUST load first -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- JavaScript Files -->
    <script src="frontend/assets/js/main.js"></script>
    <?php if(isset($additional_js)): ?>
        <?php foreach($additional_js as $js): ?>
            <script src="<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Water Refilling Station Management System. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- Logout Confirmation Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-header">
                <h3>Logout Confirmation</h3>
                <button class="modal-close" onclick="closeLogoutModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p style="text-align: center; margin: 0; font-size: 16px;">Are you sure you want to logout?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeLogoutModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmLogout()">OK</button>
            </div>
        </div>
    </div>
    
    <script src="frontend/assets/js/auth.js"></script>
</body>
</html>
