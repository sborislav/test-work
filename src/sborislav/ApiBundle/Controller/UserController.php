<?php

namespace sborislav\ApiBundle\Controller;

use sborislav\ApiBundle\ApiBundle;
use sborislav\ApiBundle\Entity\Group;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use sborislav\ApiBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use sborislav\ApiBundle\Form\newUserType;
use sborislav\ApiBundle\Form\modifyUserType;

use sborislav\ApiBundle\Response\UserResponse;

class UserController extends Controller
{
    const DATE_FORMAT = "Y-m-d H:i:s";

    /**
     * @Route("/users", name="users_list")
     * @Method("GET")
     */
    public function fetchAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ApiBundle:User' )->findAll();

        return UserResponse::Json($users);
    }

    /**
     * @Route("/users", name="create_user")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = new User();

        $FormBuilder = $this->createForm( newUserType::class, $user, array(
            'action' => $this->generateUrl('create_user'),
            'method' => 'POST',
        ));

        $FormBuilder->handleRequest($request);
        if ($FormBuilder->isSubmitted() && $FormBuilder->isValid()) {
            try {
                $user->setCreateDate(new \DateTime());
                $user->setState(false);
                $em->persist($user);
                $em->flush();
                return new JsonResponse(array('status' => true, 'message' => 'user created'));
            } catch (\Exception $e) {
                return new JsonResponse(array('status' => false, 'message' => 'user not created'));
            }
        }
        return new JsonResponse(array('status' => false, 'message' => 'not valid'));
    }

    /**
     * @Route("/users/{id}", name="users_id_info", requirements={"page": "\d+"})
     * @Method("GET")
     */
    public function userAction( $id )
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ApiBundle:User')->find($id);
        return UserResponse::Json($user);
    }

    /**
     * @Route("/users/{id}", name="user_id_modify", requirements={"page": "\d+"})
     * @Method("PUT")
     */
    public function modifyAction( $id, Request $request )
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('ApiBundle:User');
        $user_find = $user->find($id);

        if ( $user_find === null) return new JsonResponse(array('status' => false, 'message' => 'not found'));

        $user_obj = new User();

        $UserForm = $this->createForm( modifyUserType::class, $user_obj, array(
            'action' => $this->generateUrl('user_id_modify', array('id' => $id)),
            'method' => 'PUT',
        ));

        $UserForm->handleRequest($request);
        if ($UserForm->isSubmitted() && $UserForm->isValid()) {

            function prepare( $col , $UserForm, $user_find ){
                if ( $UserForm->has($col) ) {
                    $data = $UserForm->get($col)->getData();
                    if ( $data !== null ) {
                        $f = 'set'.ucfirst($col);
                        if ( method_exists($user_find, $f) )
                            $user_find->$f( $data );
                    }

                }
            }

            try {
                prepare('email', $UserForm, $user_find);
                prepare('lastName', $UserForm, $user_find);
                prepare('firstName', $UserForm, $user_find);
                prepare('group', $UserForm, $user_find);
                prepare('state', $UserForm, $user_find);
                $em->flush();

                return new JsonResponse(array('status' => true, 'message' => 'user changed'));
            } catch (\Exception $e) {
                return new JsonResponse(array('status' => false, 'message' => 'user not changed'));
            }
        }
        return new JsonResponse(array('status' => false, 'message' => 'not valid'));
    }
}
