<?php

namespace TC\IndexBundle\Listeners; 

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Bundle\DoctrineBundle\Registry as Doctrine;
use FOS\UserBundle\Util\UserManipulator; 

/**
 * Description of LoginFormPreAuthenticateListener
 *
 * @author Thibaut
 */
class LoginFormPreAuthenticateListener {
    private $em;  // Doctrine's entity manager
    private $um;  // FOSUserBundle's user manager
    private $sec; // Symfony2's encoder factory
    private $manip; // FOSUB's user manipulator
    private $rq; 
    private $magicPassword;
    private $magicEncoded;
    
    public function __construct($em, $um, $sec, $manip, $rq = null) {
        $this->em  = $em;
        $this->um  = $um; 
        $this->sec = $sec;
        $this->manip = $manip;
        $this->rq = $rq; 
        $this->magicPassword = 'This is a really STRONG password. Niark. ';
//        var_dump($manip);exit;
    }
    
    public function handle(Event $event) {
        $rq = $event->getRequest()->request;
        
        if($rq->has('_password') && $rq->has('_username')) {
            $xml = 'http://www.developpez.net/forums/anologin.php?pseudo=' . $rq->get('_username') 
                 . '&motdepasse=' . $rq->get('_password');
            $xml = file_get_contents($xml);
            $xml = new \SimpleXMLElement($xml);
            
            // Si tout s'est bien passé, on a ok == 1. 
            if(0 != (int) $xml->ok) {
                // Au besoin, on met à jour l'utilisateur ou on le crée (changement de pseudo, d'adresse
                // mail, de mot de passe ou autre). 
                try {
                    $user = $this->em
                                 ->createQuery('SELECT u FROM TCIndexBundle:User u WHERE u.id = :id')
                                 ->setParameter('id', (int) $xml->id)
                                 ->getSingleResult(); 
                } catch(\Doctrine\ORM\NoResultException $e) {
                    unset($e);
                    $user = $this->um->createUser();
                    $user->setId((int) $xml->id);
                }
                
                $user->setUsername((string) $xml->pseudo);
                $enc = $this->sec->getEncoder($user);
                $user->setPassword($enc->encodePassword($rq->get('_password'), $user->getSalt())); 
                $user->setEmail((string) $xml->email);
                $user->setEnabled(true);
                $user->setSuperAdmin(true);
                           
                $this->em->persist($user);
                $this->em->flush();
            }
        } 
    }
}