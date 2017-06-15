<?php

namespace sborislav\ApiBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class UserResponse
{
    const DATE_FORMAT = "Y-m-d H:i:s";

    static function Json( $data ){

        if ( empty($data) ) return new JsonResponse( array() );

        $dateFunction = function ($dateTime) {
            return $dateTime instanceof \DateTime
                ? $dateTime->format(self::DATE_FORMAT )
                : '';
        };

        $groupFunction = function($group){
            return !is_null($group)
                ? array('id' => $group->getId(), 'name' => $group->getName())
                : null;
        };

        $normalizer = new ObjectNormalizer();
        $normalizer->setCallbacks( array( 'createDate' => $dateFunction, 'group' => $groupFunction ) );

        $serializer = new Serializer( array( $normalizer ), array( new JsonEncoder() ) );
        $response =  new Response( $serializer->serialize( $data,'json' ) );

        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }
}