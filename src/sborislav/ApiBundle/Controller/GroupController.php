<?php

namespace sborislav\ApiBundle\Controller;

use sborislav\ApiBundle\Entity\Group;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use sborislav\ApiBundle\Form\newGroupType;
use sborislav\ApiBundle\Form\modifyGroupType;

class GroupController extends Controller
{
    /**
     * @Route("/groups", name="group_list")
     * @Method("GET")
     */
    public function fetchAction()
    {
        $em = $this->getDoctrine()->getManager();

        $groups = $em->getRepository('ApiBundle:Group')->findAll();

        if (null === $groups) $groups = array();

        $serializer = new Serializer(array( new ObjectNormalizer() ), array( new JsonEncoder() ));
        $response =  new Response( $serializer->serialize($groups,'json') );

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/groups", name="create_group")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $group = new Group();

        $FormBuilder = $this->createForm( newGroupType::class, $group, array(
            'action' => $this->generateUrl('create_group'),
            'method' => 'POST',
        ));

        $FormBuilder->handleRequest($request);
        if ($FormBuilder->isSubmitted() && $FormBuilder->isValid()) {

            try {
                $em->persist($group);
                $em->flush();
                return new JsonResponse(array('status' => true, 'message' => 'group created'));
            } catch (\Exception $e) {
                return new JsonResponse(array('status' => false, 'message' => 'group not created'));
            }

        }
        return new JsonResponse(array('status' => false, 'message' => 'not valid'));
    }

    /**
     * @Route("/groups/{id}", name="group_id_modify", requirements={"page": "\d+"})
     * Method("PUT")
     */
    public function modifyAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $Group = $em->getRepository('ApiBundle:Group')->find($id);

        if ( $Group === null)
            return new JsonResponse(array('status' => false, 'message' => 'not found'));

        $updateGroupForm = $this->createForm( modifyGroupType::class,  $Group, array(
            'action' => $this->generateUrl('group_id_modify', array('id' => $id)),
            'method' => 'PUT',
        ));

        $updateGroupForm->handleRequest($request);
        if ($updateGroupForm->isSubmitted() && $updateGroupForm->isValid()) {

            try {
                $em->flush();
                return new JsonResponse(array('status' => true, 'message' => 'group changed'));
            } catch (\Exception $e) {
                return new JsonResponse(array('status' => false, 'message' => 'group not changed'));
            }
        }
        return new JsonResponse(array('status' => false, 'message' => 'not valid'));
    }
}
