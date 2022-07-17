<?php

namespace App\Util;
 
use Symfony\Component\Serializer\SerializerInterface;
 
class MovieResponseUtil
{
    private $serializer;
 
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }
 
    public function serialize($model): string
    {
        $result = false;

        if(is_array($model) && count($model)) {
            foreach($model as $data) {
                $result[] = $this->sanitizeObject($data);
            }
        } else {
            $result = $this->sanitizeObject($model);
        }

        return $this->serializer->serialize($result, 'json');
    }

    private function sanitizeObject($data) {

        if(empty($data)) {
            return ['message' => "no data found!"];
        }

        return [
            'id' => $data->getId(),
            'name' => $data->getName(),
            'release_date' => $data->getReleaseDate(), 
            'director' => $data->getDirector(),
            'casts' => $data->getCast(),
            'ratings' => $data->getRating()
        ];
    }
}