<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\SurveyAnswerRepository;
use App\Repository\SurveyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'dashboard')]
    public function index(SurveyRepository $surveyRepository, SurveyAnswerRepository $surveyAnswerRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $totalSurveys = count($user->getSurveys()->toArray());
        $latestSurvey = $surveyRepository->findOneBy(['createdBy' => $user], ['createdAt' => 'DESC'])?->toArray();
        $totalAnswers = $surveyAnswerRepository->getUserAnswersCount($user);
        $latestAnswers = array_map(fn($surveyAnswer) => $surveyAnswer->toArray(), $surveyAnswerRepository->getLatestUserAnswers($user));

        return $this->render('dashboard/index.html.twig', compact('totalSurveys', 'latestSurvey', 'totalAnswers','latestAnswers'));
    }
}
