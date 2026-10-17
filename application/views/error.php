<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        .error {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <lottie-player 
        class="error"
        src="<?php echo base_url('/uploads/animation_js.json'); ?>"  
        background="transparent"  
        speed="1"  
        style="width: 400px; height: 400px;"  
        loop  
        autoplay>
    </lottie-player>
</body>
</html>
