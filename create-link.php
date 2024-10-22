<?php
// Define the paths
$target = '/home/bukinist/domains/bukinistebi.ge/public_html/storage/app/public';
$link = '/home/bukinist/domains/bukinistebi.ge/public_html/public/storage';

// Check if the symbolic link already exists
if (!file_exists($link)) {
    // Create the symbolic link
    if (symlink($target, $link)) {
        echo "Symbolic link created successfully.";
    } else {
        echo "Failed to create symbolic link.";
    }
} else {
    echo "Symbolic link already exists.";
}
?>
