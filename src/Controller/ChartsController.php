<?php

namespace App\Controller;

use App\Entity\Metric;
use App\Entity\Gender;
use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChartsController extends AbstractController
{
    #[Route('/charts', name: 'charts_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $metrics = [];
        $genders = [];
        $countries = [];

        if ($request->isMethod('POST')) {
            // Obtener la fecha del formulario
            $date = $request->request->get('date');

            // Buscar los datos relacionados si se ha ingresado una fecha
            $metrics = $entityManager->getRepository(Metric::class)
                ->findBy(['date' => new \DateTime($date)]);

            foreach ($metrics as $metric) {
                $genders[] = $entityManager->getRepository(Gender::class)
                    ->findBy(['idMetric' => $metric]);
                $countries[] = $entityManager->getRepository(Country::class)
                    ->findBy(['idMetric' => $metric]);
            }
        }

        // Renderizar la vista con o sin datos
        return $this->render('charts/index.html.twig', [
            'metrics' => $metrics,
            'genders' => $genders,
            'countries' => $countries,
        ]);
    }
}
