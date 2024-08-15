<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use App\Validator\AdFieldsValidator;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Processes the requests to Ad DB.
 *
 * <pre>
 * </pre>
 *
 * @since 0.0.1
 *
 * @author WorHyako
 */
#[Route ('/ad', name: 'app_ad')]
class AdController extends AbstractController
{
    private ?int $successCode = 200;

    private ?int $failCode = 400;

    /**
     * Creates new Ad object on DB.
     *
     * @param AdRepository $adRepository
     *
     * @param EntityManagerInterface $em Entity manager.
     *
     * @param ValidatorInterface $validator Validator for new object.
     *                                      <p/>
     *                                      Should have {@link AdFieldsValidator} type.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[Route('/create',
        name: 'create',
        methods: ['GET', 'POST'])]
    public function create(AdRepository           $adRepository,
                           EntityManagerInterface $em,
                           ValidatorInterface     $validator,
                           Request                $request): JsonResponse
    {
        $query = $request->query;
        $ad = (new Ad())
            ->setText($query->get('text'))
            ->setPrice($query->get('price'))
            ->setBanner($query->get('banner'))
            ->setShowLimit($query->get('limit'));

        $adFromDB = $adRepository->findOneByFields($ad);
        if (Ad::cmpData($adFromDB, $ad)) {
            return $this->generateAnswer($ad, false, 'Object already exists');
        }
        unset($adFromDB);

        $validateResult = $validator->validate($ad);

        if ($validateResult->count()) {
            return $this->generateAnswer($ad, false, $validateResult->get(0)->getMessage());
        }

        $em->persist($ad);
        $em->flush();

        return $this->generateAnswer($ad, true, 'OK');
    }

    /**
     *
     *
     * @param AdRepository $adRepository
     *
     * @param EntityManagerInterface $em
     *
     * @param ValidatorInterface $validator
     *
     * @param Request $request
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    #[Route('/edit/{id}',
        name: 'edit',
        requirements: ['id' => '\d+'],
        methods: ['GET', 'POST'])]
    public function edit(AdRepository           $adRepository,
                         EntityManagerInterface $em,
                         ValidatorInterface     $validator,
                         Request                $request,
                         int                    $id): JsonResponse
    {
        $ad = $adRepository->find($id);

        if ($ad === null) {
            return $this->generateAnswer($ad, false, 'Object not exists');
        }

        $query = $request->query;
        $ad->setText($query->get('text'))
            ->setPrice($query->get('price'))
            ->setBanner($query->get('banner'))
            ->setShowLimit($query->get('limit'));

        $validateResult = $validator->validate($ad);
        if ($validateResult->count()) {
            return $this->generateAnswer($ad, false, $validateResult->get(0)->getMessage());
        }

        $em->flush();

        return $this->generateAnswer($ad, true, 'OK');
    }

    /**
     *
     *
     * @param AdRepository $adRepository
     *
     * @param EntityManagerInterface $em
     *
     * @return JsonResponse
     */
    #[Route('/get',
        name: 'get',
        methods: ['GET'])]
    public function get(AdRepository           $adRepository,
                        EntityManagerInterface $em): JsonResponse
    {
        $ad = $adRepository->findOneByField();
        return $this->generateAnswer($ad, true, 'OK');
    }

    /**
     * Generates answer via {@link JsonResponse}.
     *
     * <pre>
     *          generateAnswer($object, true, 'OK');
     *          generateAnswer($object, false, $failMessage);
     * </pre>
     *
     * @param Ad $ad Object to parse fields to make answer.
     *
     * @param bool $positive Is answer should be positive.
     *
     * @param string $message Message value for answer.
     *
     * @return JsonResponse
     */
    private function generateAnswer(Ad     $ad,
                                    bool   $positive,
                                    string $message): JsonResponse
    {
        return $this->json([
            'message' => $message,
            'code' => $positive ? $this->successCode : $this->failCode,
            'data' => $positive
                ? [
                    'id' => $ad->getId(),
                    'text' => $ad->getText(),
                    'banner' => $ad->getBanner()]
                : []],
            $positive ? $this->successCode : $this->failCode);
    }
}
