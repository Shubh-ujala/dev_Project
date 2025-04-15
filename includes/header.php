<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Grievance System'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1e3a8a',
                        'secondary': '#3b82f6',
                    }
                }
            }
        }
    </script>
    
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-300 min-h-screen font-sans">