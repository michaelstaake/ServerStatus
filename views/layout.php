<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Server Status'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-gradient-to-r from-purple-600 to-pink-600 border-b border-white/10 text-white p-4">
        <h1 class="text-2xl font-bold text-center">
            <a href="<?php echo $baseURL; ?>" class="hover:underline text-white"><?php echo $display_name; ?></a>
        </h1>
    </header>
    <main class="container mx-auto p-4">
        <?php require $content_file; ?>
    </main>
    <footer class="bg-gray-200 p-4 text-center">
        <?php
        if (!empty($footer_content)) {
            echo $footer_content;
        } else {
            echo 'Powered by <a href="https://github.com/michaelstaake/ServerStatus" target="_blank>ServerStatus</a>';
        }
        ?>
    </footer>
</body>
</html>