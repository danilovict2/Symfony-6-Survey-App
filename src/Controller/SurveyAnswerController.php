<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Entity\SurveyAnswer;
use App\Entity\SurveyQuestion;
use App\Entity\SurveyQuestionAnswer;
use App\Repository\SurveyQuestionAnswerRepository;
use App\Repository\SurveyQuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SurveyAnswerController extends AbstractController
{
    #[Route('/survey/{survey}/answers/save', name: 'survey_answer_save', methods: ["POST"])]
    public function index(
        Request $request,
        Survey $survey,
        ValidatorInterface $validator,
        SurveyQuestionRepository $surveyQuestionRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        // Casted to array for convenience
        $answers = (array)json_decode($request->request->getString('answers'));
        
        if (empty($answers)) {
            return $this->json(['error' => 'Please provide an answer'], 400);
        }

        $surveyAnswer = new SurveyAnswer();
        $surveyAnswer->setSurvey($survey)
            ->setStartDate(new \DateTimeImmutable())
            ->setEndDate($survey->getExpireDate())
        ;

        $entityManager->persist($surveyAnswer);
        
        foreach ($answers as $questionId => $fullAnswer) {
            $question = $surveyQuestionRepository->find($questionId);
            if (!$question) {
                $entityManager->clear();
                return $this->json(['error' => 'Invalid question ID: ' . $questionId], 400);
            }

            $answer = new SurveyQuestionAnswer();
            $answer->setQuestion($question)
                ->setAnswer($surveyAnswer)
                ->setFullAnswer(is_array($fullAnswer) ? json_encode($fullAnswer) : $fullAnswer)
            ;

            $errors = $validator->validate($answer);
            if ($errors->count() > 0) {
                $entityManager->clear();
                return $this->json(['error' => $errors[0]->getMessage()], 400);
            }
            $entityManager->persist($answer);
        }

        $entityManager->flush();
        return new JsonResponse('', 202);
    }
}
