<?php

namespace project\GameHubBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Service\Geocoder\Request\GeocoderAddressRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Ivory\GoogleMap\Map;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{

    public function indexAction($id)
    {
        $snappy = $this->get('knp_snappy.pdf');
        $pdf="http://localhost/GameHub/web/uploads/paths/".$id;
        $html = '<body>   <img src="'.$pdf.'"> </body> ';

        $filename = 'myFirstSnappyPDF';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="'.$filename.'.pdf"'
            )
        );
    }
    public function testAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        return $this->render('projectGameHubBundle:evenement:backlist.html.twig');
    }

    public function testproAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        return $this->render('@projectGameHub/evenement/backlist.html.twig');
    }

    public function listAction()
    {

      $em = $this->getDoctrine()->getManager();
      $evenement = $em->getRepository("projectGameHubBundle:Evenement")->findAll();


        return $this->render('projectGameHubBundle:frontoffice:front.html.twig',array("evt" => $evenement));
    }

    public function mapAction()
    {


        $map = new Map();

//        $map->setCenter(new GeocoderAddressRequest("france"));
        return $this->render('projectGameHubBundle:evenement:map.html.twig',array("map" => $map));
    }
}
