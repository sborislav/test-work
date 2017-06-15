<?php

namespace sborislav\ApiBundle\Controller;

use sborislav\ApiBundle\Entity\Group;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use sborislav\ApiBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use sborislav\ApiBundle\Form\newUserType;
use sborislav\ApiBundle\Form\modifyUserType;
use sborislav\ApiBundle\Form\modifyGroupType;
use sborislav\ApiBundle\Form\newGroupType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {

        // new user
        $newUserForm = $this->createForm( newUserType::class, new User(), array(
            'action' => $this->generateUrl('create_user'),
            'method' => 'POST',
        ));


        // update info for user
        $updateUserForm = $this->createForm( modifyUserType::class, new User(), array(
            'action' => $this->generateUrl('create_user'),
            'method' => 'PUT',
        ));


        $newGroupForm = $this->createForm( newGroupType::class,  new Group(), array(
            'action' => $this->generateUrl('create_group'),
            'method' => 'POST',
        ));


        $updateGroupForm = $this->createForm( modifyGroupType::class,  new Group(), array(
            'action' => $this->generateUrl('create_group'),
            'method' => 'PUT',
        ));

        return $this->render('ApiBundle:Default:index.html.twig',
            [
                'form_user'    => $newUserForm->createView(),
                'update_user'  => $updateUserForm->createView(),
                'form_group'   => $newGroupForm->createView(),
                'update_group' => $updateGroupForm->createView(),
            ]);
    }
}
