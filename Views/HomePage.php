<?php

?>

<DOCTYPE HTML>
    <html>
        <head>
            <title>Bernhard Design</title>
        </head>
        <body>
            <div class="banner">
                <H1>Gaëtan BERNHARD</H1>
                <H2>Made to be your Web designer</H2>
                
            </div>
            
            <NAV>
                <ul>
                    <LI><a href="index.php">Page d'accueil</a></LI>
                    <li><a href="index.php?action=requestAddBlogPost">Ajouter un article</a></li>
                    <li><a href="index.php?action=showAllBlogPosts">Liste des articles</a></li>

                </ul>

            </NAV>
            <DIV>
                <FORM action='index.php?action=sendEmail' method="POST">
                    <LABEL>Nom :</LABEL><INPUT  type="text" name="name" id="name"></br>
                    <LABEL>Prénom :</LABEL><INPUT  type="text" name="firstname" id="firstname"></br>
                    <LABEL>Email :</LABEL><INPUT  type="email" name="email" id="email"></br>
                    <LABEL>Message :</LABEL><TEXTAREA name="message" id="message"></TEXTAREA></br>
                    <BUTTON type="submit">Envoyer</BUTTON>
                </FORM>
            </DIV>
            <DIV>
                <p><a href="Public/Bernhard_G-CV.pdf" download>Mon cv ici</a></p>
                <p><a href="https://www.linkedin.com/in/gaëtan-bernhard-b0b9074a">LinkedIn</a></p>
            </DIV>
        </body>
    </html>
