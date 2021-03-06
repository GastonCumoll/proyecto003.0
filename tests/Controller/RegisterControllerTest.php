<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;

class RegisterControllerTest extends WebTestCase
{
    //registro correcto
    public function testRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $buttonCrwalerNode = $crawler->selectButton('Register');
        $form =$buttonCrwalerNode->form();

        $user="UserPrueba_14@gmail.com";

        $form['registration_form[email]']=$user;
        $form['registration_form[agreeTerms]']=true;
        $form['registration_form[plainPassword]']='contraseña';
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $client->submit($form);

        //TEST MAILER

        $this->assertEmailCount(1);

        $this->assertNotNull($email = $this->getMailerMessage());
        $this->assertEmailHtmlBodyContains($email, 'Hi! Please confirm your email!');

        //TEST USUARIO CREADO CORRECTO

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail($user);
        // Testea si el user creado esta persistido en la db
        $this->assertNotNull($testUser);
    }
    

    public function testMismoUsuario(): void
    {
        $newUser= "UserPrueba_1@gmail.com";

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail($newUser);

        $this->assertNotNull($testUser);        

    }

    //REGISTRO INCORRECTO TEST
    public function testNotRegister(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $buttonCrwalerNode = $crawler->selectButton('Register');
        $form =$buttonCrwalerNode->form();

        $var="hola";

        $var2=$form['registration_form[email]']=$var;
        $var3=$form['registration_form[agreeTerms]']="1";
        $var4=$form['registration_form[plainPassword]']="con";
        if(str_contains($var2,"@")){
            $test=true;
        }else{
            $test=false;
        }
        $this->assertIsNotBool($var3);
        $this->assertFalse($test);
        $this->assertGreaterThan(strlen($var4),6);
    }
    
    

}


