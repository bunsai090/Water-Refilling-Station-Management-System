<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Water Refilling Station Management</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="frontend/assets/css/main.css">
    <?php if(isset($additional_css)): ?>
        <?php foreach($additional_css as $css): ?>
            <link rel="stylesheet" href="<?php echo $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="frontend/assets/images/logo.svg">
</head>
<body>
