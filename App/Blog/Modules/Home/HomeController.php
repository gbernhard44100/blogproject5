<?php

namespace App\Blog\Modules\Home;

use Lib\GBFram\Controller;
use \Lib\GBFram\HTTPRequest;

class HomeController extends Controller
{

    public function executeHomePage()
    {
        
    }

    public function executeSendContactForm(HTTPRequest $request)
    {
        $this->setView('HomePage');
        if (!($request->method() == 'POST'))
            if (!defined("PHP_EOL"))
                define("PHP_EOL", "\r\n");

        $name = $request->postData('name');
        $email = $request->postData('email');
        $comments = $request->postData('comments');

        $ableToSend = true;

        if (trim($name) == '') {
            $ableToSend = false;
            $this->page->addVar('redflash', 'Attention : le formulaire n\'a pas été envoyé! Vous devez entrer votre nom.');
        }
        if (trim($email) == '') {
            $ableToSend = false;
            $this->page->addVar('redflash', 'Attention : le formulaire n\'a pas été envoyé! Il faut entrer une adresse email valide.');
        }
        if (!$this->isEmail($email)) {
            $ableToSend = false;
            $this->page->addVar('redflash', 'Attention : le formulaire n\'a pas été envoyé! L\'adresse e-mail que vous avez entrée est incorrecte.');
        }
        if (trim($comments) == '') {
            $ableToSend = false;
            $this->page->addVar('redflash', 'Attention : le formulaire n\'a pas été envoyé! Vous n\'avez pas écrit de message.');
        }

        if (get_magic_quotes_gpc()) {
            $comments = stripslashes($comments);
        }

        if ($ableToSend) {

            $address = "gaetan.bernhard@gmail.com";

            $eSubject = 'Vous avez été contacté par ' . $name . '.';
            $subject = "Mon blog professionnel";
            $eBody = "Vous avez été contacté par $name concernant : $subject, et son message est ci-dessous." . PHP_EOL . PHP_EOL;
            $eContent = "\"$comments\"" . PHP_EOL . PHP_EOL;
            $eReply = "Vous pouvez contacter $name par cet email, $email";

            $msg = wordwrap($eBody . $eContent . $eReply, 70);

            $headers = "From: $email" . PHP_EOL;
            $headers .= "Reply-To: $email" . PHP_EOL;
            $headers .= "MIME-Version: 1.0" . PHP_EOL;
            $headers .= "Content-type: text/plain; charset=utf-8" . PHP_EOL;
            $headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

            if (mail($address, $eSubject, $msg, $headers)) {
                $this->page->addVar('greenflash', 'J\'ai avons bien reçu votre message, ' . $name . '. Merci de m\'avoir contacter!');
            } else {
                echo 'ERROR!';
            }
        }
    }

    public function isEmail($email)
    {
        return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email));
    }

}
