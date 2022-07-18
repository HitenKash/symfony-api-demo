<?php

namespace App\Util;
 
use Exception;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
 
class MovieRequestUtil
{
    private $serializer;
    private $validator;
    private $violator;
 
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        ViolationUtil $violator
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->violator = $violator;
    }
 
    public function validate(string $data, string $model): object
    {
        if (!$data) {
            throw new BadRequestHttpException('Empty body.');
        }
        
        try {
            $object = $this->serializer->deserialize($data, $model, 'json');
            $object->setRatings();
            $object->setCasts();
            $object->setReleaseDate($object->getReleaseDate());
        } catch (Exception $e) {
            throw new BadRequestHttpException('Invalid body.');
        }
 
        $errors = $this->validator->validate($object);
 
        if ($errors->count()) {
            throw new BadRequestHttpException(json_encode($this->violator->build($errors)));
        }
 
        return $object;
    }
}