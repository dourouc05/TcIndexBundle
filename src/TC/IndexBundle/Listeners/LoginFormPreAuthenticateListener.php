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
    
    public function __construct($em, $um, $sec) {
        $this->em  = $em;
        $this->um  = $um; 
        $this->sec = $sec;
    }
    
    public function handle(Event $event) {
        $rq = $event->getRequest()->request;
        
        if($rq->has('_password') && $rq->has('_username')) {
            $xml = 'http://www.developpez.net/forums/anologin.php?pseudo=' . $rq->get('_username') 
                 . '&motdepasse=' . $rq->get('_password');
            $xml = file_get_contents($xml);
            $xml = new \SimpleXMLElement($xml);
            
            // If everything went well, ok == 1. 
            if(0 != (int) $xml->ok) {
                // If needed, update the user or create one (pseudo/password/email change, etc.).
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
                
                // Idiosyncracy: only the first user may get admin right. The others won't. 
                $q = $this->em
                          ->createQuery('SELECT u FROM TCIndexBundle:User u')
                          ->getResult(); 
                if(count($q) == 0) {
                    $user->setSuperAdmin(true);
                } else {
                    $user->setSuperAdmin(false);
                }
                           
                $this->em->persist($user);
                $this->em->flush();
            }
        } 
    }
}