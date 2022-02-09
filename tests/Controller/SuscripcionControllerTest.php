<?php

namespace App\Tests\Controller;

use App\Repository\LogRepository;
use App\Repository\UserRepository;
use App\Repository\SuscripcionRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SuscripcionControllerTest extends WebTestCase
{
    public function testSuscripcionNew(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('UserPrueba_1@gmail.com');
        $client->loginUser($testUser);

        //INDEX TEST
        $crawler = $client->request('GET', '/suscripcion');
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        //NEW TEST
        $crawler = $client->request('GET', '/suscripcion/new');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrwalerNode = $crawler->selectButton('Save');
        $form =$buttonCrwalerNode->form();

        $form['suscripcion[usuario]']='2';
        $form['suscripcion[tipoPublicacion]']='1';//busca por id
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //CREACION CORRECTA TEST
        $suscripcionRepository = static::getContainer()->get(SuscripcionRepository::class);
        $suscripcion1 = $suscripcionRepository->findOneByUsuario(2);//busca el id que se crea
        
        $this->assertNotNull($suscripcion1);

        //EDIT TEST
        $crawler = $client->request('GET', '/suscripcion/1/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrwalerNode = $crawler->selectButton('Update');
        $form =$buttonCrwalerNode->form();

        $form['suscripcion[usuario]']='2';
        $form['suscripcion[tipoPublicacion]']='3';//busca por id
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //EDICION CORRECTA TEST 
        $suscripcion = $suscripcionRepository->findOneByTipoPublicacion(3);//findOneBy... lo que estoy editando

        $this->assertNotNull($suscripcion);

        //DELETE TEST
        $crawler = $client->request('GET', '/suscripcion/1/edit');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $buttonCrwalerNode = $crawler->selectButton('Delete');
        $form =$buttonCrwalerNode->form();
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //ELIMINACION CORRECTA TEST
        $suscripcion2 = $suscripcionRepository->findOneById(1);

        $this->assertNull($suscripcion2);

        //CREACION DE LOG TEST
        $logRepository = static::getContainer()->get(LogRepository::class);
        $log = $logRepository->findOneById(3);//busca el id que se crea
        $log1 = $logRepository->findOneById(4);
        
        $this->assertNotNull($log);
        $this->assertNotNull($log1);


    }


}
