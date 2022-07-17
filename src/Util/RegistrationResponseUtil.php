<?php

namespace App\Util;
 
use Symfony\Component\Serializer\SerializerInterface;
 
class RegistrationResponseUtil
{
    private $serializer;
 
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }
 
    public function serialize(object $model): string
    {
        $result = [
                   'id' => $model->getId(), 
                   'name' => $model->getName(),
                   'username' => $model->getUsername(),
                   'email' => $model->getEmail()
                  ];
        return $this->serializer->serialize($result, 'json');
    }
}