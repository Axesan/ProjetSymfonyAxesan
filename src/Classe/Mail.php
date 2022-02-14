<?php

namespace App\Classe;
use Mailjet\Client;
use Mailjet\Resources;

class Mail {
    // Clé d'api 
    private $api_key = "bec6b52b449ab8ddbc9a6364c97fa00c";
    private $api_key_secret = "736919a82438ad10a8af0b826392bd05";

    // Envoie d'email 
    public function send( $to_email,$to_name,$subject, $content){
        $mj = new Client( $this->api_key ,$this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "axel.stanislas.cdaw@gmail.com",
                        'Name' => "E-commerce - Titi"
                    ], 
                    /* 
                        A qui je doit l'envoyer ?
                        Notre variable $to_mail signifie l'email du client
                        et la variable $to_name et donc le nom du clients   
                    */
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    // Le parametre 'TemplateID' et Notre template que l'on a conceptionner dans 'mailjet'
                    'TemplateID' => 3565475,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    // Variable que l'on a utiliser sur la plateforme mailjet pour notre exemple c'est 'content'
                    'Variables' => [
                        'content' => $content,
                    
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        //Le retour du mailjet
        $response->success();
    }

}