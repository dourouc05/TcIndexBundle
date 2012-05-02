<?php

namespace TC\IndexBundle\Listeners; 

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Bundle\DoctrineBundle\Registry as Doctrine;
use Quiz\QuizBundle\Entity\User;

/**
 * Description of LoginFormPreAuthenticateListener
 *
 * @author Thibaut
 */
class LoginFormPreAuthenticateListener {
    private $em;  // Doctrine's entity manager
    private $um;  // FOSUserBundle's user manager
    private $sec; // Symfony2's encoder factory
    private $magicPassword;
    private $magicEncoded;
    
    public function __construct($em, $um, $sec) {
        $this->em  = $em;
        $this->um  = $um; 
        $this->sec = $sec;
        $this->magicPassword = 'This is a really STRONG password. Niark. ';
    }
    
    public function handle(Event $event) {
        $rq = $event->getRequest()->request;
        if($rq->has('_password') && $rq->has('_username')) {
            $xml = 'http://www.developpez.net/forums/anologin.php?pseudo=' . $rq->get('_username') 
                 . '&motdepasse=' . $rq->get('_password');
            $xml = file_get_contents($xml);
            $xml = new \SimpleXMLElement($xml);
            
            // Si tout s'est bien passé, on a ok == 1
            // Dans ce cas, comme on utilise un plaintext comme encoder de mdp, 
            // on sette le mot de passe à ce qu'on attend ; si l'utilisateur
            // n'est pas bien identifié par le forum, on ne change pas le mot
            // de passe reçu et il ne passera pas. 
            if(0 != (int) $xml->ok) {
                $q = $this->em->createQuery('SELECT u FROM QuizQuizBundle:User u WHERE u.id = :id')
                              ->setParameter('id', (int) $xml->id)
                              ->getResult(); // pas d'exception si aucun résultat
                
                if(0 == count($q)) {
                // pas d'utilisateur déjà en base, on devra le créer de zéro ; sinon, on laisse Sf2 et FOSUB gérer le tout
                    $user = $this->um->createUser();
                    $user->setId((int) $xml->id);
                    $user->setUsername((string) $xml->pseudo);
                } else {
                // déjà en base, on fait les modifications nécessaires pour que tout soit bien synchronisé
                    $user = $q[0];
                }
                
                $enc = $this->sec->getEncoder($user);
                $this->magicEncoded = $enc->encodePassword($this->magicPassword, $user->getSalt()); 
                $user->setPassword($this->magicEncoded); 
                $rq->set('_password', $this->magicPassword);
                
                $user->setEmail((string) $xml->email);
                $user->setFirstName((string) $xml->prenom);
                $user->setName((string) $xml->nom);
                
                if((bool) $xml->redac || (bool) $xml->resp || (bool) $xml->admin) {
                    $groups = $this->em->createQuery('SELECT g FROM QuizQuizBundle:Group g')->getResult();
                    
                    foreach($groups as $g) {
                        switch($g->getId()) {
                            case 1: // connectés
                                $user->addGroup($g);
                                break;
                            case 2: // rédaction
                                if((int) $xml->redac != '0' || (int) $xml->resp != '0' || (int) $xml->admin != '0')
                                    $user->addGroup($g);
                                else
                                    $user->removeGroup($g);
                                break;
                            case 3: // responsables
                                if((int) $xml->resp != '0' || (int) $xml->admin != '0')
                                    $user->addGroup($g);
                                else
                                    $user->removeGroup($g);
                                break;
                            case 4: // administrateurs
                                if((int) $xml->admin != '0' || (int) $xml->id == 254882)
                                    $user->addGroup($g);
                                else
                                    $user->removeGroup($g);
                                break;
                        }
                    }
                }
                
                $this->em->persist($user);
                $this->em->flush();
            }
        } 
    }
}