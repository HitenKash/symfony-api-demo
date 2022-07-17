<?php

namespace App\Tests\Auth;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\User;
use App\Entity\MovieCasts;
use App\Entity\Movies;
use App\Entity\MovieRatings;
use Doctrine\ORM\Tools\SchemaTool;

abstract class AbstarctAuthTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private $passwordEncoder;

    private $client = null;

    public function getClient() {
    	return $this->client;
    }
    public function getEntityManager() {
    	return $this->entityManager;
    }

    public function getPEncoder() {
    	return $this->passwordEncoder;
    }

    public function getPassword() {
    	return "password1";
    }

    public function getUsername() {
    	return "username";
    }

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

		$this->client = $kernel->getContainer()->get('test.client');
		$upe = $this->getMockBuilder(\Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface::class)->getMock();
		$upe->expects($this->any())
		            ->method('encodePassword')
		            ->willReturn('$2y$13$mzYTY8Bl.IhvSEx/ngQI2.LkN24D0jqTr6JRLfEZeJYlFR/vlu9qu');

		$this->passwordEncoder = $upe;

       //In case leftover entries exist
       $schemaTool = new SchemaTool($this->entityManager);
       $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();

       // Drop and recreate tables for all entities
       $schemaTool->dropSchema($metadata);
       $schemaTool->createSchema($metadata);
    }

    public function createUser()
    {
    	$user = new User();
    	$user->setUsername($this->getUsername());
    	$user->setName("MocName");
    	$user->setEmail("moc@moc.com");
    	$user->setPassword(
    		$this->passwordEncoder->encodePassword(
    			$user, $this->getPassword()
    		)
    	);
    	$user->setIsActive(1);
    	$user->setIsAdmin(0);

    	$this->getEntityManager()->persist($user);
    	$this->getEntityManager()->flush();
    }


    public function createNewUser()
    {
        $user = new User();
        $user->setUsername('newMockName');
        $user->setName("NewMockName");
        $user->setEmail("mocc@mocc.com");
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user, $this->getPassword()
            )
        );
        $user->setIsActive(1);
        $user->setIsAdmin(0);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
    /**
	 * Create a client with a default Authorization header.
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return \Symfony\Bundle\FrameworkBundle\Client
	 */
	protected function createAuthenticatedClient($username = 'user', $password = 'password')
	{
	    $this->client->request(
	      'POST',
	      '/api/login_check',
	      [],
	      [],
	      ['CONTENT_TYPE' => 'application/json'],
	      json_encode([
	        'username' => $username,
	        'password' => $password,
	      ])
	    );

	    $data = json_decode($this->client->getResponse()->getContent(), true);
		
		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
	    
	    $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

	}
}