<?php

?>

<Doctype html>
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{% block title %}{% end block %}</title>
            
            <!-- Styles -->
            <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" /><!-- Bootstrap -->
            <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" /><!-- Icons -->
            <link rel="stylesheet" href="css/owl.carousel.css" type="text/css" /><!-- Owl Carousal -->

            <link rel="stylesheet" href="css/style.css" type="text/css" /><!-- Style -->	
            <link rel="stylesheet" href="css/responsive.css" type="text/css" /><!-- Responsive -->	
            <link rel="stylesheet" href="css/colors/colors.css" type="text/css" /><!-- color -->	


            <!-- REVOLUTION STYLE SHEETS -->
            <link rel="stylesheet" type="text/css" href="revolution/css/settings.css">
            <!-- REVOLUTION LAYERS STYLES -->
            <link rel="stylesheet" type="text/css" href="revolution/css/layers.css">	
            <!-- REVOLUTION NAVIGATION STYLES -->
            <link rel="stylesheet" type="text/css" href="revolution/css/navigation.css">

        </head>
        <body>

                <?= $contentToDisplay ?>                
            
        <script src="js/jquery-2.1.1.js"></script>
        <script src="js/jquery.mobile.custom.min.js"></script>
        <script src="js/main.js"></script> <!-- Resource jQuery -->
        </body>
    </html>

