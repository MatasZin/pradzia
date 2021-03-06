<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;
use App\Entity\Car;

class CarsControllerTest extends WebTestCase
{

    protected $em;
    protected $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = $this->createClient(array(), array(
            'PHP_AUTH_USER' => 'admin@admin.com',
            'PHP_AUTH_PW'   => 'admin',
        ));
        $this->client->disableReboot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->em->beginTransaction();
        $this->em->getConnection()->setAutoCommit(false);
    }
  
    protected function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        if ($this->em->getConnection()->isTransactionActive())
        {
            $this->em->rollback();
            $this->em->close();
        }
    }

    public function testCarsPage()
    {
        $this->client->request('GET', '/cars');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
        //$client->followRedirect();
        $this->assertRegExp('/\/cars$/', $this->client->getRequest()->getUri());
    }

    public function testAddNewCar()
    {
        $crawler = $this->client->request('GET', '/cars');

        $form = $crawler->selectButton('Add car')->form();
        $form['car[licensePlate]'] = 'BNA447';
        $this->client->submit($form);
        $this->client->followRedirect();
        //test response
        $this->assertEquals(200,
            $this->client->getResponse()->getStatusCode()
        );
        $car = $this->em
            ->getRepository(Car::class)
            ->findBy(array(
                'licensePlate' => 'BNA447',
            ));
        $this->assertCount(1, $car);
    }
}