<?php

namespace App\Controller;

use App\Entity\Survey;
use App\Repository\SurveyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/surveys')]
#[IsGranted("ROLE_USER")]
class SurveyController extends AbstractController
{
    public function __construct(private SurveyRepository $surveyRepository, private EntityManagerInterface $entityManager)
    {
    }

    #[Route('', name: 'surveys')]
    public function index(): Response
    {
        $surveys = array_map(fn ($survey) => $survey->toArray(), $this->surveyRepository->findBy(['createdBy' => $this->getUser()], ['id' => 'DESC']));
        return $this->render('survey/index.html.twig', [
            'surveys' => $surveys
        ]);
    }

    #[Route('/create', name: 'survey_create')]
    public function create(): Response
    {
        return $this->render('survey/create.html.twig');
    }

    #[Route('/store', name: 'survey_store', methods: ["POST"])]
    public function store(
        Request $request,
        ValidatorInterface $validator,
        #[Autowire('%photo_dir%')] string $photoDir
    ): JsonResponse {
        $data = $this->formatData(array_merge($request->request->all(), $request->files->all()));
        $survey = $data['id'] ? $this->surveyRepository->find($data['id']) : new Survey();
        $currentSurveyImage = $survey->getImage();
        $this->surveyRepository->fillSurveyWithData($survey, $data);

        $errors = $validator->validate($survey);
        if ($errors->count() > 0) {
            return $this->json(['error' => $errors[0]->getMessage()], 400);
        }

        if ($data['image'] !== $currentSurveyImage) {
            if (!is_null($currentSurveyImage)) {
                unlink($photoDir . '/' . $currentSurveyImage);
            }
            $image = $this->saveFileAndGetNewFilename($data['image'], $photoDir);
            $survey->setImage($image);
        }

        $this->entityManager->persist($survey);
        $this->entityManager->flush();
        return $this->json(['id' => $survey->getId()]);
    }

    private function formatData(array $data): array
    {
        $data['id'] = $data['id'] === 'null' ? null : (int)$data['id'];
        $data['status'] = $data['status'] === 'true';
        $data['description'] = $data['description'] === 'null' ? null : $data['description'];
        $data['image'] = $data['image'] === 'null' ? '' : $data['image'];
        $data['expire_date'] = $data['expire_date'] === 'null' ? null : new \DateTimeImmutable($data['expire_date']);
        $data['created_by'] = $this->getUser();
        return $data;
    }

    private function saveFileAndGetNewFilename(UploadedFile $file, string $directory): string
    {
        $filename = bin2hex(random_bytes(6)) . '.' . $file->guessExtension();
        $file->move($directory, $filename);

        return $filename;
    }

    #[Route('/{survey}', name: 'survey_show')]
    public function show(Survey $survey): Response
    {
        if ($this->getUser() !== $survey->getCreatedBy()) {
            return new Response('Unauthorized action!', 403);
        }

        return $this->render('survey/show.html.twig', [
            'survey' => $survey
        ]);
    }

    #[Route('/delete/{survey}', name: 'survey_delete', methods: ["POST"])]
    public function delete(Survey $survey): Response
    {
        $this->entityManager->remove($survey);
        $this->entityManager->flush();
        return new Response();
    }
}
