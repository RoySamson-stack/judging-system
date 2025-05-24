<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Judging System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <?php
    // Determine the correct path based on current directory
    $base_path = '';
    if (isset($css_path)) {
        $base_path = $css_path;
    } else {
        // Auto-detect based on current script location
        $current_dir = dirname($_SERVER['SCRIPT_NAME']);
        if (strpos($current_dir, '/admin') !== false || strpos($current_dir, '/judge') !== false) {
            $base_path = '../';
        } else {
            $base_path = '';
        }
    }
    ?>
    
    <link rel="stylesheet" href="<?php echo $base_path; ?>assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="<?php echo $base_path; ?>assets/favicon.ico">
</head>
<body>